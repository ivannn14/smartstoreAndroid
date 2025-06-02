<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id(); // Default primary key
            $table->string('title');
            $table->decimal('amount', 10, 2);
            $table->string('category')->nullable();
            $table->date('expense_date');
            $table->text('description')->nullable();
            $table->string('receipt_image')->nullable();
            $table->foreignId('user_id')->constrained(); // Keeping foreign key for users
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
