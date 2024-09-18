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
            $table->string('type', 20)->after('title')->nullable();
            $table->string('sumber_dana')->after('pagu_indikatif')->nullable();
            $table->string('progress_pekerjaan', 20)->after('sumber_dana')->nullable();
            $table->text('documentation')->after('realization')->nullable();
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
