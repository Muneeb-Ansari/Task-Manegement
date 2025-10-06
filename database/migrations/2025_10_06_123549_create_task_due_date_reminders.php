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
        Schema::table('task_due_date_reminders', function (Blueprint $table) {
            //
            $table->string('reminder_type')->nullable(); // 24h, 6h, 1h, 30m, due
            $table->timestamp('scheduled_for')->nullable();
            $table->timestamp('due_snapshot')->nullable(); // job run-time guard
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_due_date_reminders', function (Blueprint $table) {
            //
        });
    }
};
