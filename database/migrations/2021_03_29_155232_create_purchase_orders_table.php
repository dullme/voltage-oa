<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PurchaseOrdersCreateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('sales_order_id');
            $table->unsignedBigInteger('vendor_id')->comment('供应商');
            $table->string('po')->comment('供应商PO');
            $table->decimal('amount', 10, 2)->comment('PO总金额');
            $table->decimal('received_amount',10, 2)->default(0)->comment('已收货总金额');
            $table->boolean('is_received')->default(false)->comment('是否完成收货');
            $table->decimal('paid_amount',10, 2)->default(0)->comment('已付款总金额');
            $table->boolean('is_paid')->default(false)->comment('是否完成付款');
            $table->date('order_at')->nullable()->comment('下单日期');
            $table->date('double_signed_at')->nullable()->comment('获取双签合同时间');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_orders');
    }
}
