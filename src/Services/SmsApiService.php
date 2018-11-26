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
        $this->httpClient->get('send', [
            'query' => [
                'action' => 'SMS',
                'app_id' => self::APP_ID,
                'senderid' => self::SENDER_ID,
                'msg' => $message,
                'tel' => $this->standardizeTel($phoneNumber)
            ]
        ]);
    }

    public function standardizeTel($phoneNumber) {
        if(strpos($phoneNumber, '0') === 0) {
            $phoneNumber = '212' . substr(str_replace(' ', '', $phoneNumber), 1);
        }

        return $phoneNumber;
    }

}