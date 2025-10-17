<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\RegistersUserEvents;

class SaleDetailItemSaleStock extends Model
{
    use HasFactory, RegistersUserEvents, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'saleDetail_id',
        'itemSaleStock_id',
        'quantity',

        'registerUser_id',
        'registerRole',
        'deleted_at',
        'deleteUser_id',
        'deleteRole',
        'deleteObservation',
    ];

    /**
     * Pertenece a un detalle de venta.
     */
    public function saleDetail()
    {
        return $this->belongsTo(SaleDetail::class, 'saleDetail_id');
    }
    
    /**
     * Pertenece a un lote de stock de producto.
     */
    public function itemSaleStock()
    {
        return $this->belongsTo(ItemSaleStock::class, 'itemSaleStock_id');
    }

}
