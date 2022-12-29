<?php

namespace App\Models\Photography;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

// Models
use App\Models\Photography\PhotoCategories;

class Photos extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'caption', 'shoot_id',
    ];

    public function setCategoriesArray()
    {
        // Set an array of all the category IDs associated with the photo
        $this->categories =
            DB::table('photo_categories')
                ->where('photo_id', $this->id)
                ->get()
                ->pluck('category_id')
                ->toArray();
    }

    public function photoCategory() {
        return $this->hasMany(PhotoCategories::class, 'photo_id');
    }
}
