<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receives', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->comment('付款人');
            $table->string('bank_receipt')->unique()->comment('银行水单');
            $table->decimal('amount', 10, 2)->comment('金额');
            $table->string('currency')->default('USD')->comment('币种');
            $table->date('receive_payment_at')->comment('收款时间');
            $table->string('remark')->nullable()->comment('备注');
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
        Schema::dropIfExists('receives');
    }
}
