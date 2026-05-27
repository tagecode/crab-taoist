<?php

namespace Support\Yaf;

class Compat
{
    public static function isNamespaceMode()
    {
        return class_exists('Yaf\\Application', false);
    }

    public static function isLegacyMode()
    {
        return class_exists('Yaf_Application', false);
    }

    public static function isSupported()
    {
        return self::isNamespaceMode() || self::isLegacyMode();
    }

    public static function modeLabel()
    {
        if (self::isNamespaceMode()) {
            return 'namespace (yaf.use_namespace=1)';
        }

        if (self::isLegacyMode()) {
            return 'legacy (yaf.use_namespace=0)';
        }

        return 'unsupported';
    }
}
