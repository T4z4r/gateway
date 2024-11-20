<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountDetail extends Model
{
    // Specify the fillable attributes
    protected $fillable = [
        'cuId',
        'label',
        'deadId',
        'checkDuplicate',
        'type',
        'customer_id',
        'account_number',
        'account_currency',
        'language',
        'account_opened_date',
        'txn_product_code',
        'risk_level',
        'screening',
        'account_customer_first_name',
        'account_customer_middle_name',
        'account_customer_last_name',
        'account_customer_gender',
        'martial_status',
        'id_type',
        'id_number',
        'nationality',
        'district',
        'state',
        'region',
        'branch',
        'country_of_birth',
        'account_mobile_number',
        'customer_type',
        'id_issue_date',
        'id_expiry_date',
        'birth_date',
        'customer_photo',
        'customer_signature',
        'account_email_address'
    ];
}
