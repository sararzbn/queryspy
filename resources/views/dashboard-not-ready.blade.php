@extends('queryspy::layout')

@section('content')
    <p style="padding: 1rem; background: #fff3cd; color: #856404; border: 1px solid #ffeeba; border-radius: 5px;">
        ⚠️ QuerySpy is not initialized. Please run the following:
    </p>
    <pre style="background: #f5f5f5; padding: 1rem; border-radius: 4px;">
        php artisan migrate
    </pre>
@endsection
