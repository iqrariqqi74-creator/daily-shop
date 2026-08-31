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

            // ==========================================
            // DROP OLD COLUMNS
            // ==========================================

            $table->dropColumn([
                'customer_id',
                'vendor_id',
                'order_number',
                'order_start_date',
                'order_end_date',
                'service_type',
                'amount',
                'promo_code',
                'status',
                'payment_method',
                'created_by',
                'updated_by',
                'deleted_at',
                'created_at',
                'updated_at',
            ]);

            // ==========================================
            // ADD NEW COLUMNS
            // ==========================================

            $table->unsignedInteger('customers_id')->after('id');

            $table->string('name')->after('customers_id');

            $table->string('email')->after('name');

            $table->string('mobile', 50)->after('email');

            $table->string('address', 500)->after('mobile');

            $table->string('city')->after('address');

            $table->string('state')->after('city');

            $table->string('pin_code', 25)->after('state');

            $table->string('coupon_code', 50)
                ->nullable()
                ->after('pin_code');

            $table->integer('coupon_value')
                ->nullable()
                ->after('coupon_code');

            $table->integer('order_status')
                ->default(1)
                ->after('coupon_value');

            $table->enum('payment_type', ['COD', 'Gateway'])
                ->default('COD')
                ->after('order_status');

            $table->string('payment_status', 50)
                ->default('Pending')
                ->after('payment_type');

            $table->string('payment_id', 50)
                ->nullable()
                ->after('payment_status');

            $table->string('txn_id', 100)
                ->nullable()
                ->after('payment_id');

            $table->integer('total_amount')
                ->default(0)
                ->after('txn_id');

            $table->text('track_details')
                ->nullable()
                ->after('total_amount');

            $table->timestamp('added_on')
                ->useCurrent()
                ->after('track_details');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            // ==========================================
            // DROP NEW COLUMNS
            // ==========================================

            $table->dropColumn([
                'customers_id',
                'name',
                'email',
                'mobile',
                'address',
                'city',
                'state',
                'pincode',
                'coupon_code',
                'coupon_value',
                'order_status',
                'payment_type',
                'payment_status',
                'payment_id',
                'txn_id',
                'total_amt',
                'track_details',
                'added_on',
            ]);

            // ==========================================
            // RESTORE OLD COLUMNS
            // ==========================================

            $table->unsignedInteger('customer_id');

            $table->unsignedInteger('vendor_id');

            $table->string('order_number')->index();

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
};