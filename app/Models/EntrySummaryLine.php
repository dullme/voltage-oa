<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntrySummaryLine extends Model
{

    use HasFactory;

    protected $fillable = [
        'year',
        'dir',
        'path',
        'buu',
        'matched',
        'entry_summary_number',
        'entry_type_code',
        'entry_summary_line_number',
        'review_team_number',
        'country_of_origin_code',
        'country_of_export_code',
        'manufacturer_id',
        'manufacturer_name',
        'foreign_exporter_id',
        'foreign_exporter_name',
        'line_spi_code',
        'line_spi',
        'reconciliation_fta_status',
        'reconciliation_other_status',
        'line_goods_value_amount',
        'line_duty_amount',
        'line_mpf_amount',
        'line_hmf_amount',
        'line_goods_value_amount2',
        'line_duty_amount2',
        'line_mpf_amount2',
        'line_hmf_amount2',
        'check',
        'b_l',
        'kcsj',
        'yjfksj',
        'sjfksj',
        'hyf',
        'gs',
        'nlyf',
        'sfzf_hyf',
        'sfzf_gs',
        'sfzf_nlyf',
        'sfxyts',
        'source',
    ];
}
