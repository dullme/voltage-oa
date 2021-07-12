<?php

namespace App\Admin\Controllers;

use App\Models\Customer;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CustomerController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Customer';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Customer());

        $grid->column('no', __('客户编号'));
        $grid->column('name', __('客户名称'));
        $grid->column('tel', __('电话'));
        $grid->column('address', __('地址'));

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
        $show = new Show(Customer::findOrFail($id));

        $show->field('no', __('客户编号'));
        $show->field('name', __('客户名称'));
        $show->field('tel', __('电话'));
        $show->field('address', __('地址'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Customer());

        $form->text('no', __('客户编号'));
        $form->text('name', __('客户名称'));
        $form->text('tel', __('电话'));
        $form->text('address', __('地址'));

        return $form;
    }
}
