<?php

namespace App\Service;

class CvsHelper
{
    public function array2csv(array &$array)
    {
        if (count($array) == 0) {
            return null;
        }
        ob_start();
        $df = fopen("php://output", 'w');
        fprintf($df, chr(0xEF).chr(0xBB).chr(0xBF));
        fputcsv($df, array_keys(reset($array)),",");
        foreach ($array as $row) {
            fputcsv($df, $row, ",");
        }
        fclose($df);
        return ob_get_clean();
    }
}
