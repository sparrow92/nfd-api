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
  protected $fillable = ['first_name', 'last_name', 'email', 'phone_number', 'company_id'];

  public function company()
  {
    return $this->belongsTo(Company::class);
  }
}
