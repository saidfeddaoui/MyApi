<?php

namespace App\Services;

class VerificationCodeGenerator implements VerificationCodeGeneratorInterface
{

    public function generate()
    {
        return sprintf('%04d', random_int(0, 9999));
    }

}