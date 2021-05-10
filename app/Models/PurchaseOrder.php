<?php

namespace App\Models;

use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory, DefaultDatetimeFormat;

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function vendor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function salesOrder(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SalesOrder::class);
    }

    public function receiptBatches()
    {
        return $this->hasMany(ReceiptBatch::class);
    }

    public function paymentBatches()
    {
        return $this->hasMany(PaymentBatch::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
