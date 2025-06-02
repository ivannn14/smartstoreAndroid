<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // ✅ Default Laravel primary key
            $table->string('name')->unique();
            $table->string('email')->unique();
            $table->string('password'); // ✅ Fixed password column
            $table->enum('role', ['Admin', 'Cashier', 'Manager']);
            $table->timestamps();
        });
        
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
