<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WpOrder extends Model
{
    use HasFactory;

    protected $table = 'wp_orders';

    protected $fillable = [
        'order_id',
        'status',
        'currency',
        'total',
        'order_date',
        'created_at',
    ];

    public $timestamps = true;

}
