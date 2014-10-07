<?php
/**
 * Created by PhpStorm.
 * User: framato
 * Date: 07/10/14
 * Time: 15:29
 */

namespace Whopa\Myotp;


class Myotp {

    /**
     * Devuelve el hash en dígitos OTP
     *
     * @param $hash
     * @param int $digits
     * @return string
     */

    private static function _keyToString($hash, $digits = 6)
    {
        $hresult = [];

        foreach (str_split($hash, 2) as $hex)
        {
            $hresult[] = hexdec($hex);
        }

        $offset = $hresult[19] & 0xf;

        $decimal = (
            (($hresult[$offset+0] & 0x7f) << 24) |
            (($hresult[$offset+1] & 0xff) << 16) |
            (($hresult[$offset+2] & 0xff) << 8) |
            ($hresult[$offset+3] & 0xff)
        );

        $optKey = str_pad($decimal, $digits, '0', STR_PAD_LEFT);
        $optKey = substr($optKey, (-1 * $digits));
        return $optKey;
    }

    /**
     * Genera una key en base al valor del contador.
     *
     * @param $key
     * @param $counter
     * @param int $digits
     * @return string
     */

    private static function _generateKey($key, $counter, $digits = 6)
    {
        $char_counter = array(0, 0, 0, 0, 0, 0, 0, 0);
        for($i=7; $i>=0; $i--)
        {
            $char_counter[$i] = pack('C*', $counter);
            $counter = $counter >> 8;
        }

        $binary_co = implode($char_counter);

        if (strlen($binary_co) < 8)
        {
            $binary_co = str_repeat(chr(0), 8 - strlen($binary_co)) . $binary_co;
        }

        $hash = hash_hmac('sha1', $binary_co, $key);

        return Myotp::_keyToString($hash, $digits);

    }

    /**
     * Codifica el dato a MIME base32
     *
     * @param $optKeying - es el string a codificar
     * @return string
     */

    private static function _base32_decode($optKeying)
    {
        static $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

        $tmp = '';

        foreach (str_split($optKeying) as $c)
        {
            if (false === ($v = strpos($alphabet, $c)))
            {
                $v = 0;
            }
            $tmp .= sprintf('%05b', $v);
        }
        $args = array_map('bindec', str_split($tmp, 8));
        array_unshift($args, 'C*');

        return rtrim(call_user_func_array('pack', $args), '\0');
    }

    /**
     * Obtiene el tiempo en formato UNIX
     *
     * @return int
     */

    private static function _getTime()
    {
        return time();
    }

    /**
     * Genera el OTP en base al timestamp y al tiempo de vida.
     *
     * @param $key
     * @param $window
     * @param bool $timestamp
     * @return string
     */

    public static function generate($key, $window, $timestamp = false)
    {
        if (!$timestamp && $timestamp !== 0)
        {
            $timestamp = Myotp::_getTime();
        }

        $counter = intval($timestamp / $window);

        return Myotp::_generateKey(Myotp::_base32_decode($key), $counter);
    }

    /**
     * Genera un key aleatorio y secreto para ser usado para generar los OTP.
     *
     * @return string
     */

    public static function userRandomKey()
    {
        static $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $key = '';

        for($i=0; $i<16; $i++)
        {
            $offset = rand(0, strlen($alphabet) -1);
            $key .= $alphabet[$offset];
        }

        return $key;

    }





} 