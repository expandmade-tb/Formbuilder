<?php

namespace Formbuilder\Wrapper;

class Wrapper {
    private static string $name = '';
    private static array $elements_array = [];
    private static array $element_parts_array = [];

    public static function factory (string $name='bootstrap') {
        if ( !empty(self::$name) )
            return;

        self::$name = $name;
        $array = require($name).'.php';
        self::$elements_array = $array["elements"];
        self::$element_parts_array = $array["element_parts"];
    }

    public static function elements (string $key, $name='', $label='', $id='', $value='', $attribute='', $options='', $row='', $col='') : string {
        self::factory('');
        $result = self::$elements_array[$key];

        if ( strpos($attribute, 'class=') !== false ) {
            $pattern = "/\[:class-ovwr\]\s?class\s?=\s?([\"'])(.*?)\\1/";
            $result = preg_replace($pattern, $attribute,  $result);
        }

        return str_replace(['[:name]','[:label]','[:id]','[:value]','[:attributes]','[:options]','[:row]','[:col]','[:class-ovwr]'],
                             [$name,$label,$id,$value,$attribute,$options,$row,$col,''],$result);
    }

    public static function element_parts (string $key, $name='', $label='', $id='', $value='', $attribute='', $options='', $row='', $col='') : string {
        self::factory('');
        $result = self::$element_parts_array[$key];

        if ( strpos($attribute, 'class=') !== false ) {
            $pattern = "/\[:class-ovwr\]\s?class\s?=\s?([\"'])(.*?)\\1/";
            $result = preg_replace($pattern, $attribute,  $result);
        }

        return str_replace(['[:name]','[:label]','[:id]','[:value]','[:attributes]','[:options]','[:row]','[:col]','[:class-ovwr]'],
                             [$name,$label,$id,$value,$attribute,$options,$row,$col,''],$result);
    }
}  