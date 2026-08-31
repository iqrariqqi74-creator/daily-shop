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
        Schema::table('customers',  function (Blueprint $table) {
            $table->string('password')->after('email');
            $table->string('city')->after('address');
            $table->string('state')->after('city');
            $table->string('zip')->after('state');
            $table->string('company')->after('zip');
            $table->string('gstin')->after('company')->nullable();
            $table->unsignedInteger('is_valid')->after('gstin')->nullable();
            $table->unsignedInteger('is_forgot_password')->after('is_valid')->nullable();
            $table->unsignedInteger('rand_id')->after('is_forgot_password')->nullable();

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
