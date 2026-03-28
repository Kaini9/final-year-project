<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Profile;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = Role::where('name', 'Admin')->first();

        // Check if admin already exists
        if (!User::where('email', 'admin@fashionconnect.com')->exists() && $adminRole) {
            $admin = User::create([
                'name' => 'FashionConnect Admin',
                'email' => 'admin@fashionconnect.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role_id' => $adminRole->id,
            ]);

            Profile::create([
                'user_id' => $admin->id,
                'bio' => 'Platform Administrator',
            ]);
        }
    }
}
