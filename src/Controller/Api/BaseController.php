<?php

namespace App\Controller\Api;

use App\DTO\Api\ApiResponse;
use FOS\RestBundle\Controller\FOSRestController;

class BaseController extends FOSRestController
{

    public function respondWith($data = [], $status = ApiResponse::OK)
    {
        return new ApiResponse($data, $status);
    }

}