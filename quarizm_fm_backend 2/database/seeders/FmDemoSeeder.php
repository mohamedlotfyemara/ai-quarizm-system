<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;

class FmDemoSeeder extends Seeder
{
    public function run(): void
    {
        $customer = User::firstOrCreate(
            ['email' => 'customer@quarizm.tech'],
            ['name' => 'عميل - مجمع الرياض', 'password' => bcrypt('123456'), 'role' => 'customer']
        );

        User::firstOrCreate(
            ['email' => 'coordinator@quarizm.tech'],
            ['name' => 'منسق الصيانة - أحمد', 'password' => bcrypt('123456'), 'role' => 'coordinator']
        );

        User::firstOrCreate(
            ['email' => 'tech1@quarizm.tech'],
            ['name' => 'فني - خالد', 'password' => bcrypt('123456'), 'role' => 'technician', 'team' => 'كهرباء']
        );

        User::firstOrCreate(
            ['email' => 'manager@quarizm.tech'],
            ['name' => 'مدير النظام', 'password' => bcrypt('123456'), 'role' => 'manager']
        );

        Ticket::firstOrCreate(
            ['code' => 'TCK-1001'],
            [
                'title' => 'عطل في التكييف المركزي',
                'description' => 'التكييف لا يعمل في الدور الثاني منذ الصباح',
                'customer_id' => $customer->id,
                'priority' => 'high',
                'status' => 'received',
            ]
        );
    }
}
