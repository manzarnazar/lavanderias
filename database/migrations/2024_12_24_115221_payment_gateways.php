<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Media;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('title');
            $table->foreignIdFor(Media::class)->nullable()->constrained()->nullOnDelete();
            $table->string('mode')->default('test')->comment('test or live');
            $table->string('alias')->nullable()->comment('controller namespace');
            $table->json('config')->nullable();
            $table->boolean('is_active')->default(false);
            $table->unsignedBigInteger('store_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_gateways');
    }
};
