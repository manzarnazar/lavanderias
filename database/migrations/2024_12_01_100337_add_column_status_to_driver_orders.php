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
        Schema::table('driver_orders', function (Blueprint $table) {
            $table->string('status')->default('To-Pickup')->after('is_approve');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('driver_orders', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
