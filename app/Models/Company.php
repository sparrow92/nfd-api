<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
  use HasFactory, Uuid;

  protected $primaryKey = 'id';
  public $incrementing = false;
  protected $keyType = 'string'; 
  protected $fillable = ['name', 'nip', 'address', 'city', 'postcode'];

  public function employees()
  {
    return $this->hasMany(Employee::class);
  }
}
