<?php

namespace Database\Seeders;

use App\Models\Help;
use Illuminate\Database\Seeder;

class HelpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		Help::factory()->create([
			'name_en'=>'How It Works',
			'name_ar'=>'كيف يتم خصم رسوم تطبيق ليدز',
			'description_en'=>'Lorem ipsum dolor sit amet consectetur adipisicing elit. Velit, saepe. Totam in iure voluptatibus repellendus maiores dolorem molestiae alias est corrupti aspernatur. Quos, sit pariatur culpa ratione quia dolorem! Culpa.',
			'description_ar'=>'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ullam labore maxime tempora odit totam placeat unde corporis accusantium officia culpa molestiae ducimus dignissimos voluptatem quos, impedit quia quidem nisi magnam?',
			'model_type'=>'Driver'
		]);
		
		Help::factory()->create([
			'name_en'=>'How To Change Password',
			'name_ar'=>'كيف اغير الباسورد',
			'description_en'=>'Lorem ipsum dolor sit amet consectetur adipisicing elit. Velit, saepe. Totam in iure voluptatibus repellendus maiores dolorem molestiae alias est corrupti aspernatur. Quos, sit pariatur culpa ratione quia dolorem! Culpa.',
			'description_ar'=>'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ullam labore maxime tempora odit totam placeat unde corporis accusantium officia culpa molestiae ducimus dignissimos voluptatem quos, impedit quia quidem nisi magnam?',
			'model_type'=>'Driver'
		]);
		
		Help::factory()->create([
			'name_en'=>'Issue In Login',
			'name_ar'=>'اواجهه مشكله في تسجيل الدخول',
			'description_en'=>'Lorem ipsum dolor sit amet consectetur adipisicing elit. Velit, saepe. Totam in iure voluptatibus repellendus maiores dolorem molestiae alias est corrupti aspernatur. Quos, sit pariatur culpa ratione quia dolorem! Culpa.',
			'description_ar'=>'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ullam labore maxime tempora odit totam placeat unde corporis accusantium officia culpa molestiae ducimus dignissimos voluptatem quos, impedit quia quidem nisi magnam?',
			'model_type'=>'Client'
		]);
		
		
    }
}
