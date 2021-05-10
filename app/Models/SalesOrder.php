<?php

namespace App\Models;

use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    use HasFactory, DefaultDatetimeFormat;

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function salesOrderBatches()
    {
        return $this->hasMany(SalesOrderBatch::class);
    }

    public function setVendorsAttribute($vendors)
    {
        if (is_array($vendors)) {
            $this->attributes['vendors'] = json_encode($vendors);
        }
    }

    public function getVendorsAttribute($vendors)
    {
        return json_decode($vendors, true);
    }
}
