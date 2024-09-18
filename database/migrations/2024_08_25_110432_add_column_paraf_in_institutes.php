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
        Schema::table('institutes', function (Blueprint $table) {
            $table->string('paraf_image')->nullable()->after('contact_pic');
            $table->string('head_of_institute')->nullable()->after('paraf_image');
            $table->string('position')->nullable()->after('head_of_institute');
            $table->string('nip')->nullable()->after('position');
            $table->integer('signature_status')->default(0)->after('nip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('institutes', function (Blueprint $table) {
            //
        });
    }
};
