<?php

namespace App\Admin\Controllers;

use App\Models\ReceivePaymentBatch;
use App\Models\SalesOrder;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ReceivePaymentBatchController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '收款批次';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ReceivePaymentBatch());

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->equal('sales_order_id', '销售单号')->select(SalesOrder::pluck('no', 'id'));
            $filter->like('no', 'NO');
        });

        $grid->salesOrder()->no('销售单号')->display(function ($salesOrder){
            $url = url('/admin/sales-orders/'.$this->sales_order_id);
            return "<a href='{$url}'>{$salesOrder}</a>";
        });
        $grid->column('no', __('No'));
        $grid->column('amount', __('收款总金额'));
        $grid->column('receive_payment_at', __('收款时间'));
        $grid->column('comment', __('备注'));

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
        $show = new Show(ReceivePaymentBatch::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('sales_order_id', __('Sales order id'));
        $show->field('no', __('No'));
        $show->field('amount', __('Amount'));
        $show->field('receive_payment_at', __('Receive payment at'));
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
        $form = new Form(new ReceivePaymentBatch());

        if(request()->get('so_id')){
            $form->select('sales_order_id', __('销售单号'))->options(SalesOrder::pluck('no', 'id'))->default(request()->get('so_id'))->required();
        }else{
            $form->select('sales_order_id', __('销售单号'))->options(SalesOrder::pluck('no', 'id'))->required();
        }

        $form->text('no', __('No'))->required();
        $form->decimal('amount', __('收款总金额'))->icon('fa-dollar')->required();
        $form->date('receive_payment_at', __('收款时间'))->required();
        $form->text('comment', __('备注'));

        $form->saved(function (Form $form) {
            admin_toastr('收款记录添加成功！', 'success');
            return redirect('/admin/sales-orders/'.$form->model()->sales_order_id);
        });

        return $form;
    }
}
