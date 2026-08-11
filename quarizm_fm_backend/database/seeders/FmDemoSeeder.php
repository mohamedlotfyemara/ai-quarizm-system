<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;

class FmDemoSeeder extends Seeder
{
    public function run(): void
    {
        $customer = User::create([
            'name' => 'عميل - مجمع الرياض',
            'email' => 'customer@quarizm.tech',
            'password' => bcrypt('123456'),
            'role' => 'customer',
        ]);

        User::create([
            'name' => 'منسق الصيانة - أحمد',
            'email' => 'coordinator@quarizm.tech',
            'password' => bcrypt('123456'),
            'role' => 'coordinator',
        ]);

        User::create([
            'name' => 'فني - خالد',
            'email' => 'tech1@quarizm.tech',
            'password' => bcrypt('123456'),
            'role' => 'technician',
            'team' => 'كهرباء',
        ]);

        User::create([
            'name' => 'مدير النظام',
            'email' => 'manager@quarizm.tech',
            'password' => bcrypt('123456'),
            'role' => 'manager',
        ]);

        Ticket::create([
            'code' => 'TCK-1001',
            'title' => 'عطل في التكييف المركزي',
            'description' => 'التكييف لا يعمل في الدور الثاني منذ الصباح',
            'customer_id' => $customer->id,
            'priority' => 'high',
            'status' => 'received',
        ]);
    }
}
