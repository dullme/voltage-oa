<style>
    .table-bordered>tbody>tr>th,
    .table-bordered>tbody>tr>td{
        border: 1px solid #858585;
    }

</style>
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title"><label class="label label-{{ $invoice->status ? 'success' : 'danger' }}">{{ $invoice->status ? '已签收' : '待签收' }}</label></h3>

        <div class="box-tools">
            <div class="btn-group pull-right" style="margin-right: 5px">
                <a href="{{ url('admin/invoices') }}" class="btn btn-sm btn-default" title="List">
                    <i class="fa fa-list"></i><span class="hidden-xs"> List</span>
                </a>
            </div>
        </div>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="col-md-12" style="padding: 20px 0">
            <div class="col-md-4">
                <p>
                    <span class="text-success" style="font-weight: bold;font-size: 35px"><span style="margin-right: 10px;font-weight:100;color: #333 !important;">No</span>{{ $invoice->invoice_no }}</span>
                </p>
                <p>发票金额：{{ $invoice->amount }}</p>
                <p>开票时间：{{ $invoice->billing_time }}</p>
                <p>签收时间：{{ $invoice->submission_at }}</p>
                <p>标题：{{ $invoice->title }}</p>
                <p>项目编号：{{ $invoice->purchaseOrder->project->no }}</p>
                @if($invoice->comment)
                    <p>备注：{{ $invoice->comment }}</p>
                @endif


                <div class="col-md-12" style="margin-top: 20px;border: 2px solid #02ab5d;padding: 10px;border-radius: 4px;">
                    <div class="col-md-12" style="text-align: center;font-size: 22px;padding-bottom: 20px">关联发票</div>
                    <div class="col-md-12">
                        <table class="table table-hover" style="margin-bottom: unset">
                            <thead>
                            <tr>
                                <th>发票号</th>
                                <th>金额</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($invoices[$invoice->serial] as $item)
                                <tr>
                                    <td>
                                        <a href="{{ asset('/admin/invoices/'.$item->id) }}">{{ $item->invoice_no }}</a>
                                        @if($item->id == $invoice->id)
                                            <i class="text-success fa fa-check"></i>
                                        @endif
                                    </td>
                                    <td>¥ {{ $item->amount }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>


            </div>
            <div class="col-md-5">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th colspan="3" style="text-align: center">发票清单 <a href="{{ url('/admin/print/invoices/'.$invoice->id) }}" target="_blank"><i class="fa fa-print"></i></a></th>
                        </tr>
                        <tr>
                            <td colspan="3">客户PO：{{ $invoice->purchaseOrder->salesOrder->customer_po }}</td>
                        </tr>
                        <tr>
                            <td colspan="3">工厂：{{ $invoice->purchaseOrder->vendor->name }}</td>
                        </tr>
                        <tr>
                            <td colspan="3">项目：{{ $invoice->purchaseOrder->project->name }}</td>
                        </tr>
                        <tr>
                            <td colspan="3">工厂PO：{{ $invoice->purchaseOrder->po }}</td>
                        </tr>
                        <tr>
                            <td colspan="3">工厂PO总额：<span style="font-weight: bold">¥ {{ $invoice->purchaseOrder->amount }}</span></td>
                        </tr>
                        @foreach($invoices as $item)
                            <tr style="{{ $item->where('id', $invoice->id)->count() ? 'background-color: #bbbbbb;' : '' }}">
                                <td>{{ $item->first()->title }}</td>
                                <th>¥ {{ $item->sum('amount') }}</th>
                                <td>共 {{ $item->count() }} 张</td>
                            </tr>
{{--                                <td><span style="cursor: pointer" tabindex="0" data-toggle="popover" data-trigger="focus" data-placement="left" title="发票号 - 金额" data-content="@foreach($item as $fapiao) {{ $fapiao->invoice_no }} @endforeach">共 {{ $item->count() }} 张</span></td>--}}
                        @endforeach
                        <tr>
                            <td colspan="3">剩余发票总额：<span class="{{ $invoice->purchaseOrder->amount - $invoice_total_amount < 0 ? 'text-danger' : '' }}" style="font-weight: bold;">¥ {{ bigNumber($invoice->purchaseOrder->amount)->subtract($invoice_total_amount)->getValue()  }}</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">凭证</h3>
                </div>
                <div class="panel-body">
                    <p><a target="_blank" href="{{ asset('uploads/'.$invoice->invoice_image) }}" class="label label-success">{{ str_replace('files/', '', $invoice->invoice_image) }}</a></p>
                    @if(isset($invoice->file))
                        @foreach($invoice->file as $item)
                            <p><a target="_blank" href="{{ asset('uploads/'.$item) }}" class="label label-info">{{ str_replace('files/', '', $item) }}</a></p>
                        @endforeach
                    @endif
                </div>
            </div>

        </div>

    </div>
</div>
