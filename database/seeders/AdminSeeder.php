<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
			
			$admin = Admin::factory()->create([
				'email'=>'admin@admin.com',
				'password'=>Hash::make('admin'),
				'name'=>'احمد صلاح'
			]);
			
			$admin->assignRole(Role::find(1));
			
    }
}
