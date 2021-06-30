<?php

namespace App\Admin\Controllers;

use App\Models\PurchaseOrder;
use Carbon\Carbon;
use DB;
use App\Models\Project;
use App\Models\SalesOrder;
use App\Models\Vendor;
use Encore\Admin\Admin;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;

class SalesOrderController extends BaseController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '销售订单';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new SalesOrder());
        $grid->model()->with('purchaseOrders')->orderByDesc('order_at');

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->like('no', '销售编号');
        });

        $grid->column('no', __('销售编号'))->display(function ($no){
            $url = url('/admin/sales-orders/'.$this->id);
            return "<a href='{$url}'>{$no}</a>";
        });

        $grid->project()->name('项目名称')->display(function ($name){
            $url = url('/admin/projects/'.optional($this->project)->id);
            $no = optional($this->project)->no;
            return "<a href='{$url}'>{$no}【{$name}】</a>";
        });

        $grid->column('amount', __('销售总额（美元）'))->display(function ($amount){
            return is_null($amount) ? '-' : '$ '.$amount;
        });
        $grid->column('customer_po', __('客户PO'));
        $grid->purchaseOrders(__('采购订单'))->display(function ($purchases){
            $pos = collect($purchases)->pluck('po')->toArray();
            $res = '';
            foreach ($pos as $key => $po){
                $res .= "<p>{$po}</p>";

            }
            return $res;
        });

        $grid->column('progress', __('进度'))->display(function (){
            $order_progress = getOrderProgress($this->vendors, $this->purchaseOrders->pluck('vendor_id')->toArray()); //下单进度

            $shipped = collect($this->salesOrderBatches)->sum('amount');
            $shipped_progress = getDeliveryProgress($shipped, $this->amount); //发货进度

            $received = collect($this->receivePaymentBatches)->sum('amount');
            $received_progress = getReceivedProgress($received, $this->amount); //收款进度

            return '<div style="display: flex;align-items: flex-end"><span>下单进度：</span><div class="progress progress-striped active" style="min-width: 200px;margin-bottom:unset;border-radius: .25em"><div class="progress-bar progress-bar-primary" style="width: '.$order_progress.'%"><span>'.$order_progress.'%</span></div></div></div> <div style="display: flex;align-items: flex-end"><span>发货进度：</span><div class="progress progress-striped active" style="min-width: 200px;margin-bottom:unset;border-radius: .25em"><div class="progress-bar progress-bar-success" style="width: '.$shipped_progress.'%"><span>'.$shipped_progress.'%</span></div></div></div> <div style="display: flex;align-items: flex-end"><span>收款进度：</span><div class="progress progress-striped active" style="min-width: 200px;margin-bottom:unset;border-radius: .25em"><div class="progress-bar progress-bar-warning" style="width: '.$received_progress.'%"><span>'.$received_progress.'%</span></div></div></div>';
        });

        $grid->column('order_at', __('下单时间'));

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

        $salesOder = SalesOrder::with('salesOrderBatches', 'receivePaymentBatches')->findOrFail($id);
        $shipped = bigNumber($salesOder->salesOrderBatches->sum('amount'))->getValue();//已发货总金额
        $received = bigNumber($salesOder->receivePaymentBatches->sum('amount'))->getValue();//已收款总金额
        $salesOder->setAttribute('shipped', $shipped);
        $salesOder->setAttribute('not_shipped', bigNumber($salesOder->amount)->subtract($shipped)->getValue());
        $salesOder->setAttribute('shipped_progress', getDeliveryProgress($shipped, $salesOder->amount));
        $salesOder->setAttribute('received', $received);
        $salesOder->setAttribute('not_received', bigNumber($salesOder->amount)->subtract($received)->getValue());
        $salesOder->setAttribute('received_progress', getReceivedProgress($received, $salesOder->amount));
        $salesOder->setAttribute('days', Carbon::parse($salesOder->order_at)->diffInDays(Carbon::now(), false));

        return view('admin.sales_order', compact('salesOder'));
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {

        $form = new Form(new SalesOrder());
        $projects = Project::all();
        $projects = $projects->map(function ($item){
            return [
                'id' => $item->id,
                'name' => $item->no .'【' .$item->name.'】',
            ];
        });
        if($form->isCreating()){
            if(request()->get('project_id')){
                $form->select('project_id', __('项目'))->options($projects->pluck('name', 'id'))->default(request()->get('project_id'))->required();
            }else{
                $form->select('project_id', __('项目'))->options($projects->pluck('name', 'id'))->required();
            }
        }elseif ($form->isEditing()){
            $sales_order_id = request()->route()->parameters()['sales_order'];
            $purchaseOrder = SalesOrder::findOrFail($sales_order_id);
            $form->select('project_id', __('项目'))->options($projects->pluck('name', 'id'))->default($purchaseOrder->project_id)->required();
        }


        $form->text('no', __('订单编号'))->creationRules(['required', "unique:sales_orders"])
            ->updateRules(['required', "unique:sales_orders,no,{{id}}"]);
        $form->decimal('amount', __('总金额'))->icon('fa-dollar')->rules('nullable|gt:0');
        $form->date('order_at', __('下单时间'))->default(date('Y-m-d'));
        $form->text('customer_po', __('客户 PO'));
        $form->number('vendors_count', __('Vendors count'))->setDisplay(false);
        $form->multipleSelect('vendors', __('供应商'))->options(Vendor::pluck('name', 'id'));

        $form->saving(function (Form $form) {
            $vendors = collect($form->vendors)->whereNotNull();
            $form->vendors_count = $vendors->count();
        });

        $form->saved(function (Form $form) {
            if(request()->get('project_id') && $form->isCreating()){
                admin_toastr('销售订单创建成功！', 'success');
            }
            return redirect('/admin/projects/'.$form->model()->project_id);
        });

        return $form;
    }

    public function getSalesOrders(Request $request)
    {
        $project_id = $request->get('q');

        return SalesOrder::where('project_id', $project_id)->get(['id', DB::raw('no as text')]);
    }

}
