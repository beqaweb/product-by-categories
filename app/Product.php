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
        return $this->belongsTo(Category::class)->with('customFields');
    }

    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    public function customFields()
    {
        return $this->hasManyThrough(
            CategoryField::class,
            Category::class,
            'id',
            'category_id',
            'category_id',
            'id'
        );
    }

    public function customFieldValues()
    {
        return $this->morphMany(CategoryFieldValue::class, 'valuable')
            ->with('categoryField');
    }
}
