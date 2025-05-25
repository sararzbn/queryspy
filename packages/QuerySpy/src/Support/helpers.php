<?php

namespace QuerySpy\Support;

class helpers
{
    /**
     * @param string $sql
     * @return string
     */
    public static function getSuggestionForQuery(string $sql): string
    {
        $normalized = strtolower($sql);
        $suggestions = [];

        if (str_contains($normalized, 'select *')) {
            $suggestions[] = 'Avoid SELECT *; specify columns';
        }

        if (!str_contains($normalized, 'where')) {
            $suggestions[] = 'Consider adding a WHERE clause';
        }

        if (!str_contains($normalized, 'limit')) {
            $suggestions[] = 'Consider using LIMIT to reduce result size';
        }

        if (substr_count($normalized, ' join ') >= 2) {
            $suggestions[] = 'Multiple JOINs detected; consider query simplification or indexing foreign keys';
        }

        if (str_contains($normalized, 'order by')) {
            $suggestions[] = 'ORDER BY can slow down large result sets; consider indexing or limiting results';
        }

        if (str_contains($normalized, 'not in')) {
            $suggestions[] = 'NOT IN may be inefficient on large sets; use LEFT JOIN with NULL check if possible';
        }

        if (preg_match('/(select|where)\s+\(.*select.*\)/', $normalized)) {
            $suggestions[] = 'Subqueries can be costly; consider restructuring or joining';
        }

        return $suggestions ? implode(' | ', $suggestions) : '✅ Looks fine';
    }
}
