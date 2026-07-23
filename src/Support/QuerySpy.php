<?php

namespace QuerySpy\Support;

class QuerySpy
{
    /**
     * Determine whether QuerySpy should currently be capturing queries,
     * based on the master switch and the allowed environments.
     */
    public static function isEnabled(?string $environment = null): bool
    {
        if (!config('queryspy.enabled', true)) {
            return false;
        }

        $allowed = config('queryspy.environments', []);

        // An empty list means "capture in every environment".
        if (empty($allowed)) {
            return true;
        }

        $environment = $environment ?? app()->environment();

        return in_array($environment, $allowed, true);
    }
}
