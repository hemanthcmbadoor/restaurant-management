<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $table = 'bills';

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 
        'uuid',
        'name', 
        'email', 
        'contact_number', 
        'payment_method', 
        'total', 
        'created_by', 
        'product_details' 
    ];
}
