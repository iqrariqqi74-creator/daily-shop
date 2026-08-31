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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('customer_id');
            $table->unsignedInteger('vendor_id');
            $table->string('order_number')->unique();
            $table->date('order_start_date')->nullable();
            $table->date('order_end_date')->nullable();
            $table->string('service_type')->nullable();
            $table->string('amount')->nullable();
            $table->string('promo_code')->nullable();
            $table->string('status')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};