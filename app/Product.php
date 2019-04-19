<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema()
 */
class Product extends Model
{
    /**
     * @OA\Property(property="name", type="string")
     */

    protected $fillable = [
        'name', 'image_id', 'category_id', 'user_id'
    ];

    public function image()
    {
        return $this->hasOne(Image::class, 'id', 'image_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class);
    }
}
