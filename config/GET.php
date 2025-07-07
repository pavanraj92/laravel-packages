<?php

if (!function_exists('storage_path')) {
    // Optionally define storage_path or just return an empty array
    return [];
}

if (!class_exists('\Symfony\Component\Yaml\Yaml')) {
    // Yaml package not installed
    return [];
}

use Symfony\Component\Yaml\Yaml;

$settingsFile = storage_path('config') . "/settings.yml";
if (file_exists($settingsFile)) {
    $settings = Yaml::parse(file_get_contents($settingsFile));
    if (!empty($settings)) {
        return $settings;
    }
}

return [];
