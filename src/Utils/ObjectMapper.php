<?php
/**
 * Created by PhpStorm.
 * User: ahargoug
 * Date: 20/09/2018
 * Time: 16:51
 */


namespace App\Utils;

class ObjectMapper {

    public static function mapObjectToEntity($data,$config) {

          $entityName= $config['EntityName'];
          $nameSpace= $config['NameSpace'];

        if(empty($data) || empty($entityName)) return "invalid entity or data";


        //$post = json_encode($data);


       // if (json_last_error() !== JSON_ERROR_NONE) return "invalid json";
        $fields = get_object_vars($data);// php reflection
        $nameSpace .= $entityName;
        $Entity = new $nameSpace();
        if(!$Entity ) return "error in entity object";

        foreach($fields as $name => $value ) {
            try {
                $reflectionMethod = new \ReflectionMethod($nameSpace, 'set'.$name);
                $reflectionMethod->invoke($Entity, $value);
            } catch (Exception $e) {
                return $name.' field not found in entity object';
            }
        }
        return $Entity;
    }


}
