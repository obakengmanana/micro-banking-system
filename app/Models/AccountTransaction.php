<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountTransaction extends Model
{
    use HasFactory;

    protected $fillable = ['account_id', 'date', 'amount', 'type'];

    // Cast the 'date' attribute to a DateTime object
    protected $casts = [
        'date' => 'datetime',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
