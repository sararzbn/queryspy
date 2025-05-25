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

        if (str_contains($normalized, 'select *')) {
            return 'Avoid SELECT *; specify columns';
        }

        if (!str_contains($normalized, 'where')) {
            return 'Consider adding a WHERE clause';
        }

        if (!str_contains($normalized, 'limit')) {
            return 'Consider using LIMIT to reduce result size';
        }

        return '✅ Looks fine';
    }

}
