<?php

namespace Esyede\Wabot\Helpers;

class Common
{
    public static function toIndonesianPhonePrefix($number)
    {
        if (static::startsWith($number, '08')) {
            $number = static::replaceFirst('08', '628', $number);
        }

        if (static::startsWith($number, '+62')) {
            $number = static::replaceFirst('+62', '62', $number);
        }

        return $number;
    }

    public static function fixPersonName($name)
    {
        return preg_replace("/^[a-zA-z'.]/", '', $name);
    }

    public static function startsWith($haystack, $needle)
    {
        return ('' !== (string) $needle && 0 === strncmp($haystack, $needle, strlen($needle)));
    }

    public static function replaceFirst($search, $replace, $subject)
    {
        if ('' === $search) {
            return $subject;
        }

        $position = strpos($subject, $search);

        return (false === $position)
            ? $subject
            : substr_replace($subject, $replace, $position, strlen($search));
    }
}