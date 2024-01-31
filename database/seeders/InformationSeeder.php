<?php

namespace Database\Seeders;

use App\Enum\InformationSection;
use App\Models\Information;
use Illuminate\Database\Seeder;

class InformationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		Information::factory()->create([
			'section_name'=>InformationSection::PROFILE,
			'name_en'=>'How It Works',
			'name_ar'=>'كيف اقوم بتقيم الكابتن',
			'description_en'=>'Lorem ipsum dolor sit amet consectetur adipisicing elit. Velit, saepe. Totam in iure voluptatibus repellendus maiores dolorem molestiae alias est corrupti aspernatur. Quos, sit pariatur culpa ratione quia dolorem! Culpa.',
			'description_ar'=>'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ullam labore maxime tempora odit totam placeat unde corporis accusantium officia culpa molestiae ducimus dignissimos voluptatem quos, impedit quia quidem nisi magnam?',
		]);
		
		Information::factory()->create([
			'section_name'=>InformationSection::PROFILE,
			'name_en'=>'How It Works',
			'name_ar'=>'كيف يتم تسعير الرحلة',
			'description_en'=>'Lorem ipsum dolor sit amet consectetur adipisicing elit. Velit, saepe. Totam in iure voluptatibus repellendus maiores dolorem molestiae alias est corrupti aspernatur. Quos, sit pariatur culpa ratione quia dolorem! Culpa.',
			'description_ar'=>'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ullam labore maxime tempora odit totam placeat unde corporis accusantium officia culpa molestiae ducimus dignissimos voluptatem quos, impedit quia quidem nisi magnam?',
		]);
		
		
		Information::factory()->create([
			'section_name'=>InformationSection::AFTER_LOGIN,
			'name_en'=>'How It Works',
			'name_ar'=>'كيف اعلم هل تم قبولي او لا',
			'description_en'=>'Lorem ipsum dolor sit amet consectetur adipisicing elit. Velit, saepe. Totam in iure voluptatibus repellendus maiores dolorem molestiae alias est corrupti aspernatur. Quos, sit pariatur culpa ratione quia dolorem! Culpa.',
			'description_ar'=>'سوق نقوم بالاتصال بك لابلاغك بالقبول او الرفض',
		]);
		
		
		
		
    }
}
