<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Post\InvoiceAction;
use Encore\Admin\Facades\Admin;
use DB;
use Encore\Admin\Widgets\Box;
use Encore\Admin\Widgets\InfoBox;
use PDF;
use App\Models\Invoice;
use App\Models\PaymentBatch;
use App\Models\PaymentBatchInvoice;
use App\Models\PurchaseOrder;
use App\Models\Project;
use App\Models\ReceiptBatch;
use App\Models\ReceiptBatchInvoice;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends ResponseController
{

    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '工厂发票';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Invoice());
        $grid->model()->orderBy('status', 'ASC')->orderByDesc('created_at');
        $grid->disableExport();
        $grid->disableRowSelector();

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->like('invoice_no', '发票号码');
            $filter->equal('purchaseOrder.project.id', '项目名称')->select(Project::pluck('name', 'id'));
            $filter->equal('purchaseOrder.id', '工厂PO')->select(PurchaseOrder::pluck('po', 'id'));
        });

        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->add(new InvoiceAction());
        });

        $grid->header(function ($query) {
            $invoice = $query->select('status')->get();
            $not_signed = $invoice->where('status', 0)->count();
            $signed = $invoice->where('status', 1)->count();
            return view('admin.invoice.invoice', compact('not_signed', 'signed'));
        });

        $grid->column('项目编号')->display(function () {
            return optional(optional($this->purchaseOrder)->project)->no;
        });
        $grid->column('工厂PO')->display(function () {
            return optional($this->purchaseOrder)->po;
        });
//        $grid->column('项目名称')->display(function () {
//            return $this->purchaseOrder->project->name;
//        });

        $grid->column('invoice_no', __('发票号码'))->display(function () {
            $url = asset('/admin/invoices/' . $this->id);

            return "<a href='$url'>$this->invoice_no</a>";
        });

        $grid->column('amount', __('发票总金额'))->prefix('¥');
        $grid->column('invoice_image', __('发票原件'))->display(function ($invoice_image){
            $url = asset('uploads/'.$invoice_image);
            $name = str_replace('files/', '', $invoice_image);
            return "<a target='_blank' href='{$url}' class='label label-success'>{$name}</a>";
        });

        $grid->column('title', __('标题'));
        $grid->column('工厂')->display(function () {
            return optional(optional($this->purchaseOrder)->vendor)->name;
        });



        $grid->column('billing_time', __('开票时间'));

        $grid->column('status','签收情况')->display(function ($status){
            return $status ? '已签收' : '待签收';
        })->label([
            0 => 'danger',
            1 => 'success',
        ]);


        $grid->column('print', __('打印'))->display(function (){
            $url = url('/admin/print/invoices/'.$this->id);
            return '<a href="'.$url.'" target="_blank"><i class="fa fa-print"></i></a>';
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
        $invoice = Invoice::with(['adminUser', 'purchaseOrder' => function($query){
            $query->with('project', 'salesOrder');
        }])->findOrFail($id);

        $invoices = Invoice::where('purchase_order_id', $invoice->purchase_order_id)->orderBy('serial', 'ASC')->get();
        $invoice_total_amount = $invoices->sum('amount');
        $invoices = $invoices->groupBy('serial');

        return view('admin/invoice', compact('invoice', 'invoices', 'invoice_total_amount'));
    }

    protected function printDetail($id)
    {
        $invoice = Invoice::with(['purchaseOrder' => function($query){
            $query->with('project', 'salesOrder');
        }])->findOrFail($id);

        $invoices = Invoice::where('purchase_order_id', $invoice->purchase_order_id)->orderBy('serial', 'ASC')->get();
        $invoice_total_amount = $invoices->sum('amount');
        $invoices = $invoices->groupBy('serial');


        $pdf = PDF::loadView('admin.print_invoice', compact('invoice', 'invoices', 'invoice_total_amount'));

        return $pdf->inline();
//        return $pdf->download($invoice->invoice_no.'invoice.pdf');

//        return view('admin.print_invoice', compact('invoice', 'invoices', 'invoice_total_amount'));
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Invoice());
        $form->select('admin_users_id', __('制单人'))->options(\Illuminate\Support\Facades\DB::table('admin_users')->pluck('name', 'id'))->required();

        $po = PurchaseOrder::with('project')->get()->map(function ($item){
            $item['po'] = '【'.$item['project']['name'].'】'.$item['po'];
            return $item;
        });

        $form->select('purchase_order_id', __('工厂PO'))->options($po->pluck('po', 'id'))->required();
        $form->hidden('vendor_id');
        $form->number('serial', __('序号'))->rules('required|numeric|min:0|max:100')->default('');
        $form->text('title', __('标题'));
        $form->text('invoice_no', __('发票号码'))->creationRules(['required', "unique:invoices"])
            ->updateRules(['required', "unique:invoices,invoice_no,{{id}}"]);
        $form->decimal('amount', __('发票金额'))->required();
        $form->date('billing_time', __('开票时间'))->required();
        $form->file('invoice_image', __('发票凭证'))->required()->removable();
        $form->multipleFile('file', __('其他凭证'))->sortable()->removable();
        $form->textarea('comment', __('备注'));

        $form->saving(function (Form $form) {
            if($form->purchase_order_id){
                $form->vendor_id = PurchaseOrder::findOrFail($form->purchase_order_id)->vendor_id;
            }
        });

        return $form;
    }

    /**
     * 关联发票
     */
    public function associatedInvoice($id)
    {
        $receiptBatchInvoice = ReceiptBatch::with('purchaseOrder.vendor.invoices.receiptBatchInvoices', 'receiptBatchInvoices')->findOrFail($id);
        $receiptBatch = ReceiptBatchInvoice::with('invoice')->where('receipt_batch_id', $id)->get();

        $invoices = $receiptBatchInvoice->purchaseOrder->vendor->invoices->where('purchase_order_id', $receiptBatchInvoice->purchase_order_id)->map(function ($item) {
            $receiptBatchInvoicesTotalAmount = $item->receiptBatchInvoices->sum('amount');
            $over_amount = bigNumber($item->amount)->subtract($receiptBatchInvoicesTotalAmount)->getValue();

            return [
                'id'                => $item->id,
                'purchase_order_id' => $item->purchase_order_id,
                'invoice_no'        => $item->invoice_no,
                'serial'            => $item->serial,
                'title'             => $item->title,
                'amount'            => $item->amount, //发票总金额
                'comment'           => $item->comment,
                'billing_time'      => $item->billing_time,
                'over_amount'       => $over_amount, //待匹配金额
                'match_amount'      => '', //匹配金额
                'is_used'           => $receiptBatchInvoicesTotalAmount == $item->amount ?? false, //如果是false 则表示该发票还未完全被匹配
            ];
        });

        return response()->json([
            'data' => [
                'id'            => $receiptBatchInvoice->id,
                'amount'        => $receiptBatchInvoice->amount,
                'receipt_at'    => $receiptBatchInvoice->receipt_at,
                'comment'       => $receiptBatchInvoice->comment,
                'invoices'      => $invoices->where('is_used', false)->values(),
                'receipt_batch' => $receiptBatch,
                'receipt_batch_total_amount' => $receiptBatch->sum('amount'),
            ],
        ]);
    }

    /**
     * 关联付款发票
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function associatedPaymentInvoice($id)
    {
        $paymentBatchInvoice = PaymentBatch::with('purchaseOrder.vendor.invoices.paymentBatchInvoices', 'paymentBatchInvoices')->findOrFail($id);
        $paymentBatch = PaymentBatchInvoice::with('invoice')->where('payment_batch_id', $id)->get();
        $invoices = $paymentBatchInvoice->purchaseOrder->vendor->invoices->where('purchase_order_id', $paymentBatchInvoice->purchase_order_id)->map(function ($item) {
            $paymentBatchInvoicesTotalAmount = $item->paymentBatchInvoices->sum('amount');
            $over_amount = bigNumber($item->amount)->subtract($paymentBatchInvoicesTotalAmount)->getValue();

            return [
                'id'                => $item->id,
                'purchase_order_id' => $item->purchase_order_id,
                'invoice_no'        => $item->invoice_no,
                'serial'            => $item->serial,
                'title'             => $item->title,
                'amount'            => $item->amount, //发票总金额
                'comment'           => $item->comment,
                'billing_time'      => $item->billing_time,
                'over_amount'       => $over_amount, //待匹配金额
                'match_amount'      => '', //匹配金额
                'is_used'           => $paymentBatchInvoicesTotalAmount == $item->amount ?? false, //如果是false 则表示该发票还未完全被匹配
            ];
        });

        return response()->json([
            'data' => [
                'id'            => $paymentBatchInvoice->id,
                'amount'        => $paymentBatchInvoice->amount,
                'payment_at'    => $paymentBatchInvoice->payment_at,
                'comment'       => $paymentBatchInvoice->comment,
                'invoices'      => $invoices->where('is_used', false)->values(),
                'payment_batch' => $paymentBatch,
                'payment_batch_total_amount' => $paymentBatch->sum('amount'),
            ],
        ]);
    }

    public function saveMatchAmount(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            '*.match_amount' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return $this->setStatusCode(422)->responseError('关联金额必须为数值');
        }

        $receipt_batch = ReceiptBatch::with('receiptBatchInvoices')->findOrFail($id);

        if($receipt_batch->receiptBatchInvoices->sum('amount') >= $receipt_batch->amount){
            return $this->setStatusCode(422)->responseError('该批次金额已完成匹配');
        }

        $receipt_batch_over_amount = bigNumber($receipt_batch->amount)->subtract($receipt_batch->receiptBatchInvoices->sum('amount'))->getValue();//剩余可匹配金额

        if(collect($request->all())->sum('match_amount') <= 0){
            return $this->setStatusCode(422)->responseError('匹配金额必须大于0');
        }

        if(collect($request->all())->sum('match_amount') > $receipt_batch_over_amount){
            return $this->setStatusCode(422)->responseError('本次匹配金额已超额');
        }

        try {
            collect($request->all())->map(function ($item) use($receipt_batch_over_amount, $id, $receipt_batch) {
                if($receipt_batch->purchase_order_id != $item['purchase_order_id']){
                    throw new \Exception('匹配错误');
                }
                if($item['match_amount']){
                    $invoice = Invoice::with('receiptBatchInvoices')->findOrFail($item['id']);
                    $matched_amount = $invoice->receiptBatchInvoices->sum('amount'); //已关联金额
                    $over_amount = bigNumber($invoice->amount)->subtract($matched_amount)->getValue(); //未关联金
                    if($item['match_amount'] > $over_amount){
                        throw new \Exception('可关联金额不足');
                    }

                    ReceiptBatchInvoice::insert([
                        'invoice_id' => $invoice->id,
                        'receipt_batch_id' => $id,
                        'amount' => $item['match_amount'],
                    ]);

                }
            });
        }catch (\Exception $exception){
            return $this->setStatusCode(422)->responseError($exception->getMessage());
        }

        return $this->responseSuccess('匹配成功');
    }

    public function savePaymentMatchAmount(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            '*.match_amount' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return $this->setStatusCode(422)->responseError('关联金额必须为数值');
        }

        $payment_batch = PaymentBatch::with('paymentBatchInvoices')->findOrFail($id);

        if($payment_batch->paymentBatchInvoices->sum('amount') >= $payment_batch->amount){
            return $this->setStatusCode(422)->responseError('该批次金额已完成匹配');
        }

        $payment_batch_over_amount = bigNumber($payment_batch->amount)->subtract($payment_batch->paymentBatchInvoices->sum('amount'))->getValue();//剩余可匹配金额

        if(collect($request->all())->sum('match_amount') <= 0){
            return $this->setStatusCode(422)->responseError('匹配金额必须大于0');
        }

        if(collect($request->all())->sum('match_amount') > $payment_batch_over_amount){
            return $this->setStatusCode(422)->responseError('本次匹配金额已超额');
        }

        try {
            collect($request->all())->map(function ($item) use($payment_batch_over_amount, $id, $payment_batch) {
                if($payment_batch->purchase_order_id != $item['purchase_order_id']){
                    throw new \Exception('匹配错误');
                }
                if($item['match_amount']){
                    $invoice = Invoice::with('paymentBatchInvoices')->findOrFail($item['id']);
                    $matched_amount = $invoice->paymentBatchInvoices->sum('amount'); //已关联金额
                    $over_amount = bigNumber($invoice->amount)->subtract($matched_amount)->getValue(); //未关联金
                    if($item['match_amount'] > $over_amount){
                        throw new \Exception('可关联金额不足');
                    }

                    PaymentBatchInvoice::insert([
                        'invoice_id' => $invoice->id,
                        'payment_batch_id' => $id,
                        'amount' => $item['match_amount'],
                    ]);

                }
            });
        }catch (\Exception $exception){
            return $this->setStatusCode(422)->responseError($exception->getMessage());
        }

        return $this->responseSuccess('匹配成功');
    }


    public function deleteMatchAmount($id)
    {
        ReceiptBatchInvoice::destroy($id);

        return $this->responseSuccess('撤销成功');
    }

    public function deletePaymentMatchAmount($id)
    {
        PaymentBatchInvoice::destroy($id);

        return $this->responseSuccess('撤销成功');
    }

    public function destroy($id)
    {
        if(Admin::user()->cannot('invoice.delete')){
            return admin_toastr('您没有权限删除发票', 'error');
        }

        $invoice = Invoice::findOrFail($id);

        if($invoice->status == 1){
            return admin_toastr('该发票已签收无法删除', 'error');
        }

        return $this->form()->destroy($id);
    }
}
