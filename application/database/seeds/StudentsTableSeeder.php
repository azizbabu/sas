<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class StudentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $class_no = 1;
        $section_no = 1;

        foreach(config('constants.classes') as $class_key=>$class_value) {

            // if($class_no == 2) {

                foreach(config('constants.sections') as $section_key=>$section_value) {

                    $gender = in_array($section_key, ['a', 'b']) ? 'female':'male';

                    // if($section_no == 1) {

                        for ($i=1; $i <= 10; $i++) { 
                        	
                            factory(App\Student::class)->create([
                                'name'  => $faker->name($gender),
                                'class' => $class_key,
                                'section'   => $section_key,
                                'roll'  => $i,
                                'gender'    => $gender,
                                'admission_date'    => \Carbon::now()->startOfYear()->subYears($class_key),
                            ]);
                            
                        }
                    // }

                    $section_no++;
                }

            // }

            $class_no++;
        }
    }
}
