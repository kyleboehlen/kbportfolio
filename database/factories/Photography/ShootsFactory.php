<?php

namespace Database\Factories\Photography;

use App\Models\Photography\Shoots;
use Illuminate\Database\Eloquent\Factories\Factory;
use DB;

class ShootsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Shoots::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Shoots $shoot) {
            // Set default categories
            foreach(config('photography.categories') as $id => $category)
            {
                if(array_rand([true, false]))
                {
                    DB::table('shoot_categories')->insert([
                        'shoot_id' => $shoot->id,
                        'category_id' => $id,
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
            'shot_on' => $this->faker->date(),
            'desc' => $this->faker->paragraph(),
        ];
    }
}
