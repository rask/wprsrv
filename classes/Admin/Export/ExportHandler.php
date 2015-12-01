<?php

namespace Wprsrv\Admin\Export;

use Wprsrv\PostTypes\Objects\Reservable;

/**
 * Class ExportsHandler
 *
 * @since 0.2.0
 * @package Wprsrv\Admin\Export
 */
class ExportHandler
{
    /**
     * Validate that an export request is valid.
     *
     * @since 0.2.0
     * @access protected
     * @return Boolean
     */
    protected function validateExportRequest()
    {
        if (!isset($_REQUEST['download_export']) || !isset($_POST)) {
            return false;
        }

        $nonce = $_POST['_wpnonce'];

        if (!wp_verify_nonce($nonce, 'wprsrv-export') || empty($_POST['wprsrv'])) {
            return false;
        }

        return true;
    }

    /**
     * Get the export request parameters.
     *
     * @since 0.2.0
     * @access protected
     *
     * @param mixed[] $args Export request parameters.
     *
     * @return mixed[]
     */
    protected function getExportParameters(Array $exportArgs)
    {
        $format = $exportArgs['format'];
        $reservable_id = $exportArgs['reservable_id'];

        $reservable = new Reservable($reservable_id);

        if (!$reservable instanceof Reservable || empty($format)) {
            throw new \InvalidArgumentException('Cannot export reservation data for null reservable.');
        }

        $dateRange = [
            'start' => $exportArgs['date_start'] ? $exportArgs['date_start'] : false,
            'end' => $dateEnd = $exportArgs['date_end'] ? $exportArgs['date_end'] : false
        ];

        // Get rid of empty (falsey) values.
        $dateRange = array_filter($dateRange);

        return [
            'format' => $format,
            'reservable' => $reservable,
            'date_range' => $dateRange
        ];
    }

    /**
     * Handle a requested export. Takes the request params and dumps the export data.
     *
     * @since 0.2.0
     * @return void
     */
    public function handleExport()
    {
        if (!$this->validateExportRequest()) {
            return;
        }

        $args = $_POST['wprsrv'];

        try {
            $exportParams = $this->getExportParameters($args);

            $exporter = ExporterFactory::create(
                $exportParams['format'],
                $exportParams['reservable'],
                $exportParams['date_range']
            );

            $exporter->dumpExport();
        } catch (\OutOfBoundsException $oobe) {
            wp_die(__('The selected date range has no reservation data to export.', 'wprsrv'));
        } catch (\Exception $e) {
            \Wprsrv\wprsrv()->logger->alert('Could not generate export: {msg}', ['msg' => $e->getMessage()]);

            wp_die(__('There was an error with the export, please try again.', 'wprsrv'));
        }
    }
}
