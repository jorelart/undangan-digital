<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rsvps', function (Blueprint $table): void {
            $table->text('owner_reply')->nullable()->after('message');
        });
    }

    public function down(): void
    {
        Schema::table('rsvps', function (Blueprint $table): void {
            $table->dropColumn('owner_reply');
        });
    }
};
