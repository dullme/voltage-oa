<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExtenderSheet implements FromArray, WithTitle, WithStyles, WithColumnWidths
{
    protected $sheetName;
    protected $itemList; //产品列表
    protected $sheets; //所有sheets表数据
    protected $exportDate = [];//导出的数据体
    protected $endColumn = 'A';
    protected $row = 0;//总行数

    public function __construct(String $sheetName, array $data)
    {
        $this->sheetName = $sheetName;
        $this->sheets = $data['sheets'];
        $this->itemList = $data['itemList'];
    }

    public function array(): array
    {
        for ($i= 0;$i<count($this->itemList); $i++){
            $itemListUnit[] = 'pcs';
        }

        $title = [
            array_merge([
                'Pallet NO.',
                'Item Description',
                'Block NO.',
                'CBX NO.',
                'Reel/Box Size',
                '',
                '',
                'Reel/Box QTY',
                'Pallet Size',
                '',
                '',
                'CBM',
                'Net weight',
                'Gross weight',
                'Gross weight（+Pallet）',
            ], $this->itemList),
            array_merge([
                '',
                '',
                '',
                '',
                'Flange / length        mm',
                'Wide       mm',
                'Hight    mm',
                '',
                'Length        mm',
                'Wide        mm',
                'Hight   mm',
                '',
                'Kg',
                'Kg',
                'Kg',
            ], $itemListUnit),
        ];

        $this->makeDate();//处理成需要导出的数据

        array_unshift($this->exportDate, $title[1]);
        array_unshift($this->exportDate, $title[0]);

        $this->endColumn = int2Excel(count($title[0])-1);

        $this->row += count($this->exportDate);

        return $this->exportDate;
    }

    public function title(): string
    {
        return $this->sheetName;
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:'.$this->endColumn.'2')->getFont()->setBold(true);//设置前2行字体加粗
        $sheet->mergeCells('A1:A2');//合并单元格
        $sheet->mergeCells('B1:B2');//合并单元格
        $sheet->mergeCells('C1:C2');//合并单元格
        $sheet->mergeCells('D1:D2');//合并单元格
        $sheet->mergeCells('E1:G1');//合并单元格
        $sheet->mergeCells('H1:H2');//合并单元格
        $sheet->mergeCells('I1:K1');//合并单元格
        $sheet->mergeCells('L1:L2');//合并单元格
        $sheet->getRowDimension(1)->setRowHeight(60);//设置第一行行高
        $sheet->getRowDimension(2)->setRowHeight(60);//设置第二行行高

        $this->setOtherRowHeight($sheet, 3, 30);

        $sheet->getStyle('A1:'.$this->endColumn.$this->row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)->setHorizontal(Alignment::HORIZONTAL_CENTER);//第一列垂直水平剧中
//
//        $cbx = '01';
//        $startColumn = 3; //起始行 默认第三行
//        $endColumn = 3; //结束行 默认第三行
//        foreach ($this->data as $key=>$data){
//            if($key > 0){ //排除第一行
//                if($data['cbx'] == $cbx){ // 如果CBX 和 上一个CBX 相同则需要合并当前行
//                    $endColumn = $key + 2;
//                    //单独处理最后一行
//                    if(($key+1) == count($this->data)){
//                        if($endColumn - $startColumn > 0){ //起始行和结束行 > 1 就意味着跨行需要合并
//                            $sheet->mergeCells('A'.$startColumn.':A'.$endColumn);
//                        }
//                    }
//                }else{
//                    if($endColumn - $startColumn > 0){ //起始行和结束行 > 1 就意味着跨行需要合并
//                        $sheet->mergeCells('A'.$startColumn.':A'.$endColumn);
//                        $startColumn = $endColumn + 1;
//                    }
//                    $cbx = $data['cbx'];
//                }
//            }
//        }
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 15,
            'C' => 10,
            'D' => 10,
            'E' => 10,
            'F' => 10,
            'G' => 10,
            'H' => 15,
            'I' => 10,
            'J' => 10,
            'K' => 10,
            'L' => 10,
            'M' => 10,
            'N' => 10,
            'O' => 10,
        ];
    }

    public function makeDate()
    {
        foreach ($this->sheets as $sheet){
            foreach ($sheet['sheetData'] as $cbx){
                $rowItemCount = [];
                foreach ($this->itemList as $key=>$item){
                    $rowItemCount[$key] = isset($cbx['byCBXSplittedItems'][$item]) ? $cbx['byCBXSplittedItems'][$item] : '';
                }
                $this->exportDate[] = array_merge([
                    '',
                    '',
                    $cbx['inv'],
                    'CBX-'.$cbx['cbx'],
                    '-',
                    '-',
                    '-',
                    '-',
                    '-',
                    '-',
                    '',
                    '',
                    '-',
                    '-',
                    '',
                ],$rowItemCount);
            }
        }

    }

    public function setOtherRowHeight($sheet, $startRow, $rowHeight)
    {
        for($i = $startRow; $i <= $this->row; $i++){
            $sheet->getRowDimension($i)->setRowHeight($rowHeight);//设置第一行行高
        }
    }
}
