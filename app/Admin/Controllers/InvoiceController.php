<?php

namespace App\Admin\Controllers;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\Project;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class InvoiceController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Invoice';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Invoice());
        $grid->model()->orderByDesc('created_at');

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->like('invoice_no', '发票号码');
            $filter->equal('order.project.id', '项目名称')->select(Project::pluck('name', 'id'));
            $filter->equal('order.id', '工厂PO')->select(Order::pluck('po', 'id'));
        });


        $grid->column('invoice_no', __('发票号码'))->display(function (){
            $url = asset('/admin/invoices/'.$this->id);
            return "<a href='$url'>$this->invoice_no</a>";
        });
        $grid->column('title', __('标题'));
        $grid->column('工厂')->display(function (){
            return $this->order->vendor;
        });
        $grid->column('工厂PO')->display(function (){
            return $this->order->po;
        });

        $grid->column('项目名称')->display(function (){
            return $this->order->project->name;
        });
        $grid->column('项目编号')->display(function (){
            return $this->order->project->no;
        });

        $grid->column('billing_time', __('开票时间'));
//        $grid->column('created_at', __('创建时间'));

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
        $invoice = Invoice::with('order.project')->findOrFail($id);
//        return $show;
        $invoices = Invoice::where('order_id', $invoice->order_id)->get();
        $invoice_total_amount = $invoices->sum('amount');
        $invoices = $invoices->groupBy('serial');

//        dd($invoices->toArray());

        return view('admin/invoice', compact('invoice', 'invoices', 'invoice_total_amount'));
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Invoice());
        $form->select('order_id', __('工厂PO'))->options(Order::pluck('po', 'id'))->required();
        $form->number('serial', __('序号'))->rules('required|numeric|min:0|max:100')->default('');
        $form->text('title', __('标题'));
        $form->text('invoice_no', __('发票号码'))->creationRules(['required', "unique:invoices"])
            ->updateRules(['required', "unique:invoices,invoice_no,{{id}}"]);
        $form->decimal('amount', __('发票金额'))->required();
        $form->date('billing_time', __('开票时间'))->required();
        $form->file('invoice_image', __('发票凭证'))->required()->removable();
        $form->multipleFile('file', __('其他凭证'))->sortable()->removable();
        $form->textarea('comment', __('备注'));
        return $form;
    }
}
