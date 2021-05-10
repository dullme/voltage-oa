<?php

namespace App\Admin\Controllers;

use App\Models\PurchaseOrder;
use App\Models\ReceiptBatch;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ReceiptBatchController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'ReceiptBatch';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ReceiptBatch());

//        $grid->purchaseOrder()->po('工厂PO');
        $grid->column('purchaseOrder.po', '采购单号');
        $grid->column('purchaseOrder', '供应商')->display(function (){
            return $this->purchaseOrder()->with('vendor')->first()->vendor->name;
        });
        $grid->column('salesOrder', '销售单号')->display(function (){
            return $this->purchaseOrder()->with('salesOrder')->first()->salesOrder->no;
        });
        $grid->column('amount', __('金额'));
        $grid->column('receipt_at', __('收货时间'));
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
        $show = new Show(ReceiptBatch::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('purchase_order_id', __('Purchase order id'));
        $show->field('amount', __('Amount'));
        $show->field('receipt_at', __('Receipt at'));
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
        $form = new Form(new ReceiptBatch());

        if(request()->get('po_id')){
            $form->select('purchase_order_id', __('采购单号'))->options(PurchaseOrder::pluck('po', 'id'))->default(request()->get('po_id'))->required();
        }else{
            $form->select('purchase_order_id', __('采购单号'))->options(PurchaseOrder::pluck('po', 'id'));
        }

        $form->decimal('amount', __('金额'))->required();
        $form->date('receipt_at', __('收货时间'));
        $form->date('estimated_delivery', __('预计交期'));
        $form->date('actual_delivery', __('实际交期'));
        $form->textarea('comment', __('备注'));

        $form->saved(function (Form $form) {
            admin_toastr('批次添加成功！', 'success');
            return redirect('/admin/purchase-orders/'.$form->model()->purchase_order_id);
        });

        return $form;
    }
}
