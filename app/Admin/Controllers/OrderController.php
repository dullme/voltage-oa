<?php

namespace App\Admin\Controllers;

use App\Models\Order;
use App\Models\Project;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class OrderController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Order';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Order());

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->equal('project_id', '项目编号')->select(Project::pluck('name', 'id'));
        });

        $grid->column('项目名称')->display(function (){
            return $this->project->name;
        });
        $grid->column('项目编号')->display(function (){
            return $this->project->no;
        });

        $grid->column('vendor', __('工厂'));
        $grid->column('po', __('工厂PO'));
        $grid->column('amount', __('工厂PO总额'));
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
        $show = new Show(Order::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('vendor', __('Vendor'));
        $show->field('po', __('Po'));
        $show->field('amount', __('Amount'));
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
        $form = new Form(new Order());

        $form->select('project_id', __('项目'))->options(Project::pluck('name', 'id'))->required();
        $form->text('vendor', __('工厂'))->required();
        $form->text('po', __('工厂PO'))->required();
        $form->decimal('amount', __('工厂PO总额'))->required();

        return $form;
    }
}
