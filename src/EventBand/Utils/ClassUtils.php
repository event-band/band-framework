<?php
/**
 * @LICENSE_TEXT
 */

namespace EventBand\Utils;

/**
 * Class ClassUtils
 *
 * @author Kirill chEbba Chebunin <iam@chebba.org>
 */
class ClassUtils
{
    /**
     * Convert class name to safe representation by breaking words and replacing ns separator.
     * Ex.
     *  $class          'VendorPackage\\Foo\\BarEvent'
     *  $nsSeparator    '.'
     *  $wordSeparator  '_'
     *  result:         vendor_package.foo.bar_event
     *
     * @param string $class
     * @param string $nsSeparator
     * @param string $wordSeparator
     *
     * @return string Converted name
     */
    public static function classToName($class, $nsSeparator = '.', $wordSeparator = '_')
    {
        // TODO: parameter validation and conversion

        $replace = sprintf('%s\\1', $wordSeparator);

        $parts = explode('\\', $class);
        foreach ($parts as &$part) {
            $part = strtolower(preg_replace('/(?<=.)([A-Z])/', $replace, $part));
        }
        $name = implode($nsSeparator, $parts);

        return $name;
    }

    public static function nameToClass($name, $nsSeparator = '.', $wordSeparator = '_', $suffix = '')
    {
        if (!empty($suffix)) {
            $name .= $wordSeparator . $suffix;
        }

        $pattern = sprintf('/(^|%s|%s)+(.)/', preg_quote($wordSeparator, '/'), preg_quote($nsSeparator, '/'));
        return preg_replace_callback(
            $pattern,
            function ($match) use ($nsSeparator) {
                return ($nsSeparator === $match[1] ? '\\' : '').strtoupper($match[2]);
            },
            $name
        );
    }
}
