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
        Schema::table('order_details', function (Blueprint $table) {

        $table->unsignedBigInteger('product_attribute_id');
        $table->unsignedBigInteger('order_id');

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
        $table->dropColumn('product__attribute_id');
            $table->unsignedBigInteger('product__attribute_id');
        });
    
    }
};