<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountMergingRequest extends Model
{

    protected $fillable = [
        'existing_ids',
        'merged_to',
        'reason',
        'maker_id',
        'checker_id',
        'status',
    ];

    // Add relationships to User for maker and checker
    public function maker()
    {
        return $this->belongsTo(User::class, 'maker_id');
    }

    public function checker()
    {
        return $this->belongsTo(User::class, 'checker_id');
    }
}
