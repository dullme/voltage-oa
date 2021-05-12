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

class SalesOrderController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'SalesOrder';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new SalesOrder());

        $grid->project()->name('项目名称');
        $grid->column('no', __('采购编号'));
        $grid->column('amount', __('采购金额'));
        $grid->column('customer_po', __('客户PO'));
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
//        Admin::script(<<<EOF
//        const app = new Vue({
//        el: '#app'
//    });
//EOF
//        );

//        $salesOder = SalesOrder::with('salesOrderBatches')->findOrFail($id);

//        return view('admin.sales_order', compact('salesOder'));
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {

        $form = new Form(new SalesOrder());

        if($form->isCreating()){
            $projects = Project::all();
            $projects = $projects->map(function ($item){
                return [
                    'id' => $item->id,
                    'name' => $item->no .'【' .$item->name.'】',
                ];
            });
            if(request()->get('project_id')){
                $form->select('project_id', __('项目'))->options($projects->pluck('name', 'id'))->default(request()->get('project_id'))->required();
            }else{
                $form->select('project_id', __('项目'))->options($projects->pluck('name', 'id'))->required();
            }
        }


        $form->text('no', __('订单编号'))->creationRules(['required', "unique:sales_orders"])
            ->updateRules(['required', "unique:sales_orders,no,{{id}}"]);
        $form->decimal('amount', __('总金额'));
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
