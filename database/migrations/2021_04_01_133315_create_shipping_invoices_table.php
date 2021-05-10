<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('project_id');
            $table->string('invoice_no')->unique()->comment('发票号码');
            $table->string('shipping')->comment('货代');
            $table->string('info')->comment('货物信息');
            $table->string('batch')->comment('批次');
            $table->string('invoice_info')->comment('发票信息');
            $table->json('detail')->comment('详细信息');
            $table->string('b_l')->comment('海运提单号');
            $table->decimal('amount', 10, 2);
            $table->date('billing_time')->comment('开票时间');
            $table->date('delivery_time')->comment('发货时间');
            $table->string('invoice_image')->comment('发票凭证');
            $table->json('file')->nullable()->comment('其他凭证');
            $table->string('comment')->nullable()->comment('备注');
            $table->tinyInteger('status')->default(0)->comment('发票状态0:与录入；1:财务已签收');
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
        Schema::dropIfExists('shipping_invoices');
    }
}
