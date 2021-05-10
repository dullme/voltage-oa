<style>
    .table-bordered>tbody>tr>th,
    .table-bordered>tbody>tr>td{
        border: 1px solid #858585;
        vertical-align: middle;
    }

    .table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td {
        vertical-align: middle;
    }

    .products-list .product-info{
        margin-left: unset;
    }

    .product-list-in-box>.item{
        border-bottom: 1px solid #ececec;
    }

    .products-list>.item{
        padding: 20px 0;
    }

</style>

<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">{{ $project->no }} - {{ $project->name }}</h3>

        <div class="box-tools">
            <div class="btn-group pull-right" style="margin-right: 5px">
                <a href="{{ url('/admin/projects') }}" class="btn btn-sm btn-default" title="List">
                    <i class="fa fa-list"></i><span class="hidden-xs"> 列表</span>
                </a>
            </div>
            <div class="btn-group pull-right" style="margin-right: 5px">
                <a href="{{ url('/admin/sales-orders/create?project_id='.$project->id) }}" class="btn btn-sm btn-success" title="新增SO">
                    <i class="fa fa-plus"></i><span class="hidden-xs"> 新增SO</span>
                </a>
            </div>
        </div>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
{{--    <div class="box-body">--}}

{{--    </div>--}}
</div>

@foreach($project->salesOrders as $key=>$salesOrder)
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">销售单号：<a href="{{ url('/admin/sales-orders/'.$salesOrder->id) }}">{{ $salesOrder->no }}</a></h3>
            <div class="box-tools pull-right">
                <a href="{{ url('/admin/sales-orders/'.$salesOrder->id.'/edit') }}" class="btn btn-box-tool"><i class="fa fa-edit"></i></a>
                <a href="{{ url('/admin/purchase-orders/create') }}" class="btn btn-box-tool"><i class="fa fa-plus"></i></a>
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
            <!-- /.box-tools -->
            <div style="display: flex;margin-top: 10px">
                <div>
                    <div>销售金额：$ {{ $salesOrder->amount }}</div>
                    <div class="description" style="margin-top: 10px">下单时间：2021-10-01</div>
                    <div style="display: flex; margin-top: 10px">
                        <span>下单进度：</span>
                        <div class="progress progress-striped active" style="min-width: 300px;margin-bottom:unset;border-radius: .25em">
                            <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: {{ round($salesOrder->progress, 2) * 100 }}%">
                                <span>{{ round($salesOrder->progress, 2) * 100 }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="padding-left: 20px">
                    待采购：
                    @foreach(\App\Models\Vendor::find($salesOrder->vendors) as $vendor)
                        @if(in_array($vendor->id, $salesOrder->purchaseOrders->pluck('vendor_id')->toArray()))
                            <span class="label label-default">{{ $vendor->name }}</span>
                        @else
                            <span class="label label-default" style="background-color: unset">{{ $vendor->name }}</span>
                        @endif
                    @endforeach

                </div>
            </div>
        </div>

        <!-- /.box-header -->
        @if($salesOrder->purchaseOrders->count())
            <div class="box-body">
                <table class="table" style="margin-bottom: 100px">
                    <thead>
                        <tr>
                            <th>采购单号</th>
                            <th>工厂</th>
                            <th>金额</th>
                            <th>下单日期</th>
                            <th>双签日期</th>
                            <th width="300px">进度</th>
                            <th width="100px" style="text-align: center">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($salesOrder->purchaseOrders as $purchaseOrder)
                            <tr>
                                <td><a href="{{ url('/admin/purchase-orders/'.$purchaseOrder->id) }}">{{ $purchaseOrder->po }}</a></td>
                                <td>{{ $purchaseOrder->vendor->name }}</td>
                                <td>¥ {{ $purchaseOrder->amount }}</td>
                                <td>{{ $purchaseOrder->order_at }}</td>
                                <td>{{ $purchaseOrder->double_signed_at }}</td>
                                <td>
                                    <span style="display: none">
                                        {{ $receipt_progress = bigNumber($purchaseOrder->receiptBatches->sum('amount'))->divide($purchaseOrder->amount)->getValue() * 100 }}
                                        {{ $payment_progress = bigNumber($purchaseOrder->paymentBatches->sum('amount'))->divide($purchaseOrder->amount)->getValue() * 100 }}
                                    </span>
                                    <div class="progress-group" style="padding: 10px 0">
                                        <span class="progress-text" style="font-size: 12px">收货进度</span>
                                        <span class="progress-number" style="font-size: 12px"><b>{{ $receipt_progress }}%</b></span>

                                        <div class="progress sm active">
                                            <div class="progress-bar progress-bar-yellow progress-bar-striped"  style="width: {{ $receipt_progress }}%"></div>
                                        </div>
                                    </div>

                                    <div class="progress-group" style="padding: 10px 0">
                                        <span class="progress-text" style="font-size: 12px">付款进度</span>
                                        <span class="progress-number" style="font-size: 12px"><b>{{ $payment_progress }}%</b></span>

                                        <div class="progress sm active">
                                            <div class="progress-bar progress-bar-success progress-bar-striped" style="width: {{ $payment_progress }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td style="text-align: center"><a href="{{ url('/admin/purchase-orders/'.$purchaseOrder->id.'/edit') }}"><i class="fa fa-edit"></i></a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="box-body">
                <div style="text-align: center;padding: 100px;font-size: 30px;color: #dedede;">暂无工厂PO</div>
            </div>
        @endif
        <!-- /.box-body -->
    </div>

@endforeach
