<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Una categoría puede tener muchos gastos.
     */
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }
}
