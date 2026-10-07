<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlyAccount extends Model
{
    protected $fillable = [
        'year',
        'month',
        'income',
        'period_start',
        'period_end',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'month' => 'integer',
            'income' => 'decimal:2',
            'period_start' => 'date',
            'period_end' => 'date',
        ];
    }

    public function expenseItems(): HasMany
    {
        return $this->hasMany(MonthlyExpenseItem::class);
    }
}
