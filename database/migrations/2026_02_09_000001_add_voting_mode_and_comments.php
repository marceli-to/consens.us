<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('polls', function (Blueprint $table) {
            $table->enum('voting_mode', ['checkbox', 'radio', 'yesnomaybe'])->default('checkbox')->after('type');
            $table->boolean('allow_comments')->default(true)->after('voting_mode');
        });

        Schema::table('votes', function (Blueprint $table) {
            $table->enum('value', ['yes', 'no', 'maybe'])->nullable()->after('voter_name');
        });

        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poll_id')->constrained()->cascadeOnDelete();
            $table->string('author_name');
            $table->text('body');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');

        Schema::table('votes', function (Blueprint $table) {
            $table->dropColumn('value');
        });

        Schema::table('polls', function (Blueprint $table) {
            $table->dropColumn(['voting_mode', 'allow_comments']);
        });
    }
};
