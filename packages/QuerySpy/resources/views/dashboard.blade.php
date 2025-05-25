<!DOCTYPE html>
<html>
<head>
    <title>QuerySpy Dashboard</title>
    <style>
        body { font-family: sans-serif; padding: 2rem; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 0.5rem; border: 1px solid #ccc; text-align: left; }
        th { background: #f8f8f8; }
        tr:hover { background: #f0f0f0; }
        code { font-family: monospace; background: #f4f4f4; padding: 2px 4px; border-radius: 3px; }
        input { padding: 5px; margin-right: 10px; }
        button { padding: 5px 10px; }
    </style>
</head>
<body>
<h1>🕵️ QuerySpy – Slow Queries</h1>

<form method="GET" style="margin-bottom: 1rem;">
    <input type="text" name="search" placeholder="Search SQL..." value="{{ $search ?? '' }}" />
    <input type="text" name="source" placeholder="Filter by source..." value="{{ $sourceFilter ?? '' }}" />
    <input type="number" name="min_time" placeholder="Min time (ms)" value="{{ $minTime ?? '' }}" />
    <button type="submit">Filter</button>
    <a href="{{ url()->current() }}">Reset</a>
</form>

@if (count($entries))
    <table>
        <thead>
        <tr>
            <th>Time (ms)</th>
            <th>SQL</th>
            <th>Source</th>
            <th>Suggestion</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($entries as $entry)
            <tr>
                <td>{{ $entry['time'] }}</td>
                <td><code>{{ $entry['sql'] }}</code></td>
                <td>{{ $entry['source'] }}@if($entry['line']):{{ $entry['line'] }}@endif</td>
                <td>{{ $entry['suggestion'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@else
    <p>No slow queries found.</p>
@endif
</body>
</html>
