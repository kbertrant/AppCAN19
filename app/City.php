<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = [
    'CITY_NAME',
    'CITY_STADIUM_1',
    'CITY_STADIUM_2'
  ];
}
