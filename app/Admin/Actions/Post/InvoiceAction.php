<?php

namespace App\Admin\Actions\Post;

use Carbon\Carbon;
use Encore\Admin\Actions\RowAction;
use Encore\Admin\Facades\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class InvoiceAction extends RowAction
{
    public $name = '签收发票';

    public function handle(Model $model, Request $request)
    {
        if(Admin::user()->cannot('invoice.submission')){ //如果没有签收发票权限
            return $this->response()->error('您没有权限签收发票');
        }

        $model->status = 1;
        $model->submission_at = $request->get('submission_at');
        $model->comment = $request->get('comment');
        $model->save();

        return $this->response()->success('发票号 '.$model->invoice_no.' 签收成功')->refresh();
    }

    public function form()
    {
        $this->datetime('submission_at', '签收时间')->default(Carbon::now())->rules('required');
        $this->textarea('comment', '备注');
    }
}
