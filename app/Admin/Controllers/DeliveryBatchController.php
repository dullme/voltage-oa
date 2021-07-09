<?php

namespace App\Admin\Controllers;

use App\Models\DeliveryBatch;
use App\Models\PurchaseOrder;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class DeliveryBatchController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'DeliveryBatch';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new DeliveryBatch());

        $grid->column('id', __('Id'));
        $grid->column('purchase_order_id', __('Purchase order id'));
        $grid->column('estimated_delivery', __('Estimated delivery'));
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
        $show = new Show(DeliveryBatch::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('purchase_order_id', __('Purchase order id'));
        $show->field('estimated_delivery', __('Estimated delivery'));
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
        $form = new Form(new DeliveryBatch());
        if(request()->get('po_id')){
            $form->select('purchase_order_id', __('采购单号'))->options(PurchaseOrder::pluck('po', 'id'))->default(request()->get('po_id'))->required();
        }else{
            $form->select('purchase_order_id', __('采购单号'))->options(PurchaseOrder::pluck('po', 'id'));
        }


        $form->date('estimated_delivery', __('预计交期'))->required();
        $form->number('order_by', __('排序'))->required();
        $form->textarea('comment', __('备注'));

        $form->saved(function (Form $form) {
            admin_toastr('交货批次添加成功！', 'success');
            return redirect('/admin/purchase-orders/'.$form->model()->purchase_order_id);
        });

        return $form;
    }
}
