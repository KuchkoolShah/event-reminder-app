<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->boolean('reminder_sent')->default(false)->after('event_time');
            // Optimised index for the scheduler query
            $table->index(['event_time', 'reminder_sent']);
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('reminder_sent');
            $table->dropIndex(['event_time', 'reminder_sent']);
        });
    }
};
