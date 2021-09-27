<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesOrderBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_order_batches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sales_order_id');
            $table->string('no')->comment('DO 编号');
            $table->decimal('amount',10, 2)->comment('发货总金额');
            $table->decimal('shipment_amount',10, 2)->default(0)->comment('已发货总金额');
            $table->boolean('is_shipment')->default(false)->comment('是否已完成发货');
            $table->decimal('received_amount',10, 2)->default(0)->comment('已收款总金额');
            $table->boolean('is_received')->default(false)->comment('是否已完成收款');
            $table->date('delivery_at')->comment('发货时间');
            $table->string('declaration_number')->nullable()->comment('报关单号');
            $table->string('file')->nullable()->comment('盖章的报关单');
            $table->string('comment')->nullable()->comment('备注');
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
        Schema::dropIfExists('sales_order_batches');
    }
}
