<?php

namespace Database\Seeders;


use App\Enums\CounterKey;
use App\Models\Counter;
use App\Models\Center;
use App\Models\Role;
use App\Models\User;
use App\Models\ForeignNational;

use Carbon\Carbon;
use Database\Seeders\ExamTypes\PATENT\PatentSeeder;
use Database\Seeders\ExamTypes\RVP\RvpSeeder;
use Database\Seeders\ExamTypes\VNZH\VnzhSeeder;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Enums\UserRoles;


class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Center::create([
            'name' => 'Федеральное государственное бюджетное образовательное учреждение высшего образования «Удмуртский государственный университет» (ФГБОУ ВО «УдГУ»)',
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
            'center_id' => 1
        ]);

        Counter::create([
            'key' => CounterKey::GroupKey,
            'value' => 1,
            'center_id' => 1
        ]);

        $roleSpecialist = Role::create([
            'name' => UserRoles::Operator,
        ]);

        $roleExaminer = Role::create([
            'name' => UserRoles::Examiner,
        ]);

        $roleScheduler = Role::create([
            'name' => UserRoles::Scheduler,
        ]);

        $roleDirector = Role::create([
            'name' => UserRoles::Director,
        ]);

        $roleVideoRecordOperator = Role::create([
            'name' => UserRoles::VideoRecordOperator,
        ]);

        $roleOrgAdmin = Role::create([
            'name' => UserRoles::OrgAdmin,
        ]);

        $SuperAdmin = Role::create([
            'name' => UserRoles::SuperAdmin,
        ]);
        
        User::factory(5)->create();

        $user = User::create([
            'surname' => 'Петров',
            'name' => 'Николай',
            'patronymic' => 'Дмитрович',
            'email' => env('SUPER_ADMIN_LOGIN'),
            'password' => Hash::make(env('SUPER_ADMIN_PASSWORD')),
            'job_title' => 'Админ', 
            'center_id' => 1,
            'has_to_change_password' => false
        ]);

        $user->roles()->attach($SuperAdmin);
        $user->roles()->attach($roleSpecialist);
        $user->roles()->attach($roleExaminer);
        $user->roles()->attach($roleScheduler);
        $user->roles()->attach($roleDirector);
        $user->roles()->attach($roleOrgAdmin);
        $user->roles()->attach($roleVideoRecordOperator);
        
        $this->call([
            PatentSeeder::class,
            // VnzhSeeder::class,
            RvpSeeder::class,
            ExamSeeder::class
        ]);
        
        ForeignNational::factory(300000) 
            ->withRandomCreator()
            ->create();
    }
}
