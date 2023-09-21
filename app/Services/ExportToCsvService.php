<?php
namespace App\Services;

use App\Services\ExportServiceInterface;
use Illuminate\Support\Facades\Log;

class ExportToCsvService implements ExportServiceInterface
{
    public function export(array $data, string $filename)
    {
        try 
        {
            $header = $this->getHeader();
            $output = fopen('php://output', 'w');
        
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            
            $distance = array_column($data, 'distance');
            array_multisort($distance, SORT_ASC, $data);

            array_unshift($data,$header);

            foreach ($data as $key => $row) 
            {
                if($key > 0)
                {
                    $row = ["no" => $key] + $row;
                }
                $row['name'] = empty($row['name']) ? 'NA': $row['name'];
                
                fputcsv($output, $row);
            }

            fclose($output);
        }
        catch (\Exception $e)
        {
            \Log::info('EXPORT_CSV_ERROR '.__METHOD__, ['line'=> $e->getLine(), 'msg' => $e->getTraceAsString()]);
            report($e);
        }
    }

    protected function getHeader()
    {
        return ['No', 'Name', 'Distance', 'Address'];
    }

}
?>