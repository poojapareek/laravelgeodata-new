<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    public function fetchPositionStackApiData($address)
    {
        try 
        {
            $queryString = http_build_query([
                'access_key' => config('app.positionstack_api_key'),
                'query' => $address
            ]);
            
            $ch = curl_init(sprintf('%s?%s', config('app.positionstack_api_url').'forward', $queryString));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
            $json = curl_exec($ch);
            
            curl_close($ch);
            
            $apiResult = json_decode($json, true);
            
            return $apiResult;
        } 
        catch (\Exception $e)
        {
            \Log::info('POSITION_STACK_API_ERROR: ', ['msg' => $e->getTraceAsString()]);
            report($e);
        }
    }
}
