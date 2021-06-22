<?php

namespace App\Admin\Controllers;

use App\Models\Project;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ProjectController extends BaseController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '项目管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Project());
        $grid->model()->orderByDesc('no');

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->like('no', '项目编号');
            $filter->like('name', '项目名称');
        });

        $grid->column('no', __('项目编号'))->display(function ($no){
            $url = url('/admin/projects/'.$this->id);
            return "<a href='{$url}'>$no</a>";
        });
        $grid->column('name', __('项目名称'));
        $grid->column('type', __('类型'))->display(function ($item){
            return $item;
        })->label([
            'HARNESS' => 'success',
            'PV'      => 'danger',
            'MV'      => 'warning',
            'OTHER'   => 'info',
        ]);

        $grid->salesOrders(__('销售订单'))->display(function ($salesOrders){
            $nos = collect($salesOrders)->pluck('no')->toArray();
            $res = '';
            foreach ($nos as $no){
                $res .= "<span class='label label-default' style='margin-right: 2px'>{$no}</span>";
            }
            return $res;
        });

        $grid->column('remark', __('备注'));
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
        $project = Project::with(['salesOrders'=>function($query){
            $query->with(['salesOrderBatches', 'receivePaymentBatches', 'purchaseOrders' => function($query){
                $query->with('vendor', 'receiptBatches', 'paymentBatches');
            }]);
        }])->findOrFail($id);

        $salesOrders = $project->salesOrders->map(function ($item){
            $item['order_progress'] = getOrderProgress($item->vendors, $item->purchaseOrders->pluck('vendor_id')->toArray()); //下单进度
            $item['shipped_progress'] = getDeliveryProgress($item->salesOrderBatches->sum('amount'), $item->amount); //发货进度
            $item['received_progress'] = getReceivedProgress($item->receivePaymentBatches->sum('amount'), $item->amount); //收款进度

            return $item;
        });

        $project->setAttribute('sales_orders', $salesOrders);

        return view('admin/project',compact('project'));
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Project());

        $form->text('no', __('项目编号'))->creationRules(['required', "unique:projects"])
            ->updateRules(['required', "unique:projects,no,{{id}}"]);
        $form->text('name', __('项目名称'))->required();
        $form->multipleSelect('type', __('类型'))->options(['MV'=>'MV','PV'=>'PV', 'HARNESS'=>'HARNESS', 'OTHER'=>'OTHER']);
        $form->text('remark', __('备注'));

        return $form;
    }
}
