<?php

namespace App;

use App\Product;
use App\SubCategory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name','product_id'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }



    public function subCategories()
    {
        return $this->hasOne(SubCategory::class);
    }
}
