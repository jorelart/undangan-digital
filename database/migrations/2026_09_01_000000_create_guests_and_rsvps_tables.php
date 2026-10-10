<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guests', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 120);
            $table->char('token_hash', 64)->unique();
            $table->unsignedTinyInteger('max_guests')->default(1);
            $table->timestamps();
        });

        Schema::create('rsvps', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('guest_id')->unique()->constrained()->cascadeOnDelete();
            $table->enum('presence', ['hadir', 'tidak']);
            $table->unsignedTinyInteger('guest_count')->default(0);
            $table->text('message');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rsvps');
        Schema::dropIfExists('guests');
    }
};
