<?php

namespace App\Helpers;

class FileHelper
{
    public static function readCsv(string $path)
    {
        $content = [];
        if (($open = fopen($path, "r")) !== FALSE) {
            while (($data = fgetcsv($open, 1000)) !== FALSE) {
                $content[] = $data;
            }
            fclose($open);
        }
        return $content;
    }
}
