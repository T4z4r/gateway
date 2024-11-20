<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountMarkingRequest extends Model
{
    protected $fillable = [
        'cuId',
        'customer_id',
        'label',
        'old_label',
        'reason',
        'maker_id',
        'checker_id',
        'status',
        'remark'
    ];
}
