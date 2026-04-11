<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $this->createRootUser();
        $this->createAdminUser();
        $this->createStoreUser();
        $this->createVisitorUser();
    }

    private function createRootUser()
    {
        $rootUser = User::updateOrCreate(
            ['email' => 'root@readylaundry.com'],
            [
                'first_name' => 'Root',
                'mobile' => '010000000016354',
                'is_active' => true,
                'password' => Hash::make('secret@123'),
            ]
        );

        Wallet::firstOrCreate(['user_id' => $rootUser->id]);

        $permissions = config('acl.permissions');

        foreach ($permissions as $permission => $value) {
            if (in_array('root', $value)) {
                $rootUser->givePermissionTo($permission);
            }
        }

        $rootUser->syncRoles(['root']);
    }

    private function createAdminUser()
    {
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'first_name' => 'Admin',
                'mobile' => '0100000000221',
                'password' => Hash::make('secret@123'),
            ]
        );

        Wallet::firstOrCreate(['user_id' => $adminUser->id]);

        $adminUser->syncRoles(['admin']);
        $adminUser->syncPermissions(['root']);
    }

 
    private function createStoreUser()
    {

        $storeUser = User::updateOrCreate(
            ['email' => 'demo-shop@readylaundry.com'],
            [
                'first_name' => 'Shop',
                'last_name' => 'Owner',
                'mobile' => '010000000026321',
                'password' => Hash::make('secret@123'),
                'is_active' => true,
            ]
        );


        Wallet::firstOrCreate(['user_id' => $storeUser->id]);


        $storeUser->syncRoles(['store']);
        $storeUser->syncPermissions(['root']);


        Store::updateOrCreate(
             ['shop_owner' => $storeUser->id],
            [
                'name' => 'Ready Laundry Shop',
                'slug' => Str::slug('Ready Laundry Shop'),
                'status' => 1,
            ]
        );
    }

    private function createVisitorUser()
    {
        $visitorUser = User::updateOrCreate(
            ['email' => 'visitor@example.com'],
            [
                'first_name' => 'Visitor',
                'mobile' => '0100000000343',
                'password' => Hash::make('secret@123'),
            ]
        );

        Wallet::firstOrCreate(['user_id' => $visitorUser->id]);

        $visitorUser->syncRoles(['visitor']);
    }
}
