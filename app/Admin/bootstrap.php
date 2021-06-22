<?php
use Encore\Admin\Grid;
use Encore\Admin\Form;

/**
 * Laravel-admin - admin builder based on Laravel.
 * @author z-song <https://github.com/z-song>
 *
 * Bootstraper for Admin.
 *
 * Here you can remove builtin form field:
 * Encore\Admin\Form::forget(['map', 'editor']);
 *
 * Or extend custom form field:
 * Encore\Admin\Form::extend('php', PHPEditor::class);
 *
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 *
 */

Encore\Admin\Form::forget(['map', 'editor']);
Encore\Admin\Admin::js('/js/app.js');

Grid::init(function (Grid $grid){
//    $grid->disableColumnSelector();
    $grid->disableRowSelector();
    $grid->disableExport(); //禁止导出

    $grid->actions(function (Grid\Displayers\Actions $actions) {
//        $actions->disableView(); //禁止行级查看路由
//        $actions->disableEdit(); //禁止行级编辑路由
        $actions->disableDelete(); //禁止行级删除路由
    });

});

Form::init(function (Form $form){
    $form->tools(function (Form\Tools $tools) {
        // 去掉`列表`按钮
//        $tools->disableList();
        // 去掉`删除`按钮
        $tools->disableDelete();
        // 去掉`查看`按钮
//        $tools->disableView();
    });
});
