<?php

namespace App\Admin\Controllers;

use App\Models\Customer;
use App\Models\Receive;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ReceiveController extends AdminController
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
        $form->select('currency', __('币种'))->options(['USD'=>'USD', 'CNY'=>'CNY'])->default('USD')->required();
        $form->decimal('amount', __('金额'))->required();
        $form->date('receive_payment_at', __('收款时间'))->default(date('Y-m-d'))->required();
        $form->textarea('remark', __('备注'));

        return $form;
    }
}
