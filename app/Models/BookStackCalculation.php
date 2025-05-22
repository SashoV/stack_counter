<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookStackCalculation extends Model
{
    protected $fillable = ['length', 'matrix', 'visible', 'calculation_time', 'user_id'];

    protected $casts = [
        'matrix' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
