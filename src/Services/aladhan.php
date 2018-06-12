<?php

/**
 * Created by PhpStorm.
 * User: JIDAL_MOHAMED
 * Date: 12/06/2018
 * Time: 13:41
 */

namespace App\Services;

class aladhan
{
    public function __construct($city)
    {
        $url = "http://api.aladhan.com/v1/calendarByCity?city=".$city."&country=morocco&method=3&month=".date("m")."&year=".date("Y");
;
        /* Let's use cURL to query the API and get all the JSON data */
        $curlobj = curl_init();
        curl_setopt($curlobj, CURLOPT_URL, $url);
        curl_setopt($curlobj, CURLOPT_RETURNTRANSFER, 1);
        $aladhan_api = curl_exec($curlobj);
        curl_close($curlobj);

        /* Now let's create a adan object of the returned json */
        $Adan = json_decode($aladhan_api);
        $this->adan = $Adan->data;

    }

    public function getTimings()
    {
       $timings = array();

       foreach ( $this->adan as $key =>$value){
           if($value->date->gregorian->date == date("d-m-Y")){
               $timings = array("As-sobh"=>substr($value->timings->Fajr, 0, 5),
                                "Ad-dohr"=>substr($value->timings->Dhuhr, 0, 5),
                                "Al-asr"=>substr($value->timings->Asr, 0, 5),
                                "Al-maghrib"=>substr($value->timings->Maghrib, 0, 5),
                                "Al-ichae"=>substr($value->timings->Isha, 0, 5));
           }
       }

        return $timings;
    }

}