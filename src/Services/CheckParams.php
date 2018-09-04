<?php

namespace App\Service;

class CheckParams
{
    // valider le format de téléphone
    public function checkPhone($phone){
        
        if (!preg_match('/^0\d ?(\d{2}) ?(\d{2}) ?(\d{2}) ?(\d{2})$/', $phone) && !preg_match('/^212\d{9}$/', $phone)) {
              return array('status'=>'KO','message'=>'Veuillez saisir un numéro de téléphone valide');
        }else{
              return array('status'=>'OK','message'=>''); 
        }
        
    }

    public function checkMethod($request,$method){
        if (!$request->isMethod($method)){
            return array('status'=>'KO','message'=>'Erreur d\'envoi :: Method');
        }
        return array('status'=>'OK','message'=>'');
    }

    // Valider les params
    public function checkParams($params){
        $optional = array('firebase_token','latitude','longtitude','os');
        foreach ($params as $key => $param){
            if ((empty($param) || is_null($param)) && !in_array($key,$optional)){
                return array('status'=>'KO','message'=>'Paramètre (s) invalide (s) :: '.$key);
            }
        }
        return array('status'=>'OK','message'=>'');

    }
}
