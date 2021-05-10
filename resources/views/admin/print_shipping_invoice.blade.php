<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="http://voltage-oa.test/vendor/laravel-admin/AdminLTE/bootstrap/css/bootstrap.min.css">
    <style>
        .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
            border-top: 1px solid #000000;
        }
    </style>
</head>
<body>
    <div style="text-align: center;font-size: 22px;padding-bottom: 20px">
        <p>发票清单</p>
    </div>
    <table class="table">
        <tr>
            <td width="30%" style="background-color: #e8e8e8">客户PO：</td>
            <td colspan="2">{{ $customer_po }}</td>
        </tr>
        <tr>
            <td width="30%" style="background-color: #e8e8e8">货代：</td>
            <td colspan="2">{{ $shipping_invoice->shipping }}</td>
        </tr>
        <tr>
            <td width="30%" style="background-color: #e8e8e8">项目：</td>
            <td colspan="2">{{ $shipping_invoice->project->name }}</td>
        </tr>
        <tr>
            <td width="30%" style="background-color: #e8e8e8">货物信息：</td>
            <td colspan="2">{{ $shipping_invoice->info }}</td>
        </tr>
        <tr>
            <td colspan="3" style="padding: 0">
                <table class="table" style="margin-bottom: unset">
                    <tbody>
                    <tr>
                        <td style="border-top:0">工厂PO</td>
                        <td style="border-top:0;text-align: center">工厂</td>
                        <td style="border-top:0;text-align: center">批次</td>
                        <td style="border-top:0;text-align: center">货值</td>
                        <td style="border-top:0;text-align: center">出货时间</td>
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
            </td>
        </tr>

        <tr>
            <td width="30%" style="background-color: #e8e8e8">发票信息：</td>
            <td>{{ $shipping_invoice->invoice_info }}</td>
        </tr>
        <tr>
            <td width="30%" style="background-color: #e8e8e8">货代国内发票总额：</td>
            <td>¥ {{ $shipping_invoices->sum('amount') }}</td>
            <td>共<span style="padding: 0 20px;font-weight: bold">{{ $shipping_invoices->count() }}</span>张</td>
        </tr>
        <tr>
            <td colspan="3">备注： {{ $shipping_invoice->comment }}</td>
        </tr>

    </table>

</body>
</html>
