<?php


class Encryption
{
    public static function encrypt($source)
    {
        return password_hash($source, PASSWORD_DEFAULT);
    }
    public static function is_verify($source, $hash)
    {
        return password_verify($source, $hash);
    }
}
