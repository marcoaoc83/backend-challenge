<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Usuario extends Model
{

    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $table = "usuarios";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','id_mlearn', 'service_id', 'msisdn', 'access_level', 'name', 'score', 'level'
    ];


}
