<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntrySummaryLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entry_summary_lines', function (Blueprint $table) {
            $table->id();
            $table->string('year')->nullable();
            $table->string('dir')->nullable();
            $table->string('path')->nullable();
            $table->string('buu')->nullable();
            $table->boolean('matched')->default(false);
            $table->string('entry_summary_number')->nullable();
            $table->string('entry_type_code')->nullable();
            $table->string('entry_summary_line_number')->nullable();
            $table->string('review_team_number')->nullable();
            $table->string('country_of_origin_code')->nullable();
            $table->string('country_of_export_code')->nullable();
            $table->string('manufacturer_id')->nullable();
            $table->string('manufacturer_name')->nullable();
            $table->string('foreign_exporter_id')->nullable();
            $table->string('foreign_exporter_name')->nullable();
            $table->string('line_spi_code')->nullable();
            $table->string('line_spi')->nullable();
            $table->string('reconciliation_fta_status')->nullable();
            $table->string('reconciliation_other_status')->nullable();
            $table->decimal('line_goods_value_amount', 10, 2)->nullable();
            $table->decimal('line_duty_amount', 10, 2)->nullable();
            $table->decimal('line_mpf_amount', 10, 2)->nullable();
            $table->decimal('line_hmf_amount', 10, 2)->nullable();
            $table->decimal('line_goods_value_amount2', 10, 2)->nullable();
            $table->decimal('line_duty_amount2', 10, 2)->nullable();
            $table->decimal('line_mpf_amount2', 10, 2)->nullable();
            $table->decimal('line_hmf_amount2', 10, 2)->nullable();
            $table->integer('check')->default(0);

            $table->string('b_l')->nullable()->comment('提单号');
            $table->date('kcsj')->nullable()->comment('开船时间');
            $table->date('yjfksj')->nullable()->comment('预计付款时间');
            $table->date('sjfksj')->nullable()->comment('实际付款时间');
            $table->decimal('hyf', 10, 2)->nullable()->comment('海运费');
            $table->decimal('gs', 10, 2)->nullable()->comment('关税');
            $table->decimal('nlyf', 10, 2)->nullable()->comment('内陆运费');
            $table->boolean('sfzf_hyf')->nullable()->comment('是否支付海运费');
            $table->boolean('sfzf_gs')->nullable()->comment('是否支付关税');
            $table->boolean('sfzf_nlyf')->nullable()->comment('是否支付内陆运费');
            $table->boolean('sfxyts')->nullable()->comment('是否需要退税');
            $table->string('source')->nullable()->comment('数据来源');


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
        Schema::dropIfExists('entry_summary_lines');
    }
}
