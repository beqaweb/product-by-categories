<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryFieldValue extends Model
{
    protected $fillable = [
        'value', 'category_field_id', 'valuable_id', 'valuable_type'
    ];

    public function valuable()
    {
        return $this->morphTo();
    }
}
