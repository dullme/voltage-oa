<?php

namespace App\Admin\Controllers;

use App\Models\Project;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ProjectController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Project';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Project());

        $grid->column('no', __('项目编号'))->display(function ($no){
            $url = url('/admin/projects/'.$this->id);
            return "<a href='{$url}'>$no</a>";
        });
        $grid->column('name', __('项目名称'));
        $grid->column('created_at', __('创建时间'));

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
            $query->with(['purchaseOrders' => function($query){
                $query->with('vendor', 'receiptBatches', 'paymentBatches');
            }]);
        }])->findOrFail($id);

        $salesOrders = $project->salesOrders->map(function ($item){
            if(count($item->vendors) == 0){
                $item['progress'] = 0;//未设置供应商进度为0
            }else{
                $item['progress'] = 0.5;//设置了供应商清单，下单进度为50%
                $vendor_ids = $item->purchaseOrders->pluck('vendor_id')->toArray();
                $in_array = 0;
                foreach ($item->vendors as $vendor){
                    $in_array = in_array($vendor, $vendor_ids) ? ++$in_array : $in_array;
                }
                if($in_array == count($item->vendors)){ //如果设置的采购计划供应商数量和实际下单的数量相等 则进度为 100%
                    $item['progress'] = 1;
                }else{
                    $item['progress'] += 0.5/count($item->vendors) * $in_array;
                }
            }

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

        return $form;
    }
}
