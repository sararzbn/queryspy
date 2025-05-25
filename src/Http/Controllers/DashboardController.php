<?php

namespace QuerySpy\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Routing\Controller;
use QuerySpy\Models\QuerySpyEntry;

class DashboardController extends Controller
{
    /**
     * @return Factory|View|Application|\Illuminate\View\View|object
     */
    public function index()
    {
        $search = request('search');
        $minTime = request('min_time');
        $sourceFilter = request('source');

        $query = QuerySpyEntry::query()->orderByDesc('created_at');

        if ($search) {
            $query->where('sql', 'ilike', "%$search%");
        }

        if ($minTime) {
            $query->where('time_ms', '>=', (float)$minTime);
        }

        if ($sourceFilter) {
            $query->where('source_file', 'ilike', "%$sourceFilter%");
        }

        $entries = $query->get()->map(function ($entry) {
            return [
                'sql' => $entry->sql,
                'time' => round($entry->time_ms, 2),
                'source' => $entry->source_file ?? 'unknown',
                'line' => $entry->source_line,
                'suggestion' => $this->getSuggestionForQuery($entry->sql),
            ];
        });

        return view('queryspy::dashboard', [
            'entries' => $entries,
            'search' => $search,
            'minTime' => $minTime,
            'sourceFilter' => $sourceFilter,
        ]);
    }

    /**
     * @param string $sql
     * @return string
     */
    protected function getSuggestionForQuery(string $sql): string
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
