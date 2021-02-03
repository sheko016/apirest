<?php

namespace App;

use App\Seller;
use App\Category;
use App\Transaction;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
	const PRODUCTO_DISPONIBLE = 'disponible';
	const PRODUCTO_NO_DISPONIBLE = 'no disponible';
    protected $fillable = [
    	'name',
    	'description',
    	'quantity',
    	'status',
    	'image',
    	'seller_id',


    ];

    public function EstaDisponible(){
    	return $this->status == Product::PRODUCT_DISPONIBLE;
    }

    public function seller($value='')
    {
       return $this->belongsTo(Seller::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function categories()
    {
       return $this->belongsToMany(Category::class);
       // return $this->belongsToMany('app\Category');
    }


}
