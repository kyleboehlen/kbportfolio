<?php

namespace App\Models\Photography;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

// Models
use App\Models\Photography\Photos;

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

    public function photos()
    {
        return $this->hasMany(Photos::class, 'shoot_id');
    }
}
