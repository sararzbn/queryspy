<?php

namespace QuerySpy\Http\Controllers;

use Illuminate\Routing\Controller;

class DashboardController extends Controller
{
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
            ];
        }

        return view('queryspy::dashboard', ['entries' => $entries]);
    }
}
