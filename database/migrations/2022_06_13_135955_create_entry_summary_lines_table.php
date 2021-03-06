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

            $table->string('b_l')->nullable()->comment('?????????');
            $table->date('kcsj')->nullable()->comment('????????????');
            $table->date('yjfksj')->nullable()->comment('??????????????????');
            $table->date('sjfksj')->nullable()->comment('??????????????????');
            $table->date('entry_date')->nullable()->comment('');
            $table->decimal('hyf', 10, 2)->nullable()->comment('?????????');
            $table->decimal('gs', 10, 2)->nullable()->comment('??????');
            $table->decimal('nlyf', 10, 2)->nullable()->comment('????????????');
            $table->decimal('tsje', 10, 2)->nullable()->comment('????????????');
            $table->boolean('sfzf_hyf')->nullable()->comment('?????????????????????');
            $table->boolean('sfzf_gs')->nullable()->comment('??????????????????');
            $table->boolean('sfzf_nlyf')->nullable()->comment('????????????????????????');
            $table->boolean('sfxyts')->nullable()->comment('??????????????????');
            $table->string('source')->nullable()->comment('????????????');
            $table->string('hy_daili')->nullable()->comment('????????????');
            $table->string('qg_daili')->nullable()->comment('????????????');


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
