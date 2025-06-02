<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('short_name');
            $table->unsignedBigInteger('base_unit')->nullable();
            $table->string('operator')->nullable();
            $table->decimal('operation_value', 10, 2)->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->foreign('base_unit')->references('id')->on('units')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('units');
    }
};