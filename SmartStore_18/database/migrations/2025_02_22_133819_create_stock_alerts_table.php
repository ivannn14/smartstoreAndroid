<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stock_alerts', function (Blueprint $table) {
            $table->id(); // Default primary key
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('threshold');
            $table->dateTime('alert_date')->nullable();
            $table->string('message')->nullable();
            $table->boolean('acknowledged')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_alerts');
    }
};
