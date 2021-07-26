<?php

namespace App\Models\Photography;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Photos extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'caption', 'shoot_id',
    ];

    public function setCategoriesArray()
    {
        $this->categories =
            DB::table('photo_categories')
                ->where('photo_id', $this->id)
                ->get()
                ->pluck('category_id')
                ->toArray();
    }
}
