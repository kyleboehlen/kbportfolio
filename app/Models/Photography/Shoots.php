<?php

namespace App\Models\Photography;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Shoots extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'name',
    ];

    public function setCategoriesArray()
    {
        $this->categories =
            DB::table('shoot_categories')
                ->where('shoot_id', $this->id)
                ->get()
                ->pluck('category_id')
                ->toArray();
    }
}
