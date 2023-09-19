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
        Schema::create('invoicings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('preview_id')->references('id')->on('previews')->onDelete('cascade');
            $table->decimal('total', 10, 2)->nullable();
            $table->longText('option_value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoicings');
    }
};
