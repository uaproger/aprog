<?php

if (!function_exists('code_location')) {
    function code_location(): string
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1] ?? null;

        if ($trace && isset($trace['file'], $trace['line'])) {
            return $trace['file'] . ':' . $trace['line'];
        }

        return 'unknown:0';
    }
}

if (!function_exists('arr')) {
    function arr($array, $key, $default = null)
    {
        if (is_array($array)) {
            return $array[$key] ?? $default;
        }
        if (is_object($array)) {
            return $array->$key ?? $default;
        }
        return null;
    }
}

if (!function_exists('object')) {
    function object(array $data = []): stdClass
    {
        if (!empty($data)) {
            $obj = new stdClass();
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    if (empty($value)) {
                        $obj->$key = [];
                    } elseif (isset($value[0])) {
                        $obj->$key = array_map(function ($item) {
                            return is_array($item) ? object($item) : $item;
                        }, $value);
                    } else {
                        $obj->$key = object($value);
                    }
                } else {
                    $obj->$key = $value;
                }
            }
            return $obj;
        }
        return new stdClass();
    }
}
