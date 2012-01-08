<?php

namespace ZfcUser\Util;

use EdpCommon\Util\String,
    ZfcUser\Module as ZfcUser;

abstract class Password
{
    public static function hash($password, $salt = false)
    {
        if (!$salt) {
            $salt = static::getPreferredSalt();
        }
        return crypt($password, $salt);
    }

    public static function getSalt($algorithm = 'blowfish', $cost = 10)
    {
        $cost = (int) $cost;

        switch ($algorithm) {
            case 'blowfish':
                return static::generateBlowfishSalt($cost);
                break;
            case 'sha512':
                return static::generateSha512Salt($cost);
                break;
            case 'sha256':
                return static::generateSha256Salt($cost);
                break;
            default:
                throw new \Exception(sprintf(
                    'Unsupported hashing algorithm: %s',
                    $algorithm
                ));
                break;
        }
    }

    public static function getPreferredSalt()
    {
        $algorithm = strtolower(ZfcUser::getOption('password_hash_algorithm'));
        switch ($algorithm) {
            case 'blowfish':
                $cost = ZfcUser::getOption('blowfish_cost');
                break;
            case 'sha512':
                $cost = ZfcUser::getOption('sha512_rounds');
                break;
            case 'sha256':
                $cost = ZfcUser::getOption('sha256_rounds');
                break;
            default:
                throw new \Exception(sprintf(
                    'Unsupported hashing algorithm: %s',
                    $algorithm
                ));
                break;
        }
        return static::getSalt($algorithm, (int) $cost);
    }

    protected static function generateBlowfishSalt($cost = 10)
    {
        $cost = str_pad(($cost < 4 || $cost > 31) ? 10 : $cost, 2, '0', STR_PAD_LEFT);
        return '$2a$' . $cost . '$' . static::getCryptSaltString() . '$';
    }

    protected static function generateSha256Salt($cost = 5000)
    {
        $cost = ($cost < 1000 || $cost > 999999999) ? 5000 : $cost;
        return '$5$rounds=' . $cost . '$' . static::getCryptSaltString() . '$';
    }

    protected static function generateSha512Salt($cost = 5000)
    {
        $cost = ($cost < 1000 || $cost > 999999999) ? 5000 : $cost;
        return '$6$rounds=' . $cost . '$' . static::getCryptSaltString() . '$';
    }

    protected static function getCryptSaltString($length = 22)
    {
        return str_replace('+', '.', substr(base64_encode(String::getRandomBytes($length)), 0, $length));
    }
}
