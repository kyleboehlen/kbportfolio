<?php

namespace Database\Factories\Photography;

use App\Models\Photography\Photos;
use Illuminate\Database\Eloquent\Factories\Factory;

// Models
use App\Models\Photography\Shoots;

class PhotosFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Photos::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Get shoot
        $shoot = Shoots::first();
        if(is_null($shoot))
        {
            $shoot = Shoots::factory()->create();
        }

        return [
            'caption' => $this->faker->name(),
            'asset' => 'cokTbVdlmDQHlupXapPcTYSkQ8xJoaOBsbNykWHF.png',
            'show_on_home' => array_rand(0, 1),
            'shoot_id' => $shoot->id,
        ];
    }
}
