<purchase-order po="{{ $purchaseOrder }}"></purchase-order>

{{--<style>--}}
{{--    .table-bordered>tbody>tr>th,--}}
{{--    .table-bordered>tbody>tr>td{--}}
{{--        border: 1px solid #858585;--}}
{{--    }--}}

{{--</style>--}}
{{--<div class="box box-info">--}}
{{--    <div class="box-header with-border">--}}
{{--        <h3 class="box-title"></h3>--}}

{{--        <div class="box-tools">--}}
{{--            <div class="btn-group pull-right" style="margin-right: 5px">--}}
{{--                <a href="{{ url('/admin/projects/') }}" class="btn btn-sm btn-default" title="List">--}}
{{--                    <i class="fa fa-list"></i><span class="hidden-xs"> </span>--}}
{{--                </a>--}}
{{--            </div>--}}
{{--            <div class="btn-group pull-right" style="margin-right: 5px">--}}
{{--                <a href="{{ url('/admin/purchase-orders/create?project_id=') }}" class="btn btn-sm btn-success" title="新增PO">--}}
{{--                    <i class="fa fa-plus"></i><span class="hidden-xs"> 新增PO</span>--}}
{{--                </a>--}}
{{--            </div>--}}
{{--            <div class="btn-group pull-right" style="margin-right: 5px">--}}
{{--                <a href="{{ url('/admin/invoices/create?po_id='.$purchaseOrder->id) }}" class="btn btn-sm btn-success" title="新增PO">--}}
{{--                    <i class="fa fa-plus"></i><span class="hidden-xs"> 新增发票</span>--}}
{{--                </a>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <!-- /.box-header -->--}}
{{--    <!-- form start -->--}}
{{--    <div class="box-body">--}}
{{--        <div class="col-md-12">--}}
{{--            <p>销售订单编号：{{ $purchaseOrder->salesOrder->no }}</p>--}}
{{--            <p>采购订单编号：{{ $purchaseOrder->po }}</p>--}}
{{--            <p>工厂：{{ $purchaseOrder->vendor->name }}</p>--}}
{{--            <p>采购总金额：{{ $purchaseOrder->amount }}</p>--}}
{{--            <p>双签合同时间：{{ $purchaseOrder->double_signed_at }}</p>--}}
{{--            <p>采购订单下单时间：{{ $purchaseOrder->order_at }}</p>--}}
{{--        </div>--}}
{{--        <div class="col-md-6">--}}
{{--            <div class="box">--}}
{{--                <div class="box-header">--}}
{{--                    <h3 class="box-title">收货批次</h3>--}}
{{--                    <div class="box-tools">--}}
{{--                        <a href="{{ url('/admin/receipt-batches/create?po_id='.$purchaseOrder->id) }}" class="btn btn-xs btn-success">新增批次</a>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <!-- /.box-header -->--}}
{{--                <div class="box-body no-padding">--}}
{{--                    <table class="table table-striped">--}}
{{--                        <tbody>--}}
{{--                            <tr>--}}
{{--                                <th style="width: 10px">#</th>--}}
{{--                                <th>金额</th>--}}
{{--                                <th>收货时间</th>--}}
{{--                                <th></th>--}}
{{--                            </tr>--}}
{{--                            @foreach($purchaseOrder->receiptBatches as $key => $receiptBatch)--}}
{{--                            <tr>--}}
{{--                                <td>{{ ++$key }}</td>--}}
{{--                                <td>¥ {{ $receiptBatch->amount }}</td>--}}
{{--                                <td>{{ $receiptBatch->receipt_at }}</td>--}}
{{--                                <td><a href="{{ url('/admin/receipt-batches/'.$receiptBatch->id.'/edit')  }}"><i class="fa fa-edit"></i></a></td>--}}
{{--                            </tr>--}}
{{--                            @endforeach--}}
{{--                        </tbody>--}}
{{--                    </table>--}}
{{--                </div>--}}
{{--                <!-- /.box-body -->--}}
{{--            </div>--}}
{{--        </div>--}}


{{--    </div>--}}
{{--</div>--}}
