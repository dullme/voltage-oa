<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class LabelTemplateExport implements FromCollection,WithEvents, WithHeadings
{
    protected $data; //数据体
    protected $resultData; //结果数据体

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->resultData = [];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        foreach ($this->data as $item){
            foreach ($item as $ite){
                foreach ($ite['items'] as $key=>$it){
                    $this->resultData[] = [
                        'inv' => $ite['inv'],
                        'cbx' => $ite['cbx'],
                        'item' => $key,
                        'count' => $it,
                    ];

                }
            }
        }



        $title = [
            'INV',
            'CBX',
            '物料',
            '数量',
        ];

        array_unshift($this->resultData, $title);

        return collect($this->resultData);
    }

    public function headings(): array
    {
        return ["线束+extender产品唛头文件"];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->setMergeCells(['A1:D1']); //合并单元格
                $event->sheet->getDelegate()->getStyle('A1:D1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);   //设置水平居中
            },
        ];
    }
}
