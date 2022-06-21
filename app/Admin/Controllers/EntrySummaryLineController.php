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

        $grid->header(function ($query) {

            $data = $query->get();

            $total['total_line_goods_value_amount'] = $data->sum('line_goods_value_amount');
            $total['total_line_duty_amount'] = $data->sum('line_duty_amount');
            $total['total_line_mpf_amount'] = $data->sum('line_mpf_amount');
            $total['total_line_hmf_amount'] = $data->sum('line_hmf_amount');
            $total['total_line_goods_value_amount2'] = $data->sum('line_goods_value_amount2');
            $total['total_line_duty_amount2'] = $data->sum('line_duty_amount2');
            $total['total_line_mpf_amount2'] = $data->sum('line_mpf_amount2');
            $total['total_line_hmf_amount2'] = $data->sum('line_hmf_amount2');



            $total['df_total_line_duty_amount'] = bigNumber($total['total_line_duty_amount2'])->subtract($total['total_line_duty_amount'])->getValue();
            $total['df_total_line_mpf_amount'] = bigNumber($total['total_line_mpf_amount2'])->subtract($total['total_line_mpf_amount'])->getValue();
            $total['df_total_line_hmf_amount'] = bigNumber($total['total_line_hmf_amount2'])->subtract($total['total_line_hmf_amount'])->getValue();

            $total['total_hyf'] = $data->sum('hyf');
            $total['total_gs'] = $data->sum('gs');
            $total['total_nlyf'] = $data->sum('nlyf');

            $total['wf_total_hyf'] = $data->where('sfzf_hyf', false)->sum('hyf');
            $total['wf_total_gs'] = $data->where('sfzf_gs', false)->sum('gs');
            $total['wf_total_nlyf'] = $data->where('sfzf_nlyf', false)->sum('nlyf');

            $total['tsje'] = $data->where('sfxyts', 1)->sum('tsje');

            return view('total', compact('total'));
        });


        $grid->column('id', __('Id'))->sortable();
//        $grid->column('year', __('Year'));
//        $grid->column('dir', __('Dir'));
//        $grid->column('buu', __('Buu'));

        $grid->column('b_l', __('B/L'))->editable()->sortable();
        $grid->column('entry_summary_number', __('No.'))->sortable();
//        $grid->column('entry_summary_number', __('B/L & No.'))->display(function ($entry_summary_number){
//            $bl = '<p>'.$this->b_l.'</p>';
//
//            if($entry_summary_number != $this->buu){
//                return $bl.'<p>'.$this->buu.' / ' . $entry_summary_number.'</p>';
//            }
//
//            return $bl.'<p>'.$entry_summary_number.'</p>';
//        });
//        $grid->column('entry_type_code', __('Entry type code'));
//        $grid->column('entry_summary_line_number', __('Entry summary line number'));
//        $grid->column('review_team_number', __('Review team number'));
//        $grid->column('country_of_origin_code', __('Country of origin code'));
//        $grid->column('country_of_export_code', __('Country of export code'));
//        $grid->column('manufacturer_id', __('Manufacturer id'));
//        $grid->column('manufacturer_name', __('Manufacturer name'));
//        $grid->column('foreign_exporter_id', __('Foreign exporter id'));
//        $grid->column('foreign_exporter_name', __('Foreign exporter name'));
//        $grid->column('line_spi_code', __('Line spi code'));
//        $grid->column('line_spi', __('Line spi'));
//        $grid->column('reconciliation_fta_status', __('Reconciliation fta status'));
//        $grid->column('reconciliation_other_status', __('Reconciliation other status'));

        $grid->column('line_goods_value_amount', __('已申报货值'))->sortable();
        $grid->column('line_goods_value_amount2', __('实际申报货值'))->editable()->sortable();
        $grid->column('line_goods_value_amount3', __('未申报货值'))->display(function (){
            return bigNumber($this->line_goods_value_amount2)->subtract($this->line_goods_value_amount)->getValue();
        });

        $grid->column('amount')->view('test');


//        $grid->column('line_duty_amount', __('Line Duty Amount'))->sortable();
//        $grid->column('line_mpf_amount', __('Line MPF Amount'))->sortable();
//        $grid->column('line_hmf_amount', __('Line HMF Amount'))->sortable();
//
        $grid->column('line_duty_amount2', __('Line Duty Amount2'))->editable()->sortable();
        $grid->column('line_mpf_amount2', __('Line MPF Amount2'))->editable()->sortable();
        $grid->column('line_hmf_amount2', __('Line HMF Amount2'))->editable()->sortable();
//        $grid->column('total', __('Total'))->display(function (){
//            return bigNumber($this->line_duty_amount2)->add($this->line_mpf_amount2)->add($this->line_hmf_amount2)->getValue();
//        });
//        $grid->column('check', __('Check'))->sortable();

        $grid->column('hyf', __('海运费'))->editable()->sortable();
        $states = [
            'on'  => ['value' => 1, 'text' => '已付', 'color' => 'primary'],
            'off' => ['value' => 0, 'text' => '未付', 'color' => 'default'],
        ];

        $grid->column('gs', __('关税'))->editable()->sortable();
        $grid->column('nlyf', __('内陆运费'))->editable()->sortable();
        $grid->column('sfzf_hyf', __('已支付海运费'))->switch($states);
        $grid->column('sfzf_gs', __('已支付关税'))->switch($states);
        $grid->column('sfzf_nlyf', __('已支付内陆运费'))->switch($states);

        $states2 = [
            'on'  => ['value' => 1, 'text' => '退', 'color' => 'primary'],
            'off' => ['value' => 0, 'text' => '不退', 'color' => 'default'],
        ];
        $grid->column('sfxyts', __('是否需要退税'))->switch($states2)->sortable();
        $grid->column('tsje', __('退税金额'))->editable()->sortable();
        $grid->column('hy_daili', __('海运代理'))->editable()->sortable();
        $grid->column('qg_daili', __('清关代理'))->editable()->sortable();
        $grid->column('kcsj', __('开船时间'))->sortable();
        $grid->column('entry_date', __('Entry Date'))->sortable()->width(100);
//        $grid->column('source', __('数据来源'))->sortable();


//        $grid->column('path', __('Path'))->display(function (){
//            if($this->path){
//                $url = asset('pdfs/'.$this->year.'/'.$this->id.'.pdf');
//                return "<iframe title='{$this->path}' src='{$url}' width='700' height='800'></iframe>";
//            }else{
//                return '';
//            }
//
//        });
//        $grid->column('matched', __('Matched'))->sortable();

        $grid->disableActions();

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();

            $filter->where(function ($query) {
                $input = explode(' ', $this->input);
                $query->whereIn('entry_summary_number', $input);
            }, 'Entry summary number');

            $filter->where(function ($query) {
                $input = explode(' ', $this->input);
                $query->whereIn('b_l', $input);
            }, 'B/L');
            $filter->between('entry_date', 'Entry Date')->date();
            $filter->where(function ($query) {
                switch ($this->input) {
                    case 'All':
                        break;
                    case 'yes':
                        $query->where('matched', 1);
                        break;
                    case 'no':
                        $query->where('matched', 0);
                        break;
                }
            }, 'Matched')->radio([
                '' => 'All',
                'yes' => '已匹配',
                'no' => '未匹配',
            ]);

            $filter->where(function ($query) {
                switch ($this->input) {
                    case 'All':
                        break;
                    case 'yes':
                        $query->where('check', 1);
                        break;
                    case 'no':
                        $query->where('check', 0);
                        break;
                }
            }, 'Check')->radio([
                '' => 'All',
                'yes' => '验证成功',
                'no' => '未验证',
            ]);


            $filter->where(function ($query) {
                $query->where('entry_summary_number', 'like', $this->input.'%');
            }, 'Head NO.')->radio([
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

            $filter->where(function ($query) {
                if($this->input == 'All' || $this->input == ''){

                }else{
                    $query->where('hy_daili', $this->input);
                }

            }, '海运代理')->radio(array_merge([''=>'All'], EntrySummaryLine::where('hy_daili', '!=', null)->pluck('hy_daili', 'hy_daili')->unique()->toArray()));

            $filter->where(function ($query) {
                if($this->input == 'All' || $this->input == ''){

                }else{
                    $query->where('qg_daili', $this->input);
                }

            }, '清关代理')->radio(array_merge([''=>'All'], EntrySummaryLine::where('qg_daili', '!=', null)->pluck('qg_daili', 'qg_daili')->unique()->toArray()));




            $filter->where(function ($query) {
                switch ($this->input) {
                    case 'All':
                        break;
                    case 'yes':
                        $query->where('sfzf_hyf', 1);
                        break;
                    case 'no':
                        $query->where('sfzf_hyf', 0);
                        break;
                }
            }, '海运费')->radio([
                '' => 'All',
                'yes' => '已支付海运费',
                'no' => '未支付海运费',
            ]);

            $filter->where(function ($query) {
                switch ($this->input) {
                    case 'All':
                        break;
                    case 'yes':
                        $query->where('sfzf_gs', 1);
                        break;
                    case 'no':
                        $query->where('sfzf_gs', 0);
                        break;
                }
            }, '关税')->radio([
                '' => 'All',
                'yes' => '已支付关税',
                'no' => '未支付关税',
            ]);

            $filter->where(function ($query) {
                switch ($this->input) {
                    case 'All':
                        break;
                    case 'yes':
                        $query->where('sfzf_nlyf', 1);
                        break;
                    case 'no':
                        $query->where('sfzf_nlyf', 0);
                        break;
                }
            }, '内陆运费')->radio([
                '' => 'All',
                'yes' => '已支付内陆运费',
                'no' => '未支付内陆运费',
            ]);

            $filter->where(function ($query) {
                switch ($this->input) {
                    case 'All':
                        break;
                    case 'yes':
                        $query->where('sfxyts', 1);
                        break;
                    case 'no':
                        $query->where('sfxyts', 0);
                        break;
                }
            }, '退税')->radio([
                '' => 'All',
                'yes' => '需要退税',
                'no' => '不需要退税',
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
        $form->text('b_l', __('B/L'));
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


        $states = [
            'on'  => ['value' => 1, 'text' => '已付', 'color' => 'primary'],
            'off' => ['value' => 0, 'text' => '未付', 'color' => 'default'],
        ];

        $form->decimal('hyf', __('hyf'));
        $form->decimal('gs', __('gs'));
        $form->decimal('nlyf', __('nlyf'));
        $form->decimal('tsje', __('tsje'));

        $form->switch('sfzf_hyf', __('sfzf_hyf'))->states($states);
        $form->switch('sfzf_gs', __('sfzf_gs'))->states($states);
        $form->switch('sfzf_nlyf', __('sfzf_nlyf'))->states($states);
        $form->switch('sfxyts', __('sfxyts'))->states();
        $form->text('hy_daili', __('hy_daili'));
        $form->text('qg_daili', __('qg_daili'));

        return $form;
    }
}
