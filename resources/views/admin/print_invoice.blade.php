<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="http://voltage-oa.test/vendor/laravel-admin/AdminLTE/bootstrap/css/bootstrap.min.css">
    <style>
        .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
            border: 1px solid #000000;
        }
    </style>
</head>
<body>
    <table class="table table-bordered" style="max-width: 380px">
        <tbody>
        <tr>
            <th colspan="3" style="text-align: center">发票清单</th>
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
            <td colspan="3">剩余发票总额：<span class="{{ $invoice->purchaseOrder->amount - $invoice_total_amount < 0 ? 'text-danger' : '' }}" style="font-weight: bold;">¥ {{ $invoice->purchaseOrder->amount - $invoice_total_amount }}</span></td>
        </tr>
        </tbody>
    </table>
</body>
</html>
