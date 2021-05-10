<?php

namespace App\Models;

use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiptBatchInvoice extends Model
{
    use HasFactory, DefaultDatetimeFormat;

    protected $fillable = [
        'invoice_id',
        'receipt_batch_id',
        'amount'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
