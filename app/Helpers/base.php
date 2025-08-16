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

if (!function_exists('encryptWithKey')) {
    function encryptWithKey($data, $key = 'IniKey123!@#')
    {
        $method = 'AES-256-CBC';
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method));
        $encrypted = openssl_encrypt($data, $method, $key, 0, $iv);
        return base64_encode($encrypted . '::' . $iv);
    }
}

if (!function_exists('decryptWithKey')) {
    function decryptWithKey($data, $key = 'IniKey123!@#')
    {
        if (!$data) {
            return null;
        }

        $method = 'AES-256-CBC';
        list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
        return openssl_decrypt($encrypted_data, $method, $key, 0, $iv);
    }
}
