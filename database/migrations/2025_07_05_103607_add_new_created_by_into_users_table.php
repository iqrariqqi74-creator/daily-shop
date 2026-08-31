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

        Schema::table('users',  function (Blueprint $table) {
            $table->string('phone_number')->nullable()->after('email');
            $table->boolean('status')->nullable()->after('email');
            $table->string('company_name')->nullable()->after('status');
            $table->unsignedInteger('user_id')->after('company_name');
            $table->unsignedInteger('created_by')->nullable()->after('user_id');
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
