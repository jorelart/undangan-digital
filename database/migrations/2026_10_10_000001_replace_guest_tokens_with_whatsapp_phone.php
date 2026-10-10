<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('guests', 'token_hash')) {
            Schema::table('guests', function (Blueprint $table): void {
                $table->dropUnique(['token_hash']);
                $table->dropColumn('token_hash');
            });
        }

        if (! Schema::hasColumn('guests', 'phone')) {
            Schema::table('guests', function (Blueprint $table): void {
                $table->string('phone', 20)->nullable()->after('name');
            });
        }

        Schema::table('guests', function (Blueprint $table): void {
            $table->string('phone', 20)->nullable()->change();
        });

        DB::table('guests')->where('phone', '')->update(['phone' => null]);

        $phoneIndexExists = collect(Schema::getIndexes('guests'))
            ->contains(fn (array $index): bool => $index['name'] === 'guests_phone_unique');

        if (! $phoneIndexExists) {
            Schema::table('guests', function (Blueprint $table): void {
                $table->unique('phone');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasIndex('guests', ['phone'], 'unique')) {
            Schema::table('guests', function (Blueprint $table): void {
                $table->dropUnique(['phone']);
            });
        }

        if (Schema::hasColumn('guests', 'phone')) {
            Schema::table('guests', function (Blueprint $table): void {
                $table->dropColumn('phone');
            });
        }

        Schema::table('guests', function (Blueprint $table): void {
            $table->char('token_hash', 64)->nullable()->unique()->after('name');
        });
    }
};
