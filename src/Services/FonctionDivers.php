<?php
/**
 * Created by PhpStorm.
 * User: JIDAL_MOHAMED
 * Date: 19/06/2018
 * Time: 14:03
 */

namespace App\Services;


class FonctionDivers
{
    public static function getErrorsAsArray($sErrors)
    {
        $aArray = preg_split('/\r\n|\r|\n/', $sErrors);
        $aArray = array_map('trim', $aArray);
        $aErrors = array();
        foreach ($aArray as $iKey => $sValue)
        {
            if ( strpos($sValue, 'ERROR: ') !== false )
            {
                $aErrors[] = str_replace('ERROR: ', '', $sValue);
            }
        }

        return $aErrors;

    }

    public static function getCities(){
        $aCities = array(
            array("ville"=>"Casablanca","code"=>"1532755","ville_ar"=>"الدار البيضاء"),
            array("ville"=>"Rabat","code"=>"1539359","ville_ar"=>"الرباط"),
            array("ville"=>"Tanger","code"=>"1540935","ville_ar"=>"طنجة"),
            array("ville"=>"Oujda","code"=>"1538412","ville_ar"=>"وجدة"),
            array("ville"=>"Agadir","code"=>"1542773","ville_ar"=>"أكادير"),
            array("ville"=>"El jadida","code"=>"1534936","ville_ar"=>"الجديدة"),
            array("ville"=>"Khouribga","code"=>"1537353"),
            array("ville"=>"Beni-Mellal","code"=>"1532231","ville_ar"=>"بني ملال"),
            array("ville"=>"Marrakech","code"=>"1537782","ville_ar"=>"مراكش"),
            array("ville"=>"Meknes","code"=>"1537862","ville_ar"=>"مكناس"),
            array("ville"=>"Fes","code"=>"1535450","ville_ar"=>"فاس"),
            array("ville"=>"Taza","code"=>"1541306","ville_ar"=>"تازة"),
            array("ville"=>"Tetouan","code"=>"1541445","ville_ar"=>"تطوان"),
            array("ville"=>"Kenitra","code"=>"1537281"),
            array("ville"=>"Larache","code"=>"1537598","ville_ar"=>"العرائش")
        );
        return $aCities;
    }

}