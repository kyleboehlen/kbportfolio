<?php

namespace Database\Factories\Software;

use App\Models\Software\Projects;
use Illuminate\Database\Eloquent\Factories\Factory;
use DB;

class ProjectsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Projects::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Projects $project) {
            // Set technologies
            foreach(config('software.technologies') as $id => $technology)
            {
                if(array_rand([true, false]))
                {
                    DB::table('software_project_technologies')->insert([
                        'project_id' => $project->id,
                        'technology_id' => $id,
                    ]);
                }
            }
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'type' => 'demo',
            'logo' => 'Exym65yFyWBQ6C7MOIjE1vfVkw74l55YPMOMQExN.jpg',
            'desc' => $this->faker->paragraph(),
            'app_link' => 'https://www.kyleboehlen.com',
        ];
    }
}
