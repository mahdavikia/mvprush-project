<?php
/**
 * Created By: Melorain Component Maker 0.1
 * [[date]]
 * [[component_name_controller]].php
 **/
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class [[component_name_controller]] extends Model
{
    use HasFactory;
    protected $fillable = [
        'content_cat_id',
        'name',
        'title',
        'brief',
        'content',
        'title_en',
        'brief_en',
        'content_en',
        'active',
        'expire_at',
        'content_type',
        'user_id',
        'image',
        'link'
    ];
    public function scopeFilters($query , $request)
    {
        if(isset($request->filter_title) && !is_null($request->filter_title)) {
            $query->where('title', 'LIKE', '%' . $request->filter_title . '%')
                ->orWhere('title_en', 'LIKE', '%' . $request->filter_title . '%')
                ->orWhere('name', 'LIKE', '%' . $request->filter_title . '%');
        }
        if(isset($request->filter_active) && !is_null($request->filter_active) && $request->filter_active !== "2") {
            $query->Where('active',$request->filter_active);
        }
        if(isset($request->filter_category) && !is_null($request->filter_category) && $request->filter_category !== "0"){
            $query->Where('content_cat_id',$request->filter_category);
        }
        return $query;
    }
    public function user()
    {
        return $this->hasMany(User::class);
    }
    public function content_cat()
    {
        return $this->belongsTo(ContentCat::class);
    }
}
