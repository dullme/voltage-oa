<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id')->unsigned()->comment('工厂PO');
            $table->string('invoice_no')->unique()->comment('发票号码');
            $table->date('billing_time')->comment('开票时间');
            $table->string('invoice_image')->comment('发票凭证');
            $table->json('file')->nullable()->comment('其他凭证');
            $table->integer('serial')->comment('序号');
            $table->string('title')->nullable()->comment('标题');
            $table->string('comment')->nullable()->comment('备注');
            $table->decimal('amount', 10, 2)->comment('发票总金额');
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
        Schema::dropIfExists('invoices');
    }
}
