<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_models', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('year')->nullable();
            $table->string('dir')->nullable();
            $table->string('path');
            $table->string('buu')->nullable();
            $table->boolean('matched')->default(false);
            $table->decimal('line_goods_value_amount', 10, 2)->default(0);
            $table->decimal('line_duty_amount', 10, 2)->default(0);
            $table->decimal('line_mpf_amount', 10, 2)->default(0);
            $table->decimal('line_hmf_amount', 10, 2)->default(0);
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('test_models');
    }
}
