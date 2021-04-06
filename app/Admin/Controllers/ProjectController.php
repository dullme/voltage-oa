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

        $grid->column('no', __('项目编号'));
        $grid->column('name', __('项目名称'));
        $grid->column('customer_po', __('客户PO'));
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
        $show = new Show(Project::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('项目名称'));
        $show->field('no', __('项目编号'));
        $show->field('customer_po', __('客户PO'));
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
        $form = new Form(new Project());

        $form->text('no', __('项目编号'))->creationRules(['required', "unique:projects"])
            ->updateRules(['required', "unique:projects,no,{{id}}"]);
        $form->text('name', __('项目名称'))->required();
        $form->text('customer_po', __('客户PO'));

        return $form;
    }
}
