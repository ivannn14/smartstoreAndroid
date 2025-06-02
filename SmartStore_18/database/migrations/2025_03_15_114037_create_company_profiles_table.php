<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('company_profiles', function (Blueprint $table) {
        $table->id();
        $table->string('company_name');
        $table->string('mobile');
        $table->string('email');
        $table->string('phone')->nullable();
        $table->string('gst_number')->nullable();
        $table->string('vat_number')->nullable();
        $table->string('pan_number')->nullable();
        $table->string('website')->nullable();
        $table->text('bank_details')->nullable();
        $table->string('country')->nullable();
        $table->string('state')->nullable();
        $table->string('city');
        $table->string('postcode')->nullable();
        $table->text('address');
        $table->string('logo')->nullable();
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_profiles');
    }
};
