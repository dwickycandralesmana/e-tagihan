<?php

if (!function_exists('getSetting')) {
    function getSetting($key, $default = null)
    {
        $setting = \App\Models\Setting::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }
}

if (!function_exists('formatRp')) {
    function formatRp($value)
    {
        if (!is_numeric($value)) {
            return $value;
        }

        return 'Rp ' . number_format($value, 0, ',', '.');
    }
}
