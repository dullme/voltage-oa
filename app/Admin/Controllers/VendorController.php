<?php

namespace App\Admin\Controllers;

use App\Models\Vendor;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class VendorController extends BaseController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '供应商管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Vendor());

        $grid->column('no', __('供应商编号'));
        $grid->column('name', __('供应商名称'));

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
        $show = new Show(Vendor::findOrFail($id));

        $show->field('no', __('供应商编号'));
        $show->field('name', __('供应商名称'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Vendor());

        $form->text('no', __('供应商编号'));
        $form->text('name', __('供应商名称'));

        return $form;
    }
}
