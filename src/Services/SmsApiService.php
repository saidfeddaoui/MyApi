<?php

namespace App\Services;

class SmsApiService extends ApiCustomerService
{

    const APP_ID = 'MAMDAMCMA';
    const SENDER_ID = 'MAMDA';

    /**
     * @param string $phoneNumber
     * @param string $message
     */
    public function sendSms(string $phoneNumber, string $message)
    {
        $Action = 'SMS';
        $AppId = self::APP_ID;
        $SenderId = self::SENDER_ID;
        $message = 'TEST '.$message;
        $Tele = $phoneNumber;
        $url_sms = "http://smsprod.mobiblanc.com/api.php/send_sms/send";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url_sms);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "action=" . $Action . "&app_id=" . $AppId . "&senderid=" . $SenderId . "&msg=" . $message . "" . "&tel=" . $Tele . "");
        $result = curl_exec($ch);
        curl_close($ch);
        var_dump($result);die;

    }


}