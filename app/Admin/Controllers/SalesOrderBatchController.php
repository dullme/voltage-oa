<?php

namespace App\Admin\Controllers;

use App\Models\SalesOrder;
use App\Models\SalesOrderBatch;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class SalesOrderBatchController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'SalesOrderBatch';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new SalesOrderBatch());

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->equal('sales_order_id', '销售单号')->select(SalesOrder::pluck('no', 'id'));
            $filter->like('no', 'DO 编号');
        });


        $grid->salesOrder()->no('销售单号')->display(function ($salesOrder){
            $url = url('/admin/sales-orders/'.$this->sales_order_id);
            return "<a href='{$url}'>{$salesOrder}</a>";
        });
        $grid->column('no', __('DO 编号'));
        $grid->column('amount', __('销售总金额'))->prefix('$');
        $grid->column('delivery_at', __('发货时间'));
        $grid->column('comment', __('Comment'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

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
        $show = new Show(SalesOrderBatch::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('sales_order_id', __('Sales order id'));
        $show->field('amount', __('Amount'));
        $show->field('delivery_at', __('Delivery at'));
        $show->field('comment', __('Comment'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new SalesOrderBatch());
        if(request()->get('so_id')){
            $form->select('sales_order_id', __('销售单号'))->options(SalesOrder::pluck('no', 'id'))->default(request()->get('so_id'))->required();
        }else{
            $form->select('sales_order_id', __('销售单号'))->options(SalesOrder::pluck('no', 'id'))->required();
        }

        $form->text('no', __('DO 编号'))->required();
        $form->decimal('amount', __('总金额'))->icon('fa-dollar')->required();
        $form->date('delivery_at', __('发货时间'))->required();
        $form->text('comment', __('备注'));

        $form->saved(function (Form $form) {
            admin_toastr('批次添加成功！', 'success');
            return redirect('/admin/sales-orders/'.$form->model()->sales_order_id);
        });

        return $form;
    }
}
