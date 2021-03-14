<?php
/**
 * API REST Functions
 *
 * Functions for REST specific things.
 */

/**
 * Serialize data, if needed.
 *
 * @param string|array|object $data Data that might be serialized.
 * @return mixed
 */
function maybe_serialize($data)
{
    if (is_array($data) || is_object($data))
        return serialize($data);

    return $data;
}

/**
 * Unserialize value only if it was serialized.
 *
 * @param string $original Maybe unserialized original, if is needed.
 * @return mixed Unserialized data can be any type.
 */
function maybe_unserialize($original)
{
    if (is_serialized($original))
        return @unserialize($original);

    return $original;
}

/**
 * Check value to find if it was serialized.
 *
 * If $data is not an string, then returned value will always be false.
 * Serialized data is always a string.
 *
 * @param string $data Value to check to see if was serialized.
 * @param bool $strict Optional. Whether to be strict about the end of the string. Default true.
 * @return bool False if not serialized and true if it was.
 */
function is_serialized($data, $strict = true)
{
    // if it isn't a string, it isn't serialized.
    if (!is_string($data)) {
        return false;
    }
    $data = trim($data);
    if ('N;' == $data) {
        return true;
    }
    if (strlen($data) < 4) {
        return false;
    }
    if (':' !== $data[1]) {
        return false;
    }
    if ($strict) {
        $lastc = substr($data, -1);
        if (';' !== $lastc && '}' !== $lastc) {
            return false;
        }
    } else {
        $semicolon = strpos($data, ';');
        $brace = strpos($data, '}');
        // Either ; or } must exist.
        if (false === $semicolon && false === $brace)
            return false;
        // But neither must be in the first X characters.
        if (false !== $semicolon && $semicolon < 3)
            return false;
        if (false !== $brace && $brace < 4)
            return false;
    }
    $token = $data[0];
    switch ($token) {
        case 's' :
            if ($strict) {
                if ('"' !== substr($data, -2, 1)) {
                    return false;
                }
            } elseif (false === strpos($data, '"')) {
                return false;
            }
            break;

        // or else fall through
        case 'a' :
        case 'O' :
            return (bool)preg_match("/^{$token}:[0-9]+:/s", $data);
            break;

        case 'b' :
        case 'i' :
        case 'd' :
            $end = $strict ? '$' : '';
            return (bool)preg_match("/^{$token}:[0-9.E-]+;$end/", $data);
            break;

    }
    return false;
}

/**
 * Retrieves the option from site settings.
 * @param $name
 * @return mixed
 */
function api_get_option($name)
{
    return Cache::tags(['options'])->rememberForever($name, function ($name) {
        return Option::get($name);
    });
}

/**
 * Retrieves the timezone from site settings as a string.
 *
 * Uses the `timezone_string` option to get a proper timezone if available,
 * otherwise falls back to an offset.
 *
 * @return string PHP timezone string or a ±HH:MM offset.
 */
function api_get_timezone_string()
{
    $timezone_string = api_get_option('timezone_string');

    if ($timezone_string) {
        return $timezone_string;
    }
    $offset = (float)api_get_option('gmt_offset');

    $hours = (int)$offset;
    $minutes = ($offset - $hours);

    $sign = ($offset < 0) ? '-' : '+';
    $abs_hour = abs($hours);
    $abs_mins = abs($minutes * 60);
    $tz_offset = sprintf('%s%02d:%02d', $sign, $abs_hour, $abs_mins);

    return $tz_offset;
}

/**
 * Parses and formats a date for ISO8601/RFC3339.
 *
 * @param string|null|ApiDateTime $date Date.
 * @param bool $utc Send false to get local/offset time.
 * @return string|null ISO8601/RFC3339 formatted datetime.
 */
function api_rest_prepare_date_response($date, $utc = true)
{
    if (is_numeric($date)) {
        $date = new ApiDateTime("@$date", new DateTimeZone('UTC'));
        $date->setTimezone(new DateTimeZone(api_get_timezone_string()));
    } elseif (is_string($date)) {
        $date = new ApiDateTime($date, new DateTimeZone('UTC'));
        $date->setTimezone(new DateTimeZone(api_get_timezone_string()));
    }
    if (!is_a($date, 'ApiDateTime')) {
        return null;
    }
    // Get timestamp before changing timezone to UTC.
    return gmdate('Y-m-d\TH:i:s', $utc ? $date->getTimestamp() : $date->getOffsetTimestamp());
}
