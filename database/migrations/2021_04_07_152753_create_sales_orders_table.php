<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('customer_id');
            $table->string('no')->unique()->comment('销售订单编号');
            $table->decimal('amount',10, 2)->comment('总金额');
            $table->date('order_at')->comment('下单时间');
            $table->string('customer_po')->nullable()->comment('客户PO号');
            $table->integer('vendors_count')->default(0)->comment('对应的供应商数量');
            $table->string('vendors')->nullable()->comment('对应的供应商');
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
        Schema::dropIfExists('sales_orders');
    }
}
