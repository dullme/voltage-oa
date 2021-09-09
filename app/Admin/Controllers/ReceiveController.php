<?php

namespace App\Admin\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\ReceiptBatch;
use App\Models\ReceiptBatchInvoice;
use App\Models\Receive;
use App\Models\ReceivePaymentBatch;
use App\Models\ReceivePaymentBatchReceive;
use App\Models\SalesOrderBatch;
use App\Models\SalesOrderBatchReceive;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReceiveController extends ResponseController
{

    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Receive';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Receive());

        $grid->customer()->name('客户');
        $grid->column('bank_receipt', __('银行水单'));
        $grid->column('currency', __('币种'));
        $grid->column('amount', __('金额'));
        $grid->column('receive_payment_at', __('收款时间'));
        $grid->column('remark', __('备注'));

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
        $show = new Show(Receive::findOrFail($id));

        $show->field('customer_id', __('付款人'));
        $show->field('bank_receipt', __('银行水单'));
        $show->field('amount', __('金额'));
        $show->field('currency', __('币种'));
        $show->field('receive_payment_at', __('收款时间'));
        $show->field('remark', __('备注'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Receive());

        $form->select('customer_id', __('付款人'))->options(Customer::pluck('name', 'id'))->required();
        $form->text('bank_receipt', __('银行水单'))->required();
        $form->select('currency', __('币种'))->options(['USD' => 'USD', 'CNY' => 'CNY'])->default('USD')->required();
        $form->decimal('amount', __('金额'))->required();
        $form->date('receive_payment_at', __('收款时间'))->default(date('Y-m-d'))->required();
        $form->textarea('remark', __('备注'));

        return $form;
    }

    /**
     * 发货序列关联收款水单
     */
    public function associatedReceive($id)
    {
        $salesOrderBatchReceive = SalesOrderBatch::with('salesOrderBatchReceives.receive', 'salesOrder')->findOrFail($id);
        $receives = Receive::with('salesOrderBatchReceives')->where('customer_id', $salesOrderBatchReceive->salesOrder->customer_id)->get();   //查询某个客户的所有水单

        $receives = $receives->map(function ($item) { //过滤已经匹配完成的水单
            $matched = $item->salesOrderBatchReceives->sum('amount');
            $over_amount = bigNumber($item->amount)->subtract($matched)->getValue();

            return [
                'id'                 => $item->id,
                'customer_id'        => $item->customer_id,
                'amount'             => $item->amount, //水单总金额
                'receive_payment_at' => $item->receive_payment_at,//收款时间
                'currency'           => $item->currency,//币种
                'bank_receipt'       => $item->bank_receipt,//银行水单
                'remark'             => $item->remark,//备注
                'over_amount'        => $over_amount, //待匹配金额
                'match_amount'       => '', //匹配金额
                'is_used'            => $matched == $item->amount ?? false, //如果是false 则表示该发票还未完全被匹配
            ];
        });

        return response()->json([
            'data' => [
                'id'                                => $salesOrderBatchReceive->id,
                'no'                                => $salesOrderBatchReceive->no,
                'amount'                            => $salesOrderBatchReceive->amount,
                'delivery_at'                       => $salesOrderBatchReceive->delivery_at,
                'comment'                           => $salesOrderBatchReceive->comment,
                'receives'                          => $receives->where('is_used', false)->values(),
                'sales_order_batch_receives'        => $salesOrderBatchReceive->salesOrderBatchReceives,
                'sales_order_batch_receives_amount' => $salesOrderBatchReceive->salesOrderBatchReceives->sum('amount'),
            ],
        ]);
    }

    /**
     * 保存发货序列关联收款水单
     */
    public function saveAssociatedReceive(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            '*.match_amount' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return $this->setStatusCode(422)->responseError('关联金额必须为数值');
        }

        $salesOrderBatchReceive = SalesOrderBatch::with('salesOrderBatchReceives', 'salesOrder')->findOrFail($id);

        if ($salesOrderBatchReceive->salesOrderBatchReceives->sum('amount') >= $salesOrderBatchReceive->amount) {
            return $this->setStatusCode(422)->responseError('该批次金额已完成匹配');
        }

        $over_amount = bigNumber($salesOrderBatchReceive->amount)->subtract($salesOrderBatchReceive->salesOrderBatchReceives->sum('amount'))->getValue();//剩余可匹配金额

        if (collect($request->all())->sum('match_amount') <= 0) {
            return $this->setStatusCode(422)->responseError('匹配金额必须大于0');
        }

        if (collect($request->all())->sum('match_amount') > $over_amount) {
            return $this->setStatusCode(422)->responseError('本次匹配金额已超额');
        }

        try {
            collect($request->all())->map(function ($item) use ($over_amount, $id, $salesOrderBatchReceive) {
                if ($salesOrderBatchReceive->salesOrder->customer_id != $item['customer_id']) {
                    throw new \Exception('匹配错误');
                }
                if ($item['match_amount']) {    //从这里开始写
                    $receive = Receive::with('salesOrderBatchReceives')->findOrFail($item['id']);
                    $matched_amount = $receive->salesOrderBatchReceives->sum('amount'); //已关联金额
                    $over_amount = bigNumber($receive->amount)->subtract($matched_amount)->getValue(); //未关联金

                    if ($item['match_amount'] > $over_amount) {
                        throw new \Exception('可关联金额不足');
                    }


                    SalesOrderBatchReceive::insert([
                        'receive_id'           => $receive->id,
                        'sales_order_batch_id' => $id,
                        'amount'               => $item['match_amount'],
                    ]);
                }
            });
        } catch (\Exception $exception) {
            return $this->setStatusCode(422)->responseError($exception->getMessage());
        }

        return $this->responseSuccess('匹配成功');
    }


    public function deleteAssociatedReceive($id)
    {
        SalesOrderBatchReceive::destroy($id);
        return $this->responseSuccess('撤销成功');
    }


    /**
     * 收款序列关联收款水单
     */
    public function associatedWaterBill($id)
    {
        $receivePaymentBatch = ReceivePaymentBatch::with('receivePaymentBatchReceives.receive', 'salesOrder')->findOrFail($id);

        $receives = Receive::with('receivePaymentBatchReceives')->where('customer_id', $receivePaymentBatch->salesOrder->customer_id)->get();   //查询某个客户的所有水单

        $receives = $receives->map(function ($item) { //过滤已经匹配完成的水单
            $matched = $item->receivePaymentBatchReceives->sum('amount');
            $over_amount = bigNumber($item->amount)->subtract($matched)->getValue();

            return [
                'id'                 => $item->id,
                'customer_id'        => $item->customer_id,
                'amount'             => $item->amount, //水单总金额
                'receive_payment_at' => $item->receive_payment_at,//收款时间
                'currency'           => $item->currency,//币种
                'bank_receipt'       => $item->bank_receipt,//银行水单
                'remark'             => $item->remark,//备注
                'over_amount'        => $over_amount, //待匹配金额
                'match_amount'       => '', //匹配金额
                'is_used'            => $matched == $item->amount ?? false, //如果是false 则表示该发票还未完全被匹配
            ];
        });


        return response()->json([
            'data' => [
                'id'                                    => $receivePaymentBatch->id,
                'amount'                                => $receivePaymentBatch->amount,
                'receive_payment_at'                    => $receivePaymentBatch->receive_payment_at,
                'comment'                               => $receivePaymentBatch->comment,
                'receives'                              => $receives->where('is_used', false)->values(),
                'receive_payment_batch_receives'        => $receivePaymentBatch->receivePaymentBatchReceives,
                'receive_payment_batch_receives_amount' => $receivePaymentBatch->receivePaymentBatchReceives->sum('amount'),
            ],
        ]);
    }

    /**
     * 收款序列关联收款水单
     */
    public function saveAssociatedWaterBill(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            '*.match_amount' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return $this->setStatusCode(422)->responseError('关联金额必须为数值');
        }

        $receivePaymentBatch = ReceivePaymentBatch::with('receivePaymentBatchReceives', 'salesOrder')->findOrFail($id);

        if ($receivePaymentBatch->receivePaymentBatchReceives->sum('amount') >= $receivePaymentBatch->amount) {
            return $this->setStatusCode(422)->responseError('该批次金额已完成匹配');
        }

        $over_amount = bigNumber($receivePaymentBatch->amount)->subtract($receivePaymentBatch->receivePaymentBatchReceives->sum('amount'))->getValue();//剩余可匹配金额

        if (collect($request->all())->sum('match_amount') <= 0) {
            return $this->setStatusCode(422)->responseError('匹配金额必须大于0');
        }

        if (collect($request->all())->sum('match_amount') > $over_amount) {
            return $this->setStatusCode(422)->responseError('本次匹配金额已超额');
        }

        try {
            collect($request->all())->map(function ($item) use ($over_amount, $id, $receivePaymentBatch) {
                if ($receivePaymentBatch->salesOrder->customer_id != $item['customer_id']) {
                    throw new \Exception('匹配错误');
                }
                if ($item['match_amount']) {    //从这里开始写
                    $receive = Receive::with('receivePaymentBatchReceives')->findOrFail($item['id']);
                    $matched_amount = $receive->receivePaymentBatchReceives->sum('amount'); //已关联金额
                    $over_amount = bigNumber($receive->amount)->subtract($matched_amount)->getValue(); //未关联金

                    if ($item['match_amount'] > $over_amount) {
                        throw new \Exception('可关联金额不足');
                    }

                    ReceivePaymentBatchReceive::insert([
                        'receive_id'               => $receive->id,
                        'receive_payment_batch_id' => $id,
                        'amount'                   => $item['match_amount'],
                    ]);
                }
            });
        } catch (\Exception $exception) {
            return $this->setStatusCode(422)->responseError($exception->getMessage());
        }

        return $this->responseSuccess('匹配成功');
    }

    public function deleteAssociatedWaterBill($id)
    {
        ReceivePaymentBatchReceive::destroy($id);

        return $this->responseSuccess('撤销成功');
    }
}
