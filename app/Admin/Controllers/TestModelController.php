<?php

namespace App\Admin\Controllers;

use Storage;
use File;
use App\Models\TestModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class TestModelController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'TestModel';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new TestModel());

        $grid->column('id', __('Id'));
        $grid->column('year', __('Year'));
        $grid->column('dir', __('Dir'))->display(function ($dir){
            return "<span title='{$this->path}'>{$dir}</span>";
        });
//        $grid->column('path', __('Path'));
        $grid->column('buu', __('Buu'))->sortable();
        $grid->column('matched', __('matched'))->sortable();
//        $grid->column('line_goods_value_amount', __('line_goods_value_amount'))->sortable();
//        $grid->column('line_duty_amount', __('line_duty_amount'))->sortable();
//        $grid->column('line_mpf_amount', __('line_mpf_amount'))->sortable();
//        $grid->column('line_hmf_amount', __('line_hmf_amount'))->sortable();
//        $grid->column('remarks', __('Remarks'));
//        $grid->column('details', __('Details'))->view('content');
//        $grid->column('image', __('Image'))->display(function (){
//            $data = [];
//            for ($i=1; $i>=1; $i++){
//                $path = '/files/'.$this->dir.'/'.$this->id.'_'.$i.'.jpg';
//                if(File::exists(public_path($path))){
//                    $data[] = asset($path);
//                }else{
//                    break;
//                }
//            }
//
//            return $data;
//        })->image('', '', 800);
        $grid->column('pdf', __('PDF'))->display(function (){
//            $path = preg_replace(['/[\x7f]/',], '', $this->path);
            $url = asset('files/'.$this->path);

//            if($this->id == 6144){
//
//                dd($this->path, preg_replace(['/[\x7f]/',], '', $this->path));
//                dd("<iframe src='{$url}' width='800' height='800'></iframe>");
//            }

            return "<iframe src='{$url}' width='800' height='800'></iframe>";
        });
//        $grid->column('created_at', __('Created at'));
//        $grid->column('updated_at', __('Updated at'));




        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->like('buu', '编号');
            $filter->like('details', '文本搜索');
            $filter->where(function ($query) {
                switch ($this->input) {
                    case 'yes':
//                        $query->unique('buu');
                        break;
                    case 'no':
                        $query->distinct('buu');
                        break;
                }
            }, 'buu', '编号去重复')->radio([
                '' => 'All',
                'yes' => '显示重复',
                'no' => '不显示重复',
            ]);

        });

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
        $show = new Show(TestModel::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('year', __('Year'));
        $show->field('dir', __('Dir'));
        $show->field('path', __('Path'));
        $show->field('buu', __('Buu'));
        $show->field('remarks', __('Remarks'));
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
        $form = new Form(new TestModel());

        $form->text('year', __('Year'));
        $form->text('dir', __('Dir'));
        $form->text('path', __('Path'));
        $form->text('buu', __('Buu'));
        $form->text('remarks', __('Remarks'));

        return $form;
    }
}
