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
