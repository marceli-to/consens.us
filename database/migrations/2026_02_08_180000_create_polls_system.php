<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('polls', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 8)->unique();
            $table->string('edit_token', 32)->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('password')->nullable();
            $table->enum('type', ['freetext', 'date'])->default('freetext');
            $table->boolean('is_closed')->default(false);
            $table->timestamps();
        });

        Schema::create('poll_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poll_id')->constrained()->cascadeOnDelete();
            $table->string('label');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Drop old votes table and recreate
        Schema::dropIfExists('votes');
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poll_id')->constrained()->cascadeOnDelete();
            $table->foreignId('poll_option_id')->constrained()->cascadeOnDelete();
            $table->string('voter_name');
            $table->timestamps();

            $table->unique(['voter_name', 'poll_id', 'poll_option_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('votes');
        Schema::dropIfExists('poll_options');
        Schema::dropIfExists('polls');
    }
};
