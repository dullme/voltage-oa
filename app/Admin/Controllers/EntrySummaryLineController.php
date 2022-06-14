<?php

namespace App\Admin\Controllers;

use App\Models\EntrySummaryLine;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class EntrySummaryLineController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'EntrySummaryLine';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new EntrySummaryLine());

        $grid->column('id', __('Id'))->sortable();
//        $grid->column('year', __('Year'));
//        $grid->column('dir', __('Dir'));
//        $grid->column('buu', __('Buu'));

        $grid->column('entry_summary_number', __('Entry summary number'));
//        $grid->column('entry_type_code', __('Entry type code'));
//        $grid->column('entry_summary_line_number', __('Entry summary line number'));
//        $grid->column('review_team_number', __('Review team number'));
//        $grid->column('country_of_origin_code', __('Country of origin code'));
//        $grid->column('country_of_export_code', __('Country of export code'));
//        $grid->column('manufacturer_id', __('Manufacturer id'));
        $grid->column('manufacturer_name', __('Manufacturer name'));
//        $grid->column('foreign_exporter_id', __('Foreign exporter id'));
//        $grid->column('foreign_exporter_name', __('Foreign exporter name'));
//        $grid->column('line_spi_code', __('Line spi code'));
//        $grid->column('line_spi', __('Line spi'));
//        $grid->column('reconciliation_fta_status', __('Reconciliation fta status'));
//        $grid->column('reconciliation_other_status', __('Reconciliation other status'));
        $grid->column('line_goods_value_amount2', __('Line Goods Value Amount2'))->editable();
        $grid->column('line_duty_amount2', __('Line Duty Amount2'))->editable();
        $grid->column('line_mpf_amount2', __('Line MPF Amount2'))->editable();
        $grid->column('line_hmf_amount2', __('Line HMF Amount2'))->editable();
        $grid->column('path', __('Path'))->display(function (){
            if($this->path){
                $url = asset('pdfs/'.$this->year.'/'.$this->id.'.pdf');
                return "<iframe title='{$this->path}' src='{$url}' width='700' height='800'></iframe>";
            }else{
                return '';
            }

        });
        $grid->column('matched', __('Matched'))->sortable();

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->like('entry_summary_number', 'Entry summary number');
            $filter->where(function ($query) {
                switch ($this->input) {
                    case 'yes':
                        $query->where('matched', 1);
                        break;
                    case 'no':
                        $query->where('matched', 0);
                        break;
                }
            }, 'matched', 'Matched')->radio([
                '' => 'All',
                'yes' => '已匹配',
                'no' => '未匹配',
            ]);


            $filter->where(function ($query) {
                $query->where('entry_summary_number', 'like', $this->input.'%');
            }, 'matched', 'Matched')->radio([
                '' => 'All',
                '101' => '101',
                '178' => '178',
                '799' => '799',
                '86P' => '86P',
                '96U' => '96U',
                'ATN' => 'ATN',
                'BQK' => 'BQK',
                'BUU' => 'BUU',
                'DZ1' => 'DZ1',
                'E4Y' => 'E4Y',
                'EE3' => 'EE3',
                'NBF' => 'NBF',
                'NIK' => 'NIK',
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
        $show = new Show(EntrySummaryLine::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('year', __('Year'));
        $show->field('dir', __('Dir'));
        $show->field('path', __('Path'));
        $show->field('buu', __('Buu'));
        $show->field('matched', __('Matched'));
        $show->field('entry_summary_number', __('Entry summary number'));
        $show->field('entry_type_code', __('Entry type code'));
        $show->field('entry_summary_line_number', __('Entry summary line number'));
        $show->field('review_team_number', __('Review team number'));
        $show->field('country_of_origin_code', __('Country of origin code'));
        $show->field('country_of_export_code', __('Country of export code'));
        $show->field('manufacturer_id', __('Manufacturer id'));
        $show->field('manufacturer_name', __('Manufacturer name'));
        $show->field('foreign_exporter_id', __('Foreign exporter id'));
        $show->field('foreign_exporter_name', __('Foreign exporter name'));
        $show->field('line_spi_code', __('Line spi code'));
        $show->field('line_spi', __('Line spi'));
        $show->field('reconciliation_fta_status', __('Reconciliation fta status'));
        $show->field('reconciliation_other_status', __('Reconciliation other status'));
        $show->field('line_goods_value_amount', __('Line goods value amount'));
        $show->field('line_duty_amount', __('Line duty amount'));
        $show->field('line_mpf_amount', __('Line mpf amount'));
        $show->field('line_hmf_amount', __('Line hmf amount'));
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
        $form = new Form(new EntrySummaryLine());

        $form->text('year', __('Year'));
        $form->text('dir', __('Dir'));
        $form->text('path', __('Path'));
        $form->text('buu', __('Buu'));
        $form->switch('matched', __('Matched'));
        $form->text('entry_summary_number', __('Entry summary number'));
        $form->text('entry_type_code', __('Entry type code'));
        $form->text('entry_summary_line_number', __('Entry summary line number'));
        $form->text('review_team_number', __('Review team number'));
        $form->text('country_of_origin_code', __('Country of origin code'));
        $form->text('country_of_export_code', __('Country of export code'));
        $form->text('manufacturer_id', __('Manufacturer id'));
        $form->text('manufacturer_name', __('Manufacturer name'));
        $form->text('foreign_exporter_id', __('Foreign exporter id'));
        $form->text('foreign_exporter_name', __('Foreign exporter name'));
        $form->text('line_spi_code', __('Line spi code'));
        $form->text('line_spi', __('Line spi'));
        $form->text('reconciliation_fta_status', __('Reconciliation fta status'));
        $form->text('reconciliation_other_status', __('Reconciliation other status'));
        $form->decimal('line_goods_value_amount', __('Line goods value amount'));
        $form->decimal('line_duty_amount', __('Line duty amount'));
        $form->decimal('line_mpf_amount', __('Line mpf amount'));
        $form->decimal('line_hmf_amount', __('Line hmf amount'));

        $form->decimal('line_goods_value_amount2', __('Line goods value amount2'));
        $form->decimal('line_duty_amount2', __('Line duty amount2'));
        $form->decimal('line_mpf_amount2', __('Line mpf amount2'));
        $form->decimal('line_hmf_amount2', __('Line hmf amount2'));

        return $form;
    }
}
