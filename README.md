# 🕵️ QuerySpy – Laravel Slow Query Profiler

**QuerySpy** is a developer-friendly profiler for Laravel that captures slow SQL queries and suggests optimizations.

## 🚀 Features

- Logs all slow database queries
- Clean dashboard with filters and search
- Smart suggestions (e.g. avoid SELECT *, use WHERE, add LIMIT)
- CLI commands to export or clear query logs
- Works out of the box with zero config
- 100% test coverage

## 📦 Installation

1. Add the local path to your main `composer.json`:

```
"repositories": [
    {
        "type": "path",
        "url": "./packages/QuerySpy"
    }
]
```

2. Require the package:

```
composer require sararzbn/queryspy:dev-main
```

3. Make sure autoload is set (optional if not already):

```
"autoload": {
    "psr-4": {
        "QuerySpy\\": "packages/QuerySpy/src/"
    }
}
```

4. Dump autoload:

```
composer dump-autoload
```

## ⚙️ Configuration (optional)

```
php artisan vendor:publish --tag=queryspy-config
```

(Only needed if config publishing is supported)

## 🖥️ Usage

### Dashboard

Open in browser:

```
http://localhost:8000/queryspy
```

### Trigger a slow query manually (for testing)

```
\DB::table('users')->whereRaw('pg_sleep(1)')->get();
```

> `pg_sleep(1)` causes a delay on PostgreSQL.

## 🛠️ CLI Commands

Export queries:

```
php artisan queryspy:export --format=csv
php artisan queryspy:export --format=json
```

Clear the log file:

```
php artisan queryspy:clear
```

## ✅ Test Suite

Run:

```
php artisan test --testsuite=QuerySpy
```

Covers:
- Model saving
- Suggestion engine
- Dashboard route
- Export/clear commands

## 🧩 Roadmap

- [ ] Live dashboard updates (AJAX polling)
- [ ] Tag queries with request/user context
- [ ] JOIN performance analyzer

## 👩‍💻 Author

Created with ❤️ by [Sara Rouzbahani](https://github.com/sararzbn)

## 📄 License

MIT License
