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
        Schema::table('product_attributes', function (Blueprint $table) {
            $table->unsignedInteger('created_by')->nullable()->after('quantity');
            $table->unsignedInteger('updated_by')->nullable()->after('created_by');
            $table->unsignedInteger('deleted_by')->nullable()->after('updated_by');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
