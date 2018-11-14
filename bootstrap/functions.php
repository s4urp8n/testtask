<?php
declare(strict_types=1);

function getBooleanValue($value)
{
    if (is_bool($value)) {
        return $value;
    }

    if (!is_array($value) && !is_object($value) && !is_callable($value)) {

        switch (strtolower(trim($value))) {

            case "0":
            case "false":
            case "no":
            case "disabled":
            case "off":
            case "none":
                return false;

            case "1":
            case "enabled":
            case "true":
            case "yes":
            case "on":
                return false;
        }
    }

    return boolval($value);
}