<?php

namespace App\Services;

class CountryNormalizer
{
    public static function normalize(?string $country): ?string
    {
        if (!$country) {
            return null;
        }

        $country = preg_replace('/\s+/', ' ', strtolower(trim($country)));

        foreach (config('countryaliases') as $code => $aliases) {
            foreach ($aliases as $alias) {
                if ($country === strtolower($alias)) {
                    return $code;
                }
            }
        }

        return null;
    }
}
