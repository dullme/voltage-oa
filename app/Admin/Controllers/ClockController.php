<?php

namespace App\Admin\Controllers;

use App\Models\Clock;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ClockController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Clock';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Clock());

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->like('name', 'Name');
            $filter->like('display', 'Display');
            $filter->like('remark', 'Remark');
        });

//        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('display', __('Display'));
        $grid->column('time_zone', __('Time zone'));
        $grid->column('remark', __('Remark'));
        $grid->column('star', __('Star'))->switch();
//        $grid->column('created_at', __('Created at'));
//        $grid->column('updated_at', __('Updated at'));

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
        $show = new Show(Clock::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('time_zone', __('Time zone'));
        $show->field('remark', __('Remark'));
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
        $form = new Form(new Clock());

        $form->text('name', __('Name'));
        $form->number('time_zone', __('Time zone'));
        $form->text('display', __('Display'));
        $form->text('remark', __('Remark'));
        $form->switch('star', __('Star'));

        return $form;
    }
}
