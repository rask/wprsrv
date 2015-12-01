<?php

namespace Wprsrv\Admin\Export;

/**
 * Class CsvExporter
 *
 * @package Wprsrv\Admin\Export
 */
class CsvExporter extends Exporter
{
    /**
     * Construct the export dump into CSV format. Return true if succeeded, false if
     * not.
     *
     * @since 0.2.0
     * @access protected
     * @return Boolean
     */
    protected function generateExportData()
    {
        $data = $this->getAllReservationData();

        $fieldSeparator = ';';
        $lineSeparator = "\n";

        if (empty($data)) {
            throw new \LogicException('Reservations generated an empty export dataset.');
        }

        $colNames = array_keys($data[0]);
        $rows = [];

        foreach ($data as $reservationData) {
            $reservationData = array_map('strval', $reservationData);
            $rows[] = array_values($reservationData);
        }

        if (empty($rows)) {
            throw new \LogicException('Reservations generated an empty export value set for rows.');
        }

        $exportValue = implode($fieldSeparator, $colNames);

        foreach ($rows as $row) {
            $exportValue .= $lineSeparator . implode($fieldSeparator, $row);
        }

        $this->exportData = $exportValue;

        if (empty($this->exportData)) {
            throw new \LogicException('Reservations generated an empty export data value.');
        }
    }

    /**
     * Output the export to the browser or similar.
     *
     * @since 0.2.0
     * @return void
     */
    public function dumpExport()
    {
        $this->generateExportData();

        header('Content-type: application/csv');
        header('Content-length: ' . strlen($this->exportData));
        header('Content-disposition: attachment; filename=wprsrv-export.csv');

        echo $this->exportData;

        exit;
    }
}
