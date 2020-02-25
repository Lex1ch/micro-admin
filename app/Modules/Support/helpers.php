<?php

if (!function_exists('dd')) {
    function dd($data = null): string {
        print '<pre>';
        var_dump($data);
        print '</pre>';
        return die();
    }
}

if (!function_exists('class_basename')) {
    function class_basename($class)
    {
        $class = is_object($class) ? get_class($class) : $class;
        return basename(str_replace('\\', '/', $class));
    }
}

if (!function_exists('array_collapse')) {
    function array_collapse(array $array)
    {
        $results = [];
        foreach($array as $key => $item) {
            $results[] = $key;
        }

        return $results;
    }
}