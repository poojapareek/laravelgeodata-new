<?php

namespace App\Services;

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Log;


class GeolocationService
{
    public $address = 
    [
        "Sint Janssingel 92, 5211 DA 's-Hertogenbosch, The Netherlands ",
        "Deldenerstraat 70, 7551AH Hengelo, The Netherlands",
        "46/1 Office no 1 Ground Floor , Dada House , Inside dada silk mills",
        "compound, Udhana Main Rd, near Chhaydo Hospital, Surat, 394210, India",
        "Weena 505, 3013 AL Rotterdam, The Netherlands",
        "221B Baker St., London, United Kingdom",
        "1600 Pennsylvania Avenue, Washington, D.C., USA",
        "350 Fifth Avenue, New York City, NY 10118",
        "Saint Martha House, 00120 Citta del Vaticano, Vatican City",
        "5225 Figueroa Mountain Road, Los Olivos, Calif. 93441, USA"
    ];
    public $name = 
    [
        'Adchieve HQ',
        'Eastern Enterprise B.V.',
        'Eastern Enterprise',
        '',
        'Adchieve Rotterdam',
        'Sherlock Holmes',
        'The White House',
        'The Empire State Building',
        'The Pope',
        'Neverland'
    ];

    public function getGeolocationAddress(ApiController $apiController): array
    {
        // Fetch data from the Positionstack API
        foreach($this->address as $address)
        {
            $addressGeolocation[] = $apiController->fetchPositionStackApiData($address);
        }
        return $addressGeolocation;
    }

    


    public function distanceBetweenTwoAddress($originAddress, $destAddress)
    {
        try
        {
            // Calculate the distance using the Haversine formula
            $earthRadius = 6371; // Radius of the Earth in kilometers
            $lat1 = deg2rad($destAddress['latitude']);
            $lon1 = deg2rad($destAddress['longitude']);
            $lat2 = deg2rad($originAddress['latitude']);
            $lon2 = deg2rad($originAddress['longitude']);

            $dlat = $lat2 - $lat1;
            $dlon = $lon2 - $lon1;

            $a = sin($dlat / 2) ** 2 + cos($lat1) * cos($lat2) * sin($dlon / 2) ** 2;
            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

            $distance = number_format($earthRadius * $c,2);
            return $distance; //in kms
        }
        catch (\Exception $e)
        {
            \Log::info('GEOLOCATION_DISTANCE_ERROR '.__METHOD__ , ['line'=> $e->getLine(),  'msg' => $e->getTraceAsString()]);
            report($e);
        }

    }

    public function getGeolocationList($addresses)
    {
        $addressSet = [];
        if(!empty($addresses))
        {
            $addressSet = array_column($addresses, 'data');
        }

        return $addressSet;
    }
}