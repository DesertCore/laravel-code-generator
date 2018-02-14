<?php

namespace CrestApps\CodeGenerator\Support;

use CrestApps\CodeGenerator\Support\Config;
use Illuminate\Support\Str as LaravelStr;

class Str extends LaravelStr
{
    /**
     * Gets a plural version of a giving word.
     *
     * @param string $word
     * @param int $count
     *
     * @return string
     */
    public static function plural($word, $count = 2)
    {
        $definitions = Config::getPluralDefinitions();

        if (array_key_exists($word, $definitions)) {
            return $definitions[$word];
        }

        return parent::plural($word, $count);
    }

    /**
     * Gets a singular version of a giving word.
     *
     * @param string $word
     *
     * @return string
     */
    public static function singular($word)
    {
        $definitions = Config::getPluralDefinitions();

        if (($singular = array_search($word, $definitions)) !== false) {
            return $singular;
        }

        return parent::singular($word);
    }

    /**
     * Converts the give value into a title case.
     *
     * @param string $value
     *
     * @return string
     */
    public static function kebabCase($value)
    {
        return self::snake($value, '-');
    }

    /**
     * Converts the give value into a title case.
     *
     * @param string $value
     *
     * @return string
     */
    public static function titleCase($value)
    {
        return self::title($value);
    }

    /**
     * Eliminate a duplicate giving phrase from a giving string
     *
     * @param string $subject
     * @param string $search
     *
     * @return string
     */
    public static function eliminateDupilcates($subject, $search)
    {
        $pattern = str_repeat($search, 2);

        while (strpos($subject, $pattern) !== false) {
            $subject = str_replace($pattern, $search, $subject);
        }

        return $subject;
    }

    /**
     * Removes a string from the end of a giving subject.
     *
     * @param  string  $subject
     * @param  string  $search
     *
     * @return string
     */
    public static function trimEnd($subject, $search)
    {
        while (($position = strripos($subject, $search)) !== false) {
            $subject = substr($subject, 0, $position);
        }

        return $subject;
    }

    /**
     * Removes a string from the start of a giving subject.
     *
     * @param  string  $subject
     * @param  string  $search
     *
     * @return string
     */
    public static function trimStart($subject, $search)
    {

        while (1) {
            $length = strlen($search);
            if (substr($subject, 0, $length) != $search) {
                break;
            }

            $subject = substr($subject, $length);
        }

        return $subject;
    }

    /**
     * Checks if a string matches at least one giving pattern
     *
     * @param string|array $patterns
     * @param string $subject
     * @param string $matchedPattern
     * @param bool $caseSensitive
     *
     * @return bool
     */
    public static function match($patterns, $subject, &$matchedPattern = '', $caseSensitive = false)
    {
        if (!is_array($patterns)) {
            $patterns = (array) $patterns;
        }

        $lowerSubject = strtolower($subject);

        foreach ($patterns as $pattern) {

            if ($caseSensitive) {
                if (self::is($pattern, $subject)) {
                    $matchedPattern = $pattern;
                    return true;
                }
            } else {
                $lowerPattern = strtolower($pattern);
                if (self::is($lowerPattern, $lowerSubject)) {
                    $matchedPattern = $pattern;
                    return true;
                }
            }

        }

        return false;
    }

    /**
     * Trims a giving string from whitespaces and single/double quotes and square brake.
     *
     * @param string $subject
     *
     * @return string
     */
    public static function trimQuots($subject)
    {
        return trim($subject, " \t\n\r\0\x0B \"'[]");
    }

    /**
     * Makes the proper english case giving name and a file type
     *
     * @param string $name
     * @param string $key
     *
     * @return string
     */
    public static function properSnake($name, $key)
    {
        $snake = self::snake($name);

        if ($key && Config::shouldBePlural($key)) {
            return self::plural($snake);
        }

        return $snake;
    }

    /**
     * Adds a preFix string at the begining of another giving string if it does not already ends with it.
     *
     * @param  string  $name
     * @param  string  $fix
     *
     * @return string
     */
    public static function prefix($name, $fix)
    {
        if (!self::startsWith($name, $fix)) {
            return $fix . $name;
        }

        return $name;
    }

    /**
     * Adds a string at the end of another giving string if it does not already ends with it.
     *
     * @param  string  $subject
     * @param  string  $fix
     *
     * @return string
     */
    public static function postfix($subject, $fix)
    {
        if (!self::endsWith($subject, $fix)) {
            return $subject . $fix;
        }

        return $subject;
    }

    /**
     * Replaces any non-english letters with an empty string
     *
     * @param string $str
     * @param bool $keep
     *
     * @return string
     */
    public static function removeNonEnglishChars($str, $keep = '')
    {
        $pattern = sprintf('A-Za-z0-9_%s', $keep);

        return preg_replace("/[^" . $pattern . "]/", '', $str);
    }

    /**
     * Split a string into array using the giving delimiter
     *
     * @param mix (string | array) $delimiter
     * @param bool $string
     *
     * @return array
     */
    public static function split($delimiter, $string)
    {
        if (is_array($delimiter)) {
            $pattern = sprintf('/ (%s) /', implode('|', $delimiter));

            return preg_split($pattern, $string);
        }

        return explode($delimiter, $string);
    }

}
