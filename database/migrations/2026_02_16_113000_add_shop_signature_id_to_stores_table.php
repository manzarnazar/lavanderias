<?php

use App\Models\Media;
use App\Models\Store;
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
        Schema::table((new Store())->getTable(), function (Blueprint $table) {
            $table->foreignId('shop_signature_id')
                ->nullable()
                ->after('banner_id')
                ->constrained('media')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
           $table->dropConstrainedForeignId('shop_signature_id');
        });
    }
};
