<p style="min-width: 300px">
    代理：{{ $model->line_duty_amount2 }} + {{ $model->line_mpf_amount2 }} + {{ $model->line_hmf_amount2 }} = {{ $amount2 = bigNumber($model->line_duty_amount2)->add($model->line_mpf_amount2)->add($model->line_hmf_amount2)->getValue() }}
</p>
<p style="min-width: 300px">
    海关：{{ $model->line_duty_amount }} + {{ $model->line_mpf_amount }} + {{ $model->line_hmf_amount }} = {{ $amount = bigNumber($model->line_duty_amount)->add($model->line_mpf_amount)->add($model->line_hmf_amount)->getValue() }}
</p>

<p style="min-width: 300px;font-size: 14px;font-weight: bolder;">
    多付：{{ bigNumber($amount2)->subtract($amount)->getValue()  }}
</p>
