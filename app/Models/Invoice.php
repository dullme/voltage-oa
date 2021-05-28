<?php

namespace App\Models;

use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory, DefaultDatetimeFormat;

    public function setFileAttribute($file)
    {
        if (is_array($file)) {
            $this->attributes['file'] = json_encode($file);
        }
    }

    public function adminUser()
    {
        return $this->belongsTo(AdminUser::class, 'admin_users_id')->select('id', 'username', 'name', 'email');
    }

    public function getFileAttribute($file)
    {
        return json_decode($file, true);
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function receiptBatchInvoices()
    {
        return $this->hasMany(ReceiptBatchInvoice::class);
    }

    public function paymentBatchInvoices()
    {
        return $this->hasMany(PaymentBatchInvoice::class);
    }
}
