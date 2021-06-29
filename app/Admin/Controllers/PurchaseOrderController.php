<?php

namespace App\Admin\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Project;
use App\Models\SalesOrder;
use App\Models\Vendor;
use Carbon\Carbon;
use Encore\Admin\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Table;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends BaseController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '采购订单';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new PurchaseOrder());
        $grid->model()->orderByDesc('created_at');
        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->equal('project_id', '项目编号')->select(Project::pluck('no', 'id'));
            $filter->like('po', '采购单号');
            $filter->between('order_at', '下单时间')->date();
            $filter->equal('type', '类别')->select([
                'HARNESS' => 'HARNESS',
                'EXTENDER' => 'EXTENDER',
                'PV AL' => 'PV AL',
                'PV CU'      => 'PV CU',
                'MV'      => 'MV',
                'OTHER'   => 'OTHER',
            ]);

            $projects = Project::all();
            $projects = $projects->map(function ($item){
                $item['name'] = $item['name'] .'【' .$item['no'] . '】';
                return $item;
            });

            $filter->equal('project.id', '项目名称')->select($projects->pluck('name', 'id'));
        });

        $grid->column('po', __('采购单号'))->display(function($po){
            $url = url('/admin/purchase-orders/'.$this->id);
            return "<a href='{$url}'>{$po}</a>";
        });

        $grid->column('salesOrder', '销售订单')->display(function(){
            $url = url('/admin/sales-orders/'.optional($this->salesOrder)->id);
            $no = optional($this->salesOrder)->no;
            return "<a href='{$url}'>{$no}</a>";
        });

        $grid->column('项目名称')->display(function (){
            $url = url('/admin/projects/'.optional($this->project)->id);
            return '<a href="'.$url.'"><p>'.optional($this->project)->name.'</p>'.optional($this->project)->no.'</a>';
        });

        $grid->column('type', __('类别'))->label([
            'HARNESS' => 'success',
            'EXTENDER' => 'success',
            'PV AL' => 'danger',
            'PV CU'      => 'danger',
            'MV'      => 'warning',
            'OTHER'   => 'info',
        ]);

        $grid->column('创建日期')->display(function (){
            return optional(optional($this->salesOrder)->created_at)->toDateString();
        })->width(81);

        $grid->column('order_at', __('下单时间'))->sortable()->width(90);

        $grid->column('double_signed_at', __('双签时间'))->width(81);
        $grid->column('amount', __('订单总额'))->prefix('¥');
        $grid->vendor()->name('供应商');

        $grid->column('交货批次')->display(function (){
            return $this->receiptBatches->count();
        })->expand(function () {
            $comments = $this->receiptBatches->map(function ($item, $key) {
                $item['key'] = ++$key;

                return $item->only(['key', 'receipt_at', 'amount', 'estimated_delivery', 'actual_delivery', 'comment']);
            });

            return new Table(['#', '收货时间', '批次总金额', '预计交期', '实际交期', '交货数量'], $comments->toArray());
        });


//        $grid->column('交货数量')->display(function (){
//            $data = $this->receiptBatches->pluck('comment')->values();
//            $res = '';
//            foreach ($data as $key => $value){
//                $value = $value ??'-';
//                $res .= "<p>".++$key."、 {$value}</p>";
//
//            }
//
//            return $res;
//        });
//
//
//        $grid->column('预计交期')->display(function (){
//            $data = $this->receiptBatches->pluck('estimated_delivery')->values();
//            $res = '';
//            foreach ($data as $key => $value){
//                $value = $value ??'-';
//                $res .= "<p>".++$key."、 ".$value."</p>";
//            }
//            return $res;
//        })->width(105);
//
//        $grid->column('实际交期')->display(function (){
//            $data = $this->receiptBatches->pluck('actual_delivery')->values();
//            $res = '';
//            foreach ($data as $key => $value){
//                $value = $value ??'-';
//                $res .= "<p>".++$key."、 ".$value."</p>";
//            }
//            return $res;
//        })->width(105);

        $grid->column('progress', __('进度'))->display(function (){
            $receipt_progress = bigNumber($this->receiptBatches->sum('amount'))->divide($this->amount)->getValue() * 100;//收货进度
            $payment_progress = bigNumber($this->paymentBatches->sum('amount'))->divide($this->amount)->getValue() * 100;//付款进度

            '<span data-toggle="tooltip" data-placement="top" data-original-title="收货进度">Status</span>';


            return '<div style="display: flex;align-items: flex-end"><div data-toggle="tooltip" data-placement="top" data-original-title="收货进度" class="progress progress-striped active" style="min-width: 100px;margin-bottom:unset;border-radius: .25em"><div class="progress-bar progress-bar-success" style="width: '.$receipt_progress.'%"><span>'.$receipt_progress.'%</span></div></div></div> <div style="display: flex;align-items: flex-end"><div data-toggle="tooltip" data-placement="top" data-original-title="付款进度" class="progress progress-striped active" style="min-width: 100px;margin-bottom:unset;border-radius: .25em"><div class="progress-bar progress-bar-warning" style="width: '.$payment_progress.'%"><span>'.$payment_progress.'%</span></div></div></div>';
        });

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
        $projects = Project::all();
        $projects = $projects->map(function ($item){
            return [
                'id' => $item->id,
                'name' => $item->no .'【' .$item->name.'】',
            ];
        });
        if($form->isCreating()){
            if(request()->get('sales_order_id')){
                $salesOrder = SalesOrder::findOrFail(request()->get('sales_order_id'));
                $form->select('project_id', __('项目'))->options($projects->pluck('name', 'id'))->load('sales_order_id', url('/admin/get-sales-orders'))->default($salesOrder->project_id)->required();
                $form->select('sales_order_id', '销售订单')->options(SalesOrder::where('project_id', $salesOrder->project_id)->pluck('no', 'id'))->default($salesOrder->id)->required();
            }else{
                $form->select('project_id', __('项目'))->options($projects->pluck('name', 'id'))->load('sales_order_id', url('/admin/get-sales-orders'))->required();
                $form->select('sales_order_id', '销售订单')->required();
            }
        }elseif ($form->isEditing()){
            $purchase_order_id = request()->route()->parameters()['purchase_order'];
            $purchaseOrder = PurchaseOrder::findOrFail($purchase_order_id);
            $form->select('project_id', __('项目'))->options($projects->pluck('name', 'id'))->load('sales_order_id', url('/admin/get-sales-orders'))->default($purchaseOrder->project_id)->required();
            $form->select('sales_order_id', '销售订单')->options(SalesOrder::where('project_id', $purchaseOrder->project_id)->pluck('no', 'id'))->default($purchaseOrder->sales_order_id)->required();
        }

        $form->select('type', __('类型'))->options([
            'HARNESS' => 'HARNESS',
            'EXTENDER' => 'EXTENDER',
            'PV AL' => 'PV AL',
            'PV CU'      => 'PV CU',
            'MV'      => 'MV',
            'OTHER'   => 'OTHER',
        ]);
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
