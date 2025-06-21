@extends('queryspy::layout')

@section('content')
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
@endsection
