<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absen extends Model
{
    protected $table = 'absen';
    public $incrementing = false;

    public function masuks(){
    	return $this->hasOne('App\Models\Absen_masuk','absen');
    }

    public function pulangs(){
    	return $this->hasOne('App\Models\Absen_pulang','absen');
    }

    public function users(){
    	return $this->belongsTo('App\User','user');
    }
}
