<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class QuotationProduct extends Model
{
    protected $table = 'quotation_products';
    protected $hidden = [];
    protected $fillable = ['slack', 'quotation_id', 'product_id', 'product_slack', 'product_code', 'name', 'quantity', 'amount_excluding_tax', 'subtotal_amount_excluding_tax', 'discount_percentage', 'tax_percentage', 'discount_amount', 'total_after_discount', 'tax_amount', 'tax_components', 'total_amount', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'];

    public function scopeProduct($query){
        return $query->leftJoin('products', function ($join) {
            $join->on('products.id', '=', 'quotation_products.product_id');
        });
    }

    public function scopeActive($query){
        return $query->where('quotation_products.status', 1);
    }

    /* For view files */
    
    public function createdUser(){
        return $this->hasOne('App\Models\User', 'id', 'created_by')->select(['id','slack', 'fullname', 'email', 'user_code']);
    }

    public function updatedUser(){
        return $this->hasOne('App\Models\User', 'id', 'updated_by')->select(['id','slack', 'fullname', 'email', 'user_code']);
    }

    public function status_data(){
        return $this->hasOne('App\Models\MasterStatus', 'value', 'status')->where('key', 'QUOTATION_PRODUCT_STATUS');
    }

    public function parseDate($date){
        return ($date != null)?Carbon::parse($date)->format(config("app.date_time_format")):null;
    }
}
