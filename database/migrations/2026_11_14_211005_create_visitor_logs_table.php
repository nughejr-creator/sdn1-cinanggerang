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
        Schema::create('visitor_logs', function (Blueprint $table) {
            $table->id();
            $table->string('ip_hash', 64)->index();
            $table->date('visited_date')->index();
            $table->unsignedInteger('hits')->default(1);
            $table->string('user_agent', 255)->nullable();
            $table->string('page_url', 255)->nullable();
            $table->timestamp('last_activity_at')->nullable()->index();
            $table->timestamps();

            $table->unique(['ip_hash', 'visited_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitor_logs');
    }
};
