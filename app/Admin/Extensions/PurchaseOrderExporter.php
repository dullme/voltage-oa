<?php


namespace App\Admin\Extensions;

use Carbon\Carbon;
use Encore\Admin\Grid;
use Encore\Admin\Grid\Exporters\ExcelExporter;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PurchaseOrderExporter extends ExcelExporter implements WithMapping, ShouldAutoSize, WithStyles, WithColumnWidths, WithColumnFormatting, WithEvents
{

    protected $fileName = 'purchase_order.xlsx';
    protected $headings = ['采购单号', '销售单号', '项目编号', '项目名称', '类别', '下单时间', '双签时间', '订单总额（¥）', '供应商', '收货序列', '交货批次'];
    protected $rowHeight = [1=>30];  //设置行高
    protected $rows = 0;


    public function columnWidths(): array
    {
        return [
            'I' => 35,
            'H' => 25,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Styling an entire column.
            '1'  => ['font' => ['size' => 14]],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_TEXT,
            'F' => NumberFormat::FORMAT_TEXT,
            'G' => NumberFormat::FORMAT_TEXT,
            'H' => NumberFormat::FORMAT_TEXT,
            'I' => NumberFormat::FORMAT_TEXT,
            'J' => NumberFormat::FORMAT_TEXT,
            'K' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function map($item): array
    {
        return [
            $item->po,
            data_get($item, 'salesOrder.no'),
            data_get($item, 'project.no'),
            data_get($item, 'project.name'),
            $item->type,
            $item->order_at,
            $item->double_signed_at,
            $item->amount,
            data_get($item, 'vendor.name'),
            $this->receiptBatches($item->receiptBatches),
            $this->deliveryBatches($item->deliveryBatches)
        ];
    }

    public function receiptBatches($receiptBatches)
    {
        $res = '';
        foreach ($receiptBatches->toArray() as $key => $item){
            $res.= (++$key) . '、' . $item['receipt_at'] . ' 【¥' . $item['amount']."】\n";
        }

        return $res;
    }

    public function deliveryBatches($deliveryBatches)
    {
        $res = '';
        foreach ($deliveryBatches->toArray() as $key => $item){
            $res.= (++$key) . '、' . $item['estimated_delivery'] . ' 【' . $item['comment']."】\r\n";
        }

        return $res;
    }

    public function registerEvents(): array
    {
        $this->rows = count($this->getData()) + 1;

        return [
            AfterSheet::class => function( AfterSheet $event){
                $event->sheet->getDelegate()->getStyle('A1:K'.$this->rows)->getAlignment()->setVertical('center');
                $event->sheet->getStyle('A2:K'.$this->rows)->getAlignment()->setWrapText(TRUE);
                foreach ($this->rowHeight as $row => $height) {
                    $event->sheet->getDelegate()
                        ->getRowDimension($row)
                        ->setRowHeight($height);
                }
            }
        ];
    }
}
