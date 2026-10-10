<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('guests', 'invitation_token')) {
            Schema::table('guests', function (Blueprint $table): void {
                $table->string('invitation_token', 64)->nullable()->unique()->after('phone');
            });
        }

        DB::table('guests')
            ->whereNull('invitation_token')
            ->orderBy('id')
            ->get(['id'])
            ->each(function (object $guest): void {
                DB::table('guests')
                    ->where('id', $guest->id)
                    ->update(['invitation_token' => Str::random(64)]);
            });
    }

    public function down(): void
    {
        if (Schema::hasColumn('guests', 'invitation_token')) {
            Schema::table('guests', function (Blueprint $table): void {
                $table->dropUnique(['invitation_token']);
                $table->dropColumn('invitation_token');
            });
        }
    }
};
