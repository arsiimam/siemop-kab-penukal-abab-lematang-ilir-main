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
        Schema::table('activity_reports', function (Blueprint $table) {
            $table->string('target_fisik', 10)->after('target_kinerja')->nullable();
            $table->string('target_keuangan', 10)->after('target_fisik')->nullable();
            $table->string('contract_number')->after('contract_price')->nullable();
            $table->date('contract_date')->after('contract_number')->nullable();
            $table->string('contract_duration', 50)->after('contract_date')->nullable();
            $table->string('target_progres', 10)->after('contract_duration')->nullable();
            $table->string('realisasi_progres', 10)->after('target_progres')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_reports', function (Blueprint $table) {
            //
        });
    }
};
