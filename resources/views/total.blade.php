<div class="row">
    <div class="col-md-3">
        <p style="min-width: 300px">
            已申报货值：{{ bigNumber($total['total_line_goods_value_amount'])->getValue() }}
        </p>

        <p style="min-width: 300px">
            实际申报货值：{{ bigNumber($total['total_line_goods_value_amount2'])->getValue() }}
        </p>

        <p style="min-width: 300px">
            未申报货值：{{ bigNumber($total['total_line_goods_value_amount2'])->subtract($total['total_line_goods_value_amount'])->getValue() }}
        </p>
    </div>

    <div class="col-md-3">
        <p style="min-width: 300px">
            多付Duty：{{ $total['df_total_line_duty_amount'] }}
        </p>

        <p style="min-width: 300px">
            多付MPF：{{ $total['df_total_line_mpf_amount'] }}
        </p>

        <p style="min-width: 300px">
            多付HMF：{{ $total['df_total_line_hmf_amount'] }}
        </p>

        <p style="min-width: 300px">
            合计多付：{{ bigNumber($total['df_total_line_duty_amount'])->add($total['df_total_line_mpf_amount'])->add($total['df_total_line_hmf_amount'])->getValue() }}
        </p>
    </div>


    <div class="col-md-3">
        <p style="min-width: 300px">
            海运费：{{ $total['total_hyf'] }}
        </p>

        <p style="min-width: 300px">
            关税：{{ $total['total_gs'] }}
        </p>

        <p style="min-width: 300px">
            内陆运费：¥ {{ $total['total_nlyf'] }}
        </p>

    </div>

    <div class="col-md-3">
        <p style="min-width: 300px">
            未付海运费：{{ $total['wf_total_hyf'] }}
        </p>

        <p style="min-width: 300px">
            未付关税：{{ $total['wf_total_gs'] }}
        </p>

        <p style="min-width: 300px">
            未付内陆运费：¥ {{ $total['wf_total_nlyf'] }}
        </p>

        <p style="min-width: 300px">
            合计退税金额：{{ bigNumber($total['tsje'])->getValue() }}
        </p>
    </div>

</div>
