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
            $table->string('entry_summary_number');
            $table->string('entry_type_code');
            $table->string('entry_summary_line_number');
            $table->string('review_team_number');
            $table->string('country_of_origin_code');
            $table->string('country_of_export_code');
            $table->string('manufacturer_id');
            $table->string('manufacturer_name');
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
