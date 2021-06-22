<?php


namespace App\Admin\Controllers;


use Encore\Admin\Controllers\AdminController;

class BaseController extends AdminController
{
    public function destroy($id)
    {
        return admin_toastr('您没有权限删除', 'error');

        return $this->form()->destroy($id);
    }
}
