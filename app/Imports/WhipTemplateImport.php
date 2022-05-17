<?php

namespace App\Imports;

use App\Exports\ExtenderExport;
use App\Exports\LabelTemplateExport;
use App\Exports\WhipExport;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\BeforeSheet;
use Excel;

class WhipTemplateImport implements ToCollection,WithEvents
{
    public $sheetNames;
    public $sheetData;
    public $resultData = [];//每次处理后的数据
    public $data = [
        'itemList' => [], //产品列表
        'sheets' => [], //产品列表
        'itemFirstKey' => '',//产品开始位置
        'itemEndKey' => ''//产品结束位置
    ]; //处理后的总数据

    private $itemIncludeHarness = false;//Item列表是否需要包含harness
    private $splitStr = '/';//Item 拆分符号
    private $filterStr = '-';//Item 过滤符号
    private $sheetTemplateName = '模板';//定位item等基本信息的模板
    private $blockPrefixName = 'INV';//Block 前缀
    private $sheetStartRow = 6;//每个 sheet 从第几行开始为数据体
    private $CBXColumn = 5;//CBX编号所在的列
    private $typicalColumn = 6;//Typical编号所在的列
    private $harnessColumn = 9;//Harness编号所在的列
    private $itemIndexRow = 4;//item 编号所在行
    private $itemNameRow = 5;//item 名称所在行

    public function __construct()
    {

    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $this->sheetData[] = $collection;
    }

    public function registerEvents() : array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $this->sheetNames[] = $event->getSheet()->getDelegate()->getTitle();
            },
            AfterImport::class => function () {
                $this->getItemInfo();//获取item信息
                foreach ($this->sheetNames as $sheetKey => $sheet) {
                    if ($this->includeBlockPrefixName($sheet)) { //如果 包含Block 前缀 的需要处理这个 sheet
                        $current_cbx = '01'; //当前CBX
                        $current_typical = ''; //当前Typical
                        if ($this->sheetToBeProcessed($sheetKey)) { //确保第四行有值，不然判断该 Sheet 无效
                            foreach ($this->sheetData[$sheetKey] as $key => $excelRow) { //循环这个Sheet表的每一行
                                if ($key >= $this->sheetStartRow) { //从开始行开始处理数据
                                    $current_cbx = is_null($excelRow[$this->CBXColumn]) ? $current_cbx : $excelRow[$this->CBXColumn]; //如果当前 CBX 没有值就取上一次的值
                                    $current_typical = is_null($excelRow[$this->typicalColumn]) ? $current_typical : $excelRow[$this->typicalColumn]; //如果当前 Typical 没有值就取上一次的值
                                    $this->resultData[$sheetKey][] = [
                                        'cbx'          => $current_cbx,
                                        'typical'      => $current_typical,
                                        'harness'       => $excelRow[$this->harnessColumn],//Harness 产品信息
                                        'products'      => $this->getWhipRowItems($excelRow)//获取每一行对应的产品真实名称
                                    ];
                                }
                            }
                            if (isset($this->resultData[$sheetKey])) { //如果从excel中读取到了信息
                                $this->resultData[$sheetKey] = collect($this->resultData[$sheetKey])->groupBy('cbx')->map(function ($cbxs, $key) use ($sheet) { //根据CBX排序统计
                                    $byCBXSplittedItems = [];//拆分后的Items信息
                                    foreach ($cbxs as $cbx){
                                        if($this->itemIncludeHarness && $cbx['harness']){//如果需要包含harness
                                            $byCBXSplittedItems[] = $cbx['harness'];
                                        }
                                        foreach ($cbx['products'] as $product){
                                            $byCBXSplittedItems[] = $product;
                                        }
                                    }

                                    return [
                                        'inv' => $sheet,
                                        'cbx' => $key,
                                        'byCBXSplittedItems' => array_count_values($byCBXSplittedItems),
                                        'thisCBXIncludeHarnessList' => $cbxs->pluck('harness')->unique()->whereNotNull()->values()->toArray()
                                    ];
                                });

                                $this->data['sheets'][] = [
                                    'sheetName' => $sheet,
                                    'thisSheetIncludeHarnessList' => $this->resultData[$sheetKey]->pluck('thisCBXIncludeHarnessList')->flatten(1)->unique()->whereNotNull()->values()->toArray(),
                                    'sheetData' => $this->resultData[$sheetKey]->toArray(),
                                ];
                            }

                        }
                    }
                }

                /**
                 * -------------------------------------------
                 * Whip最后处理完数据需要拆分item为Pos 和 Neg
                 * -------------------------------------------
                 */
                $splittedItemList = [];
                foreach ($this->data['itemList'] as $itemList){
                    if(is_null($itemList)){
                        $splittedItemList[] = $itemList;
                    }else{
                        $splittedItemList[] = $itemList.'-Pos';
                        $splittedItemList[] = $itemList.'-Neg';
                    }
                }

                $this->data['itemList'] = $splittedItemList;
                /**
                 * -------------------------------------------
                 * Whip最后处理完数据需要拆分item为Pos 和 Neg
                 * -------------------------------------------
                 */

                Excel::store(
                    new WhipExport($this->data, $this->itemIncludeHarness, $this->splitStr),
                    "Whip-".date('Y-m-dHis', time()).".xlsx"   //导出的文件名
                );

            }
        ];
    }

    /**
     * 是否包含前缀 Excel sheet name
     * @param $sheetKey
     * @return false|string
     */
    private function includeBlockPrefixName($sheetKey)
    {
        return strstr(strtoupper($sheetKey), $this->blockPrefixName);
    }

    /**
     * 判断这个sheet表是否需要处理
     * @param $sheetKey
     * @return bool
     */
    private function sheetToBeProcessed($sheetKey)
    {
        return isset($this->sheetData[$sheetKey][4]);
    }

    /**
     * 获取每一行对应的item信息
     * @param $excelRow
     * @return array
     */
    public function getWhipRowItems($excelRow)
    {
        $items = [];
        for ($i=$this->data['itemFirstKey']; $i<=$this->data['itemEndKey'];$i++ ){
            if($excelRow[$i]){ //如果这一行存在值

                /**
                 * --------------------------------------
                 * Whip的item信息说明
                 * 例如 ：列名为 108inch
                 * 当前行名称为 Cir01-Pos/Neg
                 * 结果：108inch-Pos  108inch-Neg
                 * --------------------------------------
                 */
                $columnItemName = $this->data['itemList'][$i-$this->data['itemFirstKey']]; //当前列的item名称
                $rowItemNames = explode($this->filterStr, $excelRow[$i]);
                $rowItemNames = explode($this->splitStr, end($rowItemNames));
                foreach ($rowItemNames as $itemName){
                    $items[] = $columnItemName . $this->filterStr . $itemName;
                }
                /**
                 * --------------------------------------
                 * 处理Whip的Item结束
                 * --------------------------------------
                 */

            }
        }

        return $items;
    }


    /**
     * 获取 item 信息
     * @throws \Exception
     */
    public function getItemInfo()
    {
        if(in_array($this->sheetTemplateName, $this->sheetNames)){
            foreach ($this->sheetNames as $sheetKey=>$sheet) {
                if($sheet == $this->sheetTemplateName){
                    $this->createItemInfo($sheetKey);//根据模板创建item信息
                }
            }
        }else{
            throw new \Exception('找不到模版');
        }
    }

    /**
     * 根据模板创建item信息
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function createItemInfo($sheetKey)
    {
        $data = $this->sheetData[$sheetKey]->toArray();
        foreach ($data[$this->itemIndexRow] as $key => $findIndex) {
            if ($this->data['itemFirstKey'] == '') { //如果还没找到第一个
                if ($findIndex == 1) {
                    $this->data['itemFirstKey'] = $key;
                }
            } else { //已经找到第一个
                if (is_null($findIndex) || $findIndex == '') {
                    $this->data['itemEndKey'] = $key - 1;
                    break;
                }
            }
        }

        if ($this->data['itemFirstKey'] == '' || $this->data['itemEndKey'] == '') {
            throw new \Exception('没有定位到第一个产品或最后一个产品');
        }

        for ($i=$this->data['itemFirstKey']; $i<=$this->data['itemEndKey'];$i++ ){
            $this->data['itemList'][] = $data[$this->itemNameRow][$i];
        }

        return true;
    }

}
