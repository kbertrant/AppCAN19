<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    protected $fillable = [
        'CRED_TELCO_ID',
        'CRED_USER_ID',
        'PHONE_NBR',
        'AMOUNT'
      ];
}
