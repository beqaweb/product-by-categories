<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema()
 */
class Category extends Model
{
    /**
     * @OA\Property(property="name", type="string")
     */

    protected $fillable = [
        'name'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function permittedUsers()
    {
        return $this->belongsToMany(User::class);
    }
}
