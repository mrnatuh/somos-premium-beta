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
        Schema::create('previews', function (Blueprint $table) {
            $table->id();
            $table->string('cc')->nullable();
            $table->string('week_ref')->nullable();
            $table->string('month_ref')->nullable();
            $table->decimal('invoicing', 10, 2)->nullable()->default(0);
            $table->decimal('events', 10, 2)->nullable()->default(0);
            $table->decimal('mp', 10, 2)->nullable()->default(0);
            $table->decimal('mo', 10, 2)->nullable()->default(0);
            $table->decimal('gd', 10, 2)->nullable()->default(0);
            $table->decimal('rou', 10, 2)->nullable()->default(0);
            $table->decimal('he', 10, 2)->nullable()->default(0);
            $table->decimal('variation', 10, 2)->nullable();
            $table->string('status')->default('em-analise');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('previews');
    }
};
