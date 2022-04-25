<?php

namespace App\Exports;

use App\Exports\Sheets\ExtenderSheet;
use App\Exports\Sheets\WhipSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class WhipExport implements WithMultipleSheets
{
    use Exportable;
    protected $exportSheetName = 'sheet'; //导出文件的Sheet 名称
    protected $data;
    protected $itemIncludeHarness;//Item列表是否需要包含harness
    protected $splitStr;//Item 拆分符号

    public function __construct(array $data, $itemIncludeHarness, $splitStr)
    {
        $this->data = $data;
        $this->itemIncludeHarness = $itemIncludeHarness;
        $this->splitStr = $splitStr;
    }

    public function sheets(): array
    {
        $itemList = [];

        //根据拆分标识拆分产品
        foreach ($this->data['itemList'] as $item){
            $products = explode($this->splitStr, $item);
            foreach ($products as $product){
                if(isset($product) && $product !=''){
                    $itemList[] = $product;
                }
            }
        }

        $this->data['itemList'] = array_unique($itemList); //去除重复的item值

        if($this->itemIncludeHarness){ //如果包含Harness 就需要把Harness 放到产品列表里面去
            $harnessList = collect($this->data['sheets'])->pluck('thisSheetIncludeHarnessList')->flatten(1)->unique()->whereNotNull()->toArray();
            $this->data['itemList'] = array_unique(array_merge($harnessList, $itemList)); //合并后去除重复Item
        }
        $sheets[] =  new WhipSheet($this->exportSheetName, $this->data);

        return $sheets;
    }
}
