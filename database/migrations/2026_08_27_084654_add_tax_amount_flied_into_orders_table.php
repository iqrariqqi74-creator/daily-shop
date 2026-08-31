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
        Schema::table('orders', function (Blueprint $table) {
            // change name txn_id to tax_id
            $table->renameColumn('txn_id', 'tax_id');
            $table->decimal('tax_amount', 10, 2)->default(0.00)->after('tax_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->renameColumn('tax_id', 'txn_id');
            $table->dropColumn('tax_amount');
        });
    }
};
