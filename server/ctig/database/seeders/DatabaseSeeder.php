<?php

namespace Database\Seeders;


use App\Enums\CounterKey;
use App\Models\Counter;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use App\Models\Student;

use Carbon\Carbon;
use Database\Seeders\ExamTypes\PATENT\PatentSeeder;
use Database\Seeders\ExamTypes\RVP\RvpSeeder;
use Database\Seeders\ExamTypes\VNZH\VnzhSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Enums\UserRoles;


class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Organization::create([
            'name' => 'УдГУ',
            'time_zone' => 'Europe/Samara',
            'director_fio' => 'Рязанова Анна Юрьевна',
            'certificates_issue_address' => 'Удмуртская республика, г. Ижевск, ул. Университетская, д.1',
            'ogrn' => '1021801503382',
            'inn' => '1833010750',
            'address' => 'Удмуртская Республика, г. Ижевск, улица Университетская',
            'name_genitive' => 'федеральному государственному бюджетному образовательному учреждению высшего образования «Удмуртский государственный университет»'
        ]);

        Counter::create([
            'key' => CounterKey::RegNumKey,
            'value' => Carbon::now()->format('y').'0000',
            'organization_id' => 1
        ]);

        Counter::create([
            'key' => CounterKey::GroupKey,
            'value' => 1,
            'organization_id' => 1
        ]);

        $roleSpecialist = Role::create([
            'name' => UserRoles::Operator,
        ]);

        $roleEXAMINER = Role::create([
            'name' => UserRoles::EXAMINER,
        ]);

        $roleScheduler = Role::create([
            'name' => UserRoles::Scheduler,
        ]);

        $roleDirector = Role::create([
            'name' => UserRoles::Director,
        ]);

        $roleOrgAdmin = Role::create([
            'name' => UserRoles::OrgAdmin,
        ]);
        
        User::factory(5)->create();
        
        $this->call([
            PatentSeeder::class,
            // VnzhSeeder::class,
            RvpSeeder::class,
            ExamSeeder::class,
            ExamStudentSeeder::class
        ]);
        
        Student::factory(200) 
            ->withRandomCreator()
            ->create();
    }
}
