<?php

namespace QuerySpy\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Routing\Controller;
use QuerySpy\Models\QuerySpyEntry;
use QuerySpy\Support\helpers;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    /**
     * @return Factory|View|Application|\Illuminate\View\View|object
     */
    public function index()
    {
        if (!Schema::hasTable('query_spy_entries')) {
            return view('queryspy::dashboard-not-ready');
        }

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
                'suggestion' => helpers::getSuggestionForQuery($entry->sql),
            ];
        });

        return view('queryspy::dashboard', [
            'entries' => $entries,
            'search' => $search,
            'minTime' => $minTime,
            'sourceFilter' => $sourceFilter,
        ]);
    }

}
