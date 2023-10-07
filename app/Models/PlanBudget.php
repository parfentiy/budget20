<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanBudget extends Model
{
    use HasFactory;

    protected $fillable = [
        'dataset',
        'month',
        'year',
        'user_id',
        'description',
        'incomes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
