<?php

namespace App\Application\Service;

class TokenService
{
    // @todo strategy?
    private $strategy;

    public static function generateToken()
    {
        return md5(uniqid(rand(), true));
    }

    public static function generateRandomBytes()
    {
        return bin2hex(random_bytes(8));
    }
}