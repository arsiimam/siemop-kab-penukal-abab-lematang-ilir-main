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
        Schema::create('activity_reports', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('activityprogram_id')->index();
            $table->bigInteger('institute_id')->index()->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->unsignedBigInteger('parent_id')->index()->nullable();
            $table->string('title')->nullable();
            $table->string('month', 20)->index()->nullable();
            $table->string('year', 20)->index()->nullable();
            $table->string('pagu_indikatif')->nullable();
            $table->string('target_kinerja')->nullable();
            $table->string('fisik')->nullable();
            $table->string('non_fisik')->nullable();
            $table->integer('realization')->nullable();
            $table->string('percentage', 20)->nullable();
            $table->string('executor')->nullable();
            $table->integer('contract_price')->nullable();
            $table->string('location')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_reports');
    }
};
