<?php

namespace App\Models;

use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentBatch extends Model
{
    use HasFactory, DefaultDatetimeFormat;

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function paymentBatchInvoices()
    {
        return $this->hasMany(PaymentBatchInvoice::class);
    }
}
