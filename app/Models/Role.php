<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';
    protected $hidden = [];
    protected $fillable = ['slack', 'role_code', 'label', 'status', 'created_by', 'updated_by'];

    public function scopeActive($query){
        return $query->where('status', 1);
    }

    public function scopeResolveSuperAdminRole($query){
        $query->where('roles.id', '!=', 1);
    }

    public function scopeSortLabelAsc($query){
        return $query->orderBy('roles.label', 'asc');
    }

    public function scopeStatusJoin($query){
        return $query->leftJoin('master_status', function ($join) {
            $join->on('master_status.value', '=', 'roles.status');
            $join->where('master_status.key', '=', 'ROLE_STATUS');
        });
    }

    public function scopeCreatedUser($query){
        return $query->leftJoin('users AS user_created', function ($join) {
            $join->on('user_created.id', '=', 'roles.created_by');
        });
    }

    public function scopeUpdatedUser($query){
        return $query->leftJoin('users AS user_updated', function ($join) {
            $join->on('user_created.id', '=', 'roles.updated_by');
        });
    }

    /* For view files */

    public function createdUser(){
        return $this->hasOne('App\Models\User', 'id', 'created_by')->select(['id','slack', 'fullname', 'email', 'user_code']);
    }

    public function updatedUser(){
        return $this->hasOne('App\Models\User', 'id', 'updated_by')->select(['id','slack', 'fullname', 'email', 'user_code']);
    }

    public function status_data(){
        return $this->hasOne('App\Models\MasterStatus', 'value', 'status')->where('key', 'ROLE_STATUS');
    }

    public function parseDate($date){
        return ($date != null)?Carbon::parse($date)->format(config("app.date_time_format")):null;
    }
}
