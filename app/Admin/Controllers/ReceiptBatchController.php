<?php

namespace App\Admin\Controllers;

use DB;
use App\Models\PurchaseOrder;
use App\Models\ReceiptBatch;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ReceiptBatchController extends BaseController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '收货批次';

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
        $grid->column('receipt_at', __('实际交期'));
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
        $form->date('receipt_at', __('实际交期'));
        $form->textarea('comment', __('备注'));

        $form->saving(function (Form $form){
            $purchaseOrder = PurchaseOrder::findOrfail($form->purchase_order_id);
            if($form->isCreating()){
                $purchaseOrder->received_amount = bigNumber($purchaseOrder->received_amount)->add(is_null($form->amount) ? 0 : $form->amount);
            }elseif ($form->isEditing()){
                $purchaseOrder->received_amount = bigNumber($purchaseOrder->received_amount)->subtract(is_null($form->model()->amount) ? 0 : $form->model()->amount)->add(is_null($form->amount) ? 0 : $form->amount);
            }

            $purchaseOrder->is_received = $purchaseOrder->received_amount >= $purchaseOrder->amount;

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
            $receiptBatch = ReceiptBatch::with('receiptBatchInvoices')->findOrFail($id);
            $receiptBatchInvoicesCount = $receiptBatch->receiptBatchInvoices->count();

            if($receiptBatchInvoicesCount > 0){
                throw new \Exception('该收货序列已匹配发票无法删除');
            }

            $purchaseOrder = PurchaseOrder::findOrFail($receiptBatch->purchase_order_id);
            $purchaseOrder->received_amount = bigNumber($purchaseOrder->received_amount)->subtract($receiptBatch->amount);
            $purchaseOrder->is_received = $purchaseOrder->received_amount >= $purchaseOrder->amount;
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
