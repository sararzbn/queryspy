<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::create('query_spy_entries', function (Blueprint $table) {
            $table->id();
            $table->text('sql');
            $table->json('bindings')->nullable();
            $table->float('time_ms');
            $table->string('source_file')->nullable();
            $table->integer('source_line')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('query_spy_entries');
    }
};
