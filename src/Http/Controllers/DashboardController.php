<?php

namespace QuerySpy\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Routing\Controller;

class DashboardController extends Controller
{

    /**
     * @return Factory|View|Application|\Illuminate\View\View|object
     */
    public function index()
    {
        $logPath = storage_path('logs/queryspy.log');

        if (!file_exists($logPath)) {
            return view('queryspy::dashboard', ['entries' => []]);
        }

        $lines = file($logPath);
        $entries = [];

        foreach ($lines as $line) {
            if (!str_contains($line, 'Slow query detected')) continue;

            $jsonStart = strpos($line, '{');
            if ($jsonStart === false) continue;

            $json = substr($line, $jsonStart);
            $data = json_decode($json, true);
            if (!$data) continue;

            $entries[] = [
                'sql' => $data['sql'],
                'time' => round($data['time_ms'], 2),
                'source' => $data['source']['file'] ?? 'unknown',
                'line' => $data['source']['line'] ?? null,
                'suggestion' => $this->getSuggestionForQuery($data['sql']),
            ];

        }

        return view('queryspy::dashboard', ['entries' => $entries]);
    }

    /**
     * @param string $sql
     * @return string
     */
    function getSuggestionForQuery(string $sql): string
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
