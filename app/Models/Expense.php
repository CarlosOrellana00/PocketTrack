<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'is_permanent',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_permanent' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function monthlyExpenseItems(): HasMany
    {
        return $this->hasMany(MonthlyExpenseItem::class);
    }
}
