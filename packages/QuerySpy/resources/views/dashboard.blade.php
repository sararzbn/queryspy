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
    </style>
</head>
<body>
<h1>🕵️ QuerySpy – Slow Queries</h1>

@if (count($entries))
    <table>
        <thead>
        <tr>
            <th>Time (ms)</th>
            <th>SQL</th>
            <th>Source</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($entries as $entry)
            <tr>
                <td>{{ $entry['time'] }}</td>
                <td><code>{{ $entry['sql'] }}</code></td>
                <td>{{ $entry['source'] }}@if($entry['line']):{{ $entry['line'] }}@endif</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@else
    <p>No slow queries found.</p>
@endif
</body>
</html>
