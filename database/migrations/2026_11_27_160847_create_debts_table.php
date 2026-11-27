<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('debts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('creditor')->nullable();
            $table->decimal('original_amount', 12, 2);
            $table->decimal('balance', 12, 2);
            $table->decimal('interest_rate', 5, 2)->default(0);
            $table->decimal('minimum_payment', 12, 2)->default(0);
            $table->unsignedTinyInteger('due_day')->nullable();
            $table->string('status')->default('active')->index();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('debts');
    }
};
