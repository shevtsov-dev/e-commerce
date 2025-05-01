<?php

declare(strict_types=1);

if (!function_exists('safe_trans')) {
    /**
     * Safe translation: force string even if translation returns array.
     *
     * @param string               $key
     * @param array<string, mixed> $replace
     * @param string|null          $locale
     *
     * @return string
     */
    function safe_trans(string $key, array $replace = [], ?string $locale = null): string
    {
        $translated = __($key, $replace, $locale);

        return is_array($translated) ? '' : (string) $translated;
    }
}
