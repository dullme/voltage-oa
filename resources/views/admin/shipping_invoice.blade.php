<style>
    .table-bordered>tbody>tr>th,
    .table-bordered>tbody>tr>td{
        border: 1px solid #858585;
    }

</style>
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Detail</h3>

        <div class="box-tools">
            <div class="btn-group pull-right" style="margin-right: 5px">
                <a href="{{ url('admin/shipping-invoices') }}" class="btn btn-sm btn-default" title="List">
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
                <div class="col-md-12">
                    <div class="col-md-12">
                        <p><span class="text-success" style="font-weight: bold;font-size: 35px"><span style="margin-right: 10px;font-weight:100;color: #333 !important;">No</span>{{ $shipping_invoice->invoice_no }}</span></p>
                        <p>发票金额：{{ $shipping_invoice->amount }}</p>
                        <p>开票时间：{{ $shipping_invoice->billing_time }}</p>
                        <p>批次：{{ $shipping_invoice->batch }}</p>
                        <p>项目名称：{{ $shipping_invoice->project->name }}</p>
                        <p>项目编号：{{ $shipping_invoice->project->no }}</p>
                    </div>

                    <div class="col-md-12" style="margin-top: 20px;border: 2px solid #02ab5d;padding: 10px;border-radius: 4px;">
                        <div class="col-md-12" style="text-align: center;font-size: 22px;padding-bottom: 20px">对应发票</div>
                        <div class="col-md-12">
                            <table class="table table-hover" style="margin-bottom: unset">
                                <thead>
                                <tr>
                                    <th>发票号</th>
                                    <th>金额</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($shipping_invoices as $item)
                                    <tr>
                                        <td>
                                            <a href="{{ asset('/admin/shipping-invoices/'.$item->id) }}">{{ $item->invoice_no }}</a>
                                            @if($item->id == $shipping_invoice->id)
                                                <i class="text-success fa fa-check"></i>
                                            @endif
                                        </td>
                                        <td>{{ $item->amount }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>

            <div class="col-md-8">
                <div class="col-md-12" style="text-align: center;font-size: 22px;padding-bottom: 20px">发票清单 <a href="{{ url('/admin/print/shipping-invoices/'.$shipping_invoice->id) }}" target="_blank"><i class="fa fa-print"></i></a></div>
                <div class="col-md-12">
                    <div class="col-md-3" style="border-bottom: 1px solid black;padding: 8px 10px;background-color: #e8e8e8">客户PO：</div>
                    <div class="col-md-9" style="border-bottom: 1px solid black;padding: 8px 10px">{{ $customer_po }}</div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-3" style="border-bottom: 1px solid black;padding: 8px 10px;background-color: #e8e8e8">货代：</div>
                    <div class="col-md-9" style="border-bottom: 1px solid black;padding: 8px 10px">{{ $shipping_invoice->shipping }}</div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-3" style="border-bottom: 1px solid black;padding: 8px 10px;background-color: #e8e8e8">项目：</div>
                    <div class="col-md-9" style="border-bottom: 1px solid black;padding: 8px 10px">{{ $shipping_invoice->project->name }}</div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-3" style="border-bottom: 1px solid black;padding: 8px 10px;background-color: #e8e8e8">货物信息：</div>
                    <div class="col-md-9" style="border-bottom: 1px solid black;padding: 8px 10px">{{ $shipping_invoice->info }}</div>
                </div>
                <div class="col-md-12">
                    <table class="table" style="margin-bottom: unset">
                        <tbody>
                            <tr>
                                <td>工厂PO</td>
                                <td style="text-align: center">工厂</td>
                                <td style="text-align: center">批次</td>
                                <td style="text-align: center">货值</td>
                                <td style="text-align: center">出货时间</td>
                            </tr>
                            @foreach($shipping_invoice->detail as $item)
                            <tr>
                                <td>{{ $orders->where('id', $item['po'])->first()->po  }}</td>
                                <td style="text-align: center">{{ $orders->where('id', $item['po'])->first()->vendor->name }}</td>
                                <td style="text-align: center;">{{ $shipping_invoice->batch }}</td>
                                <td style="text-align: center">¥ {{ $item['amount'] }}</td>
                                <td style="text-align: center;">{{ $shipping_invoice->delivery_time }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="col-md-12">
                    <div class="col-md-3" style="border-top: 1px solid black;border-bottom: 1px solid black;padding: 8px 10px;background-color: #e8e8e8">发票信息：</div>
                    <div class="col-md-9" style="border-top: 1px solid black;border-bottom: 1px solid black;padding: 8px 10px">{{ $shipping_invoice->invoice_info }}</div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-3" style="border-bottom: 1px solid black;padding: 8px 10px;">货代国内发票总额：</div>
                    <div class="col-md-5" style="border-bottom: 1px solid black;padding: 8px 10px">¥ {{ $shipping_invoices->sum('amount') }}</div>
                    <div class="col-md-4" style="border-bottom: 1px solid black;padding: 8px 10px">共<span style="padding: 0 20px;font-weight: bold">{{ $shipping_invoices->count() }}</span>张</div>
                </div>
                <div class="col-md-12">
                    <div style="padding: 8px 10px;">备注： {{ $shipping_invoice->comment }}</div>
                </div>
            </div>

        </div>

        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">凭证</h3>
                </div>
                <div class="panel-body">
                    <p><a target="_blank" href="{{ asset('uploads/'.$shipping_invoice->invoice_image) }}" class="label label-success">{{ str_replace('files/', '', $shipping_invoice->invoice_image) }}</a></p>
                    @if(isset($shipping_invoice->file))
                        @foreach($shipping_invoice->file as $item)
                            <p><a target="_blank" href="{{ asset('uploads/'.$item) }}" class="label label-info">{{ str_replace('files/', '', $item) }}</a></p>
                        @endforeach
                    @endif
                </div>
            </div>

        </div>


    </div>
</div>
