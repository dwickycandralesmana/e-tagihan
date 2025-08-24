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
        $combined = $encrypted . '::' . $iv;
        // Use URL-safe base64 encoding (replaces + with -, / with _, removes padding =)
        return rtrim(strtr(base64_encode($combined), '+/', '-_'), '=');
    }
}

if (!function_exists('decryptWithKey')) {
    function decryptWithKey($data, $key = 'IniKey123!@#')
    {
        if (!$data) {
            return null;
        }

        // Convert URL-safe base64 back to regular base64
        $data = str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT);

        $method = 'AES-256-CBC';
        list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
        return openssl_decrypt($encrypted_data, $method, $key, 0, $iv);
    }
}

if (!function_exists('getDefaultTA')) {
    function getDefaultTA()
    {
        $date = date('Y-m-d');

        if (date('m', strtotime($date)) < 7) {
            return date('Y', strtotime($date)) - 1;
        }

        return date('Y', strtotime($date));
    }
}

function terbilang($x)
{
    $angka = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];

    if ($x < 12)
        return $angka[$x] . " ";
    elseif ($x < 20)
        return terbilang($x - 10) . "Belas ";
    elseif ($x < 100)
        return terbilang($x / 10) . "Puluh " . terbilang($x % 10);
    elseif ($x < 200)
        return "Seratus " . terbilang($x - 100);
    elseif ($x < 1000)
        return terbilang($x / 100) . "Ratus " . terbilang($x % 100);
    elseif ($x < 2000)
        return "Seribu " . terbilang($x - 1000);
    elseif ($x < 1000000)
        return terbilang($x / 1000) . "Ribu " . terbilang($x % 1000);
    elseif ($x < 1000000000)
        return terbilang($x / 1000000) . "Juta " . terbilang($x % 1000000);
}

function terbilangRupiah($x)
{
    if ($x == 0) {
        return "Nol Rupiah";
    }

    return terbilang($x) . " Rupiah";
}
