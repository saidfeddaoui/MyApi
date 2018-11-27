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
        dump($phoneNumber);die;
        $this->httpClient->get('send', [
            'query' => [
                'action' => 'SMS',
                'app_id' => self::APP_ID,
                'senderid' => self::SENDER_ID,
                'msg' => $message,
                'tel' => $phoneNumber
            ]
        ]);
    }


}