<?php

namespace Wprsrv\Admin\Export;

use Wprsrv\PostTypes\Objects\Reservable;

/**
 * Class ExporterFactory
 *
 * Create exporters.
 *
 * @since 0.1.1
 * @package Wprsrv\Admin\Export
 */
class ExporterFactory
{
    /**
     * Create a new exporter of a certain format and for a certain reservable.
     *
     * @static
     * @since 0.1.1
     *
     * @param String $format Format to use for exports, e.g `csv`.
     * @param \Wprsrv\PostTypes\Objects\Reservable|null $reservable Reservable to
     *                                                              generate exporter
     *                                                              for. Null if
     *                                                              global export.
     * @param String[] $datesRange Y-m-d Date range to export reservations for.
     *
     * @return \Wprsrv\Admin\Export\Exporter
     */
    public static function create($format, Reservable $reservable = null, $dateRange = [])
    {
        // Sanitize format first.
        $format = strtolower($format);
        $format = preg_replace('%[^a-z]%', '', $format);

        switch ($format) {
            case 'csv':
                return new CsvExporter($reservable, $dateRange);
                break;

            default:
                throw new \InvalidArgumentException('Not exporter available for format ' . $format);
        }
    }
}
