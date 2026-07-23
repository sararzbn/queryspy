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

## ⚙️ Configuration

Publish the config file to customize QuerySpy:

```
php artisan vendor:publish --tag=queryspy-config
```

This creates `config/queryspy.php` with the following options:

| Option | Default | Description |
|---|---|---|
| `enabled` | `true` | Master switch. Set `QUERYSPY_ENABLED=false` in `.env` to disable capturing entirely. |
| `environments` | `['local', 'staging']` | Only capture queries in these environments. An empty array `[]` means capture everywhere. |
| `threshold` | `300` | Queries slower than this many milliseconds are recorded. Override with `QUERYSPY_THRESHOLD`. |

You can also configure these via `.env`:

```
QUERYSPY_ENABLED=true
QUERYSPY_THRESHOLD=300
```

> **⚠️ Upgrade note (v1.2):** QuerySpy now only captures queries in the
> environments listed in `environments` (default `local` and `staging`), so it
> no longer writes to your **production** database out of the box. If you relied
> on capturing in another environment, add it to the list or set `environments`
> to `[]`.

## 🗄️ Database Migration

QuerySpy stores slow queries in a dedicated database table. To create it, run:


```
php artisan migrate
```

## 🖥️ Usage

### Dashboard

Open in browser:

```
http://localhost:8000/queryspy
```

### Seeding demo data (for testing)

QuerySpy ships with a seeder that inserts fake slow-query rows into the
`query_spy_entries` table, so you can test the dashboard, export, and analyze
features without having to generate real slow queries first.

Run it with:

```
php artisan db:seed --class="QuerySpy\Database\Seeders\QuerySpySeeder"
```

This inserts 40 sample slow queries. Use `php artisan queryspy:clear` to wipe them again.

> **Note:** The seeder namespace is registered in the package's `composer.json`
> autoload. If you change the package's `composer.json`, run
> `composer update sararzbn/queryspy` so the app picks up the changes.

## 🛠️ CLI Commands

Export queries:

```
php artisan queryspy:export --format=csv
php artisan queryspy:export --format=json
```

Clear all recorded slow queries (empties the database table and the log file):

```
php artisan queryspy:clear
```

Analyze recorded slow queries in the terminal (slowest first):

```
php artisan queryspy:analyze
```

## 🧩 Roadmap

- [ ] Live dashboard updates (AJAX polling)
- [ ] Tag queries with request/user context
- [ ] JOIN performance analyzer

## 👩‍💻 Author

Created with ❤️ by [Sara Rouzbahani](https://github.com/sararzbn)

## 📄 License

MIT License
