<?php

namespace QuerySpy\Database\Seeders;

use Illuminate\Database\Seeder;
use QuerySpy\Models\QuerySpyEntry;

class QuerySpySeeder extends Seeder
{
    /**
     * Seed the query_spy_entries table with fake "slow query" rows so the
     * QuerySpy dashboard / export / analyze commands have data to work with
     * during local development.
     */
    public function run(): void
    {
        $queries = [
            "SELECT * FROM users ORDER BY created_at DESC",
            "SELECT * FROM orders JOIN users ON users.id = orders.user_id JOIN products ON products.id = orders.product_id",
            "SELECT * FROM posts WHERE title LIKE '%laravel%' ORDER BY views DESC",
            "SELECT COUNT(*) FROM logs WHERE created_at BETWEEN '2025-01-01' AND '2025-12-31'",
            "SELECT * FROM invoices JOIN customers ON customers.id = invoices.customer_id WHERE invoices.paid = 0",
            "UPDATE products SET stock = stock - 1 WHERE id IN (SELECT product_id FROM order_items)",
            "SELECT * FROM comments JOIN posts ON posts.id = comments.post_id JOIN users ON users.id = comments.user_id ORDER BY comments.created_at",
        ];

        $sources = [
            ['file' => 'routes/web.php', 'line' => 12],
            ['file' => 'app/Http/Controllers/OrderController.php', 'line' => 45],
            ['file' => 'app/Http/Controllers/ReportController.php', 'line' => 88],
            ['file' => 'app/Services/InvoiceService.php', 'line' => 130],
        ];

        $count = 40;

        for ($i = 0; $i < $count; $i++) {
            $source = $sources[array_rand($sources)];

            QuerySpyEntry::create([
                'sql'         => $queries[array_rand($queries)],
                'bindings'    => [],
                // Random slow time between 300ms and 5000ms (above the 300ms threshold).
                'time_ms'     => round(mt_rand(300, 5000) + mt_rand(0, 99) / 100, 2),
                'source_file' => $source['file'],
                'source_line' => $source['line'],
            ]);
        }

        $this->command?->info("Seeded {$count} fake slow queries into query_spy_entries.");
    }
}
