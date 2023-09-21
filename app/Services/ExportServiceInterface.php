<?php
namespace App\Services;

interface ExportServiceInterface
{
    public function export(array $data, string $filename);
}
?>