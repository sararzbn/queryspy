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

To install the package, simply run the following Composer command:

```
composer require sararzbn/queryspy
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

## 🧩 Roadmap

- [ ] Live dashboard updates (AJAX polling)
- [ ] Tag queries with request/user context
- [ ] JOIN performance analyzer

## 👩‍💻 Author

Created with ❤️ by [Sara Rouzbahani](https://github.com/sararzbn)

## 📄 License

MIT License
