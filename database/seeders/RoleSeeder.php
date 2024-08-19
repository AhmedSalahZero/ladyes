<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
			'name'=>'مدير',
			'guard_name'=>'admin'
		]);
		Role::create([
			'name'=>'مشرف',
			'guard_name'=>'admin'
		]);
		
    }
}
