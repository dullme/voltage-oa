<?php

namespace App\Admin\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Project;
use App\Models\SalesOrder;
use App\Models\Vendor;
use Carbon\Carbon;
use Encore\Admin\Admin;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Order';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new PurchaseOrder());
        $grid->column('po', __('采购单号'))->display(function($po){
            $url = url('/admin/purchase-orders/'.$this->id);
            return "<a href='{$url}'>{$po}</a>";
        });
        $grid->vendor()->name('供应商');
        $grid->salesOrder()->no('销售订单');
        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->equal('project_id', '项目编号')->select(Project::pluck('name', 'id'));
        });

        $grid->column('项目名称')->display(function (){
            return $this->project->name;
        });
        $grid->column('项目编号')->display(function (){
            return $this->project->no;
        });
        $grid->column('amount', __('订单总额'));

        $grid->column('double_signed_at', __('获取双签合同时间'));
        $grid->column('created_at', __('创建时间'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        Admin::script(<<<EOF
        const app = new Vue({
        el: '#app'
    });
EOF
        );

        $purchaseOrder = PurchaseOrder::with('salesOrder', 'vendor', 'receiptBatches.receiptBatchInvoices', 'paymentBatches.paymentBatchInvoices')->findOrFail($id);

        $receipt_batches = $purchaseOrder->receiptBatches->map(function ($item){
            $item['matched_amount'] = $item->receiptBatchInvoices->sum('amount');
            return $item;
        });

        $payment_batches = $purchaseOrder->paymentBatches->map(function ($item){
            $item['matched_amount'] = $item->paymentBatchInvoices->sum('amount');
            return $item;
        });

        $purchaseOrder->setAttribute('receipt_batches', $receipt_batches);
        $purchaseOrder->setAttribute('payment_batches', $payment_batches);
        $purchaseOrder->setAttribute('days', Carbon::parse($purchaseOrder->order_at)->diffInDays(Carbon::now(), false));

        $purchaseOrder->setAttribute('progress', bigNumber($receipt_batches->sum('amount'))->divide($purchaseOrder->amount)->getValue() * 100);
        $purchaseOrder->setAttribute('be_received', bigNumber($purchaseOrder->amount)->subtract($receipt_batches->sum('amount'))->getValue());

        $purchaseOrder->setAttribute('payment_progress', bigNumber($payment_batches->sum('amount'))->divide($purchaseOrder->amount)->getValue() * 100);
        $purchaseOrder->setAttribute('be_payment', bigNumber($purchaseOrder->amount)->subtract($payment_batches->sum('amount'))->getValue());

        return view('admin.purchase_order', compact('purchaseOrder'));
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new PurchaseOrder());

        if($form->isCreating()){
            $projects = Project::all();
            $projects = $projects->map(function ($item){
                return [
                    'id' => $item->id,
                    'name' => $item->no .'【' .$item->name.'】',
                ];
            });
            if(request()->get('sales_order_id')){
                $salesOrder = SalesOrder::findOrFail(request()->get('sales_order_id'));
                $form->select('project_id', __('项目'))->options($projects->pluck('name', 'id'))->load('sales_order_id', url('/admin/get-sales-orders'))->default($salesOrder->project_id)->required();
                $form->select('sales_order_id', '销售订单')->options(SalesOrder::where('project_id', $salesOrder->project_id)->pluck('no', 'id'))->required();
            }else{
                $form->select('project_id', __('项目'))->options($projects->pluck('name', 'id'))->load('sales_order_id', url('/admin/get-sales-orders'))->required();
                $form->select('sales_order_id', '销售订单')->required();
            }
        }

        $form->select('vendor_id', __('供应商'))->options(Vendor::pluck('name', 'id'))->required();
        $form->text('po', __('采购单号'))->required();
        $form->decimal('amount', __('订单总额'))->required();
        $form->date('order_at', __('下单日期'));
        $form->date('double_signed_at', __('获取双签合同时间'));

        $form->saved(function (Form $form) {

            return redirect('/admin/purchase-orders/'.$form->model()->id);
        });

        return $form;
    }
}
