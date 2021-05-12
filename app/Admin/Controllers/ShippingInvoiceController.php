<?php

namespace App\Admin\Controllers;

use PDF;
use App\Models\PurchaseOrder;
use App\Models\Project;
use App\Models\ShippingInvoice;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ShippingInvoiceController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'ShippingInvoice';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ShippingInvoice());
        $grid->model()->orderByDesc('created_at');

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->like('invoice_no', '发票号码');
            $filter->equal('project.id', '项目名称')->select(Project::pluck('name', 'id'));
        });

        $grid->column('invoice_no', __('发票号码'))->display(function ($invoice_no){
            $url = asset('/admin/shipping-invoices/'.$this->id);
            return "<a href='{$url}'>{$invoice_no}</a>";
        });
        $grid->column('amount', __('发票金额'));
        $grid->project()->name('项目名称');
        $grid->column('shipping', __('货代'));
        $grid->column('info', __('货物信息'));
        $grid->column('invoice_info', __('发票信息'));
        $grid->column('b_l', __('提单号'));
        $grid->column('billing_time', __('开票时间'));

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
        $shipping_invoice = ShippingInvoice::with('project')->findOrFail($id);

        $shipping_invoices = ShippingInvoice::where('b_l', $shipping_invoice->b_l)->get();

        $orders = PurchaseOrder::with('salesOrder')->whereIn('id', collect($shipping_invoice->detail)->pluck('po'))->get();


        $customer_po= implode('/', $orders->pluck('salesOrder')->pluck('customer_po')->unique()->toArray());

        return view('admin/shipping_invoice', compact('shipping_invoice', 'orders', 'shipping_invoices', 'customer_po'));
    }

    protected function printDetail($id)
    {
        $shipping_invoice = ShippingInvoice::with('project')->findOrFail($id);

        $shipping_invoices = ShippingInvoice::where('b_l', $shipping_invoice->b_l)->get();

        $orders = PurchaseOrder::with('salesOrder')->whereIn('id', collect($shipping_invoice->detail)->pluck('po'))->get();


        $customer_po= implode('/', $orders->pluck('salesOrder')->pluck('customer_po')->unique()->toArray());


        $pdf = PDF::loadView('admin.print_shipping_invoice', compact('shipping_invoice', 'orders', 'shipping_invoices', 'customer_po'));
        return $pdf->inline();
//        return $pdf->download($shipping_invoice->invoice_no.'shipping_invoices.pdf');

//        return view('admin.print_shipping_invoice', compact('shipping_invoice', 'orders', 'shipping_invoices', 'customer_po'));
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new ShippingInvoice());
        $form->select('project_id', __('项目'))->options(Project::pluck('name', 'id'))->required();
        $form->text('invoice_no', __('发票号码'))->creationRules(['required', "unique:shipping_invoices"])
            ->updateRules(['required', "unique:shipping_invoices,invoice_no,{{id}}"]);
        $form->decimal('amount', __('发票金额'))->required();
        $form->text('shipping', __('货代'))->required();
        $form->text('info', __('货物信息'))->required();
        $form->text('batch', __('批次'))->required();
        $form->text('invoice_info', __('发票信息'))->required();
        $form->text('b_l', __('提单号'))->required();
        $form->date('billing_time','开票时间')->required();
        $form->date('delivery_time','出货时间')->required();


        $form->table('detail', '详情', function ($table) {

            $orders = PurchaseOrder::with('vendor')->orderBy('id', 'DESC')->get();
            $orders = $orders->map(function ($item){
                return [
                    'id' => $item->id,
                    'po' => $item->po .' / ' .$item->vendor->name . ' / '. $item->amount,
                ];
            });
            $table->select('po', '工厂PO / 工厂 / PO总金额')->options($orders->pluck('po', 'id'))->rules('required');
            $table->decimal('amount', '货值')->rules('required');
        });
        $form->file('invoice_image', __('发票凭证'))->required()->removable();
        $form->multipleFile('file', __('其他凭证'))->sortable()->removable();
        $form->textarea('comment', __('备注'));

        $form->saving(function (Form $form) {

            if(is_null($form->detail)){
//                admin_toastr('Message...', 'success', ['timeOut' => 5000]);
                throw new \Exception('详情未填写');
            }

        });

        return $form;
    }
}
