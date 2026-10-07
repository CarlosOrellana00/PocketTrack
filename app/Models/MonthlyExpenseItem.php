<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlyExpenseItem extends Model
{
    protected $fillable = [
        'monthly_account_id',
        'expense_id',
        'category_id',
        'name',
        'amount',
        'expense_date',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'expense_date' => 'date',
        ];
    }

    public function monthlyAccount(): BelongsTo
    {
        return $this->belongsTo(MonthlyAccount::class);
    }

    public function expense(): BelongsTo
    {
        return $this->belongsTo(Expense::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
