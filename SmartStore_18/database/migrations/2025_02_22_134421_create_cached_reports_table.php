<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cached_reports', function (Blueprint $table) {
            $table->id('report_id');
            $table->string('report_type'); // e.g., "Daily", "15-Day", "Monthly"
            $table->date('report_date'); // End date of the reporting period
            $table->decimal('total_sales', 10, 2)->default(0);
            $table->decimal('total_expenses', 10, 2)->default(0);
            $table->decimal('profit', 10, 2)->default(0);
            $table->timestamp('generated_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cached_reports');
    }
};
