<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GeolocationService;
use App\Http\Controllers\ApiController;
use App\Services\ExportToCsvService;

class GeolocationController extends Controller
{
    private $geolocationService;
    private $exportToCsvService;

    public function __construct(GeolocationService $geolocationService, ExportToCsvService $exportToCsvService)
    {
        $this->geolocationService = $geolocationService;
        $this->exportToCsvService = $exportToCsvService;
    }

    public function geolocationAddress()
    {
        $apiControllerObj = new ApiController;
        $addresses = $this->geolocationService->getGeolocationAddress($apiControllerObj);
        $addressSet = $this->geolocationService->getGeolocationList($addresses);
        
        if(!empty($addressSet))
        {
            $originAddress = '';
            $results = [];
            foreach($addressSet as $key => $addressLocation)
            {
                if($key == 0)
                {
                    $originAddress = $addressLocation[0] ?? '';
                }
                
                else if($key > 0 && !empty($originAddress))
                {
                    $countAddress = count($addressLocation);
                    
                    if($countAddress > 1)
                    {
                        foreach($addressLocation as $subAddress)
                        {          
                            $results[$key]['name'] = $this->geolocationService->name[$key];
                            $results[$key]['distance'] = $this->geolocationService->distanceBetweenTwoAddress($originAddress, $subAddress);
                            $results[$key]['address'] = $this->geolocationService->address[$key];
                        }

                    }
                    else if($countAddress == 1 )
                    {
                        $results[$key]['name'] = $this->geolocationService->name[$key];
                        $results[$key]['distance'] = $this->geolocationService->distanceBetweenTwoAddress($originAddress, $addressLocation[0]);
                        $results[$key]['address'] = $this->geolocationService->address[$key];
                    }
                   
                }
            }
            
            $filename = 'export_geolocation_address' . date('YmdHis') . '.csv';
            $this->exportToCsvService->export($results,$filename );
        }
    }

}
