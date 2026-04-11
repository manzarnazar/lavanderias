<?php

use App\Models\Order;
use App\Models\Store;
use App\Models\Transaction;
use App\Models\Customer;
use App\Models\Wallet;
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
        Schema::create((new Transaction())->getTable(), function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Store::class)->nullable()->constrained();
            // $table->foreignIdFor(Customer::class)->constrained();
            $table->foreignIdFor(Order::class)->constrained();
            $table->boolean('payment_status')->default(false);
            $table->float('amount')->default(0);
            $table->string('payment_method')->default('cash');
            $table->string('transaction_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists((new Transaction())->getTable());
    }
};
