<?php

namespace App\Models\Software;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Projects extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'software_projects';

    protected $fillable = [
        'name', 'type', 'desc', 'app_link',
    ];

    public function setTechnologiesArray()
    {
        // Set an array of all the technology IDs associated with the software project
        $this->technologies =
            DB::table('software_project_technologies')
                ->where('project_id', $this->id)
                ->get()
                ->pluck('technology_id')
                ->toArray();
    }
}