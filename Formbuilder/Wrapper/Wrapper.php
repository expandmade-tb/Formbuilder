<?php

namespace Formbuilder\Wrapper;

use Exception;

class Wrapper {
    private static bool $is_loaded = false;
    private static array $elements_data = [];
    private static array $element_parts_data = [];

    // Store common replacement keys and their default values
    private const PLACEHOLDERS = [
        '[:name]', '[:label]', '[:id]', '[:value]', '[:attributes]', '[:options]',
        '[:row]', '[:col]', '[:min]', '[:max]', '[:step]', '[:class-ovwr]'
    ];
    
    // This allows us to map placeholder values to method arguments dynamically
    private const ARGUMENT_MAP = [
        'name', 'label', 'id', 'value', 'attribute', 'options',
        'row', 'col', 'min', 'max', 'step', '' // empty string for class-ovwr removal
    ];

    public static function factory(string $name = 'bootstrap'): void {
        if (self::$is_loaded === false) {
            // Using realpath to prevent directory traversal and improve security
            $filepath = realpath(__DIR__ . '/' . $name . '.php');

            if ($filepath === false || !file_exists($filepath)) {
                throw new Exception("Formbuilder wrapper file '{$name}.php' not found.");
            }

            $array = require $filepath;
            self::$elements_data = $array["elements"] ?? [];
            self::$element_parts_data = $array["element_parts"] ?? [];
            self::$is_loaded = true;
        }
    }

    /**
     * Generates an HTML element from a template.
     *
     * @param string $key The key of the element in the loaded templates.
     * @param array $data An associative array of replacement values (e.g., ['name' => 'myField', 'label' => 'My Label']).
     * @return string The generated HTML string.
     * @throws Exception If the Wrapper is not initialized.
     */
    public static function elements(string $key, ...$data): string {
        if (self::$is_loaded === false) {
            throw new Exception('Wrapper not initialized. Call Wrapper::factory() first.');
        }

        if (isset(self::$elements_data[$key]) === false) {
            return ''; // Return empty string if key doesn't exist
        }

        $template = self::$elements_data[$key];

        // Handle class-overwrite specifically
        if (isset($data['attribute']) && is_string($data['attribute']) && str_contains($data['attribute'], 'class=')) {            $pattern = "/\[:class-ovwr\]\s?class\s?=\s?([\"'])(.*?)\\1/";
            $template = preg_replace($pattern, $data['attribute'], $template);
            // After replacing, remove the attribute to prevent double insertion if attribute also contains class
            unset($data['attribute']);
        }
        
        // Prepare replacement values, defaulting to empty string if not provided
        $replacements = [];

        foreach (self::ARGUMENT_MAP as $index => $argKey) {
            $placeholder = self::PLACEHOLDERS[$index];
            if ($argKey === '') { // This is for '[:class-ovwr]'
                $replacements[] = '';
            } elseif (isset($data[$argKey])) {
                // Check if it's an array and join it into a string if so
                if (is_array($data[$argKey])) {
                    $replacements[] = implode(', ', $data[$argKey]); // Or choose another separator if needed
                } else {
                    $replacements[] = strval($data[$argKey]); // Convert to string if it's not an array
                }
            } else {
                $replacements[] = ''; // Default to empty string for missing data
            }
        }
        
        return str_replace(self::PLACEHOLDERS, $replacements, $template);
    }

    /**
     * Generates an HTML element part from a template.
     *
     * @param string $key The key of the element part in the loaded templates.
     * @param array $data An associative array of replacement values.
     * @return string The generated HTML string.
     * @throws Exception If the Wrapper is not initialized.
     */
    public static function element_parts(string $key, ...$data): string {
        if (self::$is_loaded === false) {
            throw new Exception('Wrapper not initialized. Call Wrapper::factory() first.');
        }

        if (isset(self::$element_parts_data[$key]) === false) {
            return '';
        }

        $template = self::$element_parts_data[$key];

        // Handle class-overwrite specifically
        if (isset($data['attribute']) && is_string($data['attribute']) && str_contains($data['attribute'], 'class=')) {
            $pattern = "/\[:class-ovwr\]\s?class\s?=\s?([\"'])(.*?)\\1/";
            $template = preg_replace($pattern, $data['attribute'], $template);
            // Ensure [:attributes] placeholder doesn't also add the class again
            unset($data['attribute']);
        }

        $replacements = [];

        foreach (self::ARGUMENT_MAP as $index => $argKey) {
            $placeholder = self::PLACEHOLDERS[$index];
            if ($argKey === '') { // This is for '[:class-ovwr]'
                $replacements[] = '';
            } elseif (isset($data[$argKey])) {
                // Check if it's an array and join it into a string if so
                if (is_array($data[$argKey])) {
                    $replacements[] = implode(', ', $data[$argKey]); // Or choose another separator if needed
                } else {
                    $replacements[] = strval($data[$argKey]); // Convert to string if it's not an array
                }
            } else {
                $replacements[] = ''; // Default to empty string for missing data
            }
        }
        
        return str_replace(self::PLACEHOLDERS, $replacements, $template);
    }

    public static function classes(string $key, string $classes_file = 'classes'): string {
        // This method is fine as is, assuming classes.php is small and frequently accessed.
        // If 'classes.php' is large, you might consider caching its content as well.
        $filepath = realpath(__DIR__ . '/' . $classes_file . '.php');

        if ($filepath === false || !file_exists($filepath)) {
            throw new Exception("Classes file '{$classes_file}.php' not found.");
        }

        $array = require $filepath;
        return $array[$classes_file][$key] ?? ''; // Using $classes_file as the key for the inner array
    }
}