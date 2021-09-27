<?php

namespace App\Admin\Controllers;

use DB;
use App\Models\PaymentBatch;
use App\Models\PurchaseOrder;
use App\Models\ReceiptBatch;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class PaymentBatchController extends BaseController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '付款批次';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new PaymentBatch());

        //        $grid->purchaseOrder()->po('工厂PO');
        $grid->column('purchaseOrder.po', '采购单号');
        $grid->column('purchaseOrder', '供应商')->display(function (){
            return optional(optional($this->purchaseOrder()->with('vendor')->first())->vendor)->name;
        });
        $grid->column('salesOrder', '销售单号')->display(function (){
            return optional(optional($this->purchaseOrder()->with('salesOrder')->first())->salesOrder)->no;
        });
        $grid->column('amount', __('金额'));
        $grid->column('payment_at', __('付款时间'));
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
        $show = new Show(PaymentBatch::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('purchase_order_id', __('Purchase order id'));
        $show->field('amount', __('Amount'));
        $show->field('payment_at', __('Payment at'));
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
        $form = new Form(new PaymentBatch());

        if(request()->get('po_id')){
            $form->select('purchase_order_id', __('采购单号'))->options(PurchaseOrder::pluck('po', 'id'))->default(request()->get('po_id'))->required();
        }else{
            $form->select('purchase_order_id', __('采购单号'))->options(PurchaseOrder::pluck('po', 'id'));
        }

        $form->decimal('amount', __('金额'))->required();
        $form->date('payment_at', __('付款时间'))->required();
        $form->textarea('comment', __('备注'));

        $form->saving(function (Form $form){
            $purchaseOrder = PurchaseOrder::findOrfail($form->purchase_order_id);
            if($form->isCreating()){
                $purchaseOrder->paid_amount = bigNumber($purchaseOrder->paid_amount)->add($form->amount);
            }elseif ($form->isEditing()){
                $purchaseOrder->paid_amount = bigNumber($purchaseOrder->paid_amount)->subtract($form->model()->amount)->add($form->amount);
            }

            $purchaseOrder->is_paid = $purchaseOrder->paid_amount >= $purchaseOrder->amount;

            $purchaseOrder->save();
        });

        $form->saved(function (Form $form) {
            admin_toastr('批次添加成功！', 'success');
            return redirect('/admin/purchase-orders/'.$form->model()->purchase_order_id);
        });

        return $form;

    }


    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $paymentBatch = PaymentBatch::with('paymentBatchInvoices')->findOrFail($id);
            $paymentBatchInvoicesCount = $paymentBatch->paymentBatchInvoices->count();

            if($paymentBatchInvoicesCount > 0){
                throw new \Exception('该付款序列已匹配发票无法删除');
            }

            $purchaseOrder = PurchaseOrder::findOrFail($paymentBatch->purchase_order_id);
            $purchaseOrder->paid_amount = bigNumber($purchaseOrder->paid_amount)->subtract($paymentBatch->amount);
            $purchaseOrder->is_paid = $purchaseOrder->paid_amount >= $purchaseOrder->amount;
            $purchaseOrder->save();

            $this->form()->destroy($id);
            DB::commit();

            return true;
        } catch (\Exception $e){
            DB::rollback();
            return admin_toastr($e->getMessage(), 'error');
        }
    }
}
