<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
  use HasFactory, Uuid;

  protected $primaryKey = 'id';
  public $incrementing = false;
  protected $keyType = 'string'; 

  public function company()
  {
    return $this->belongsTo(Company::class);
  }
}
