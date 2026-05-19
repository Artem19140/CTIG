<?php

namespace Database\Seeders;


use App\Enums\CounterKey;
use App\Models\Address;
use App\Models\Counter;
use App\Models\Center;
use App\Models\Role;
use App\Models\Employee;
use Carbon\Carbon;
use Database\Seeders\ExamTypes\PATENT\PatentSeeder;
use Database\Seeders\ExamTypes\RVP\RvpSeeder;
use Database\Seeders\ExamTypes\VNZH\VnzhSeeder;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Enums\EmployeeRole;


class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            RolesSeeder::class
        ]);

        $center = Center::create([
            'name' => 'Федеральное государственное бюджетное образовательное учреждение высшего образования «Удмуртский государственный университет»',
            'time_zone' => 'Europe/Samara',
            'director_fio' => 'Рязанова Анна Юрьевна',
            'certificates_issue_address' => 'Удмуртская республика, г. Ижевск, ул. Университетская, д.1',
            'ogrn' => '1021801503382',
            'inn' => '1833010750',
            'short_name' => 'ФГБОУ ВО «УдГУ»',
            'address' => 'Удмуртская Республика, г. Ижевск, улица Университетская',
            'name_genitive' => 'федеральному государственному бюджетному образовательному учреждению высшего образования «Удмуртский государственный университет»',
            'commission_chairman' => 'Иванов Иван Иванович'
        ]);

        context(['center_seed_id' => $center->id]);

        Counter::create([
            'key' => CounterKey::RegNum,
            'value' => Carbon::now()->format('y').'0000',
            'center_id' =>  $center->id
        ]);

        Counter::create([
            'key' => CounterKey::Group,
            'value' => 1,
            'center_id' =>  $center->id
        ]);

        // Counter::create([
        //     'key' => CounterKey::RegNum,
        //     'value' => Carbon::now()->format('y').'0000',
        //     'center_id' =>  $center->id
        // ]);

        // Counter::create([
        //     'key' => CounterKey::Group,
        //     'value' => 1,
        //     'center_id' =>  $center->id
        // ]);

        $roleSpecialist = Role::findByEnum(EmployeeRole::Operator);

        $roleExaminer = Role::findByEnum(EmployeeRole::Examiner);

        $roleScheduler = Role::findByEnum(EmployeeRole::Scheduler);

        $roleDirector = Role::findByEnum(EmployeeRole::Director);


        $roleOrgAdmin = Role::findByEnum(EmployeeRole::CenterAdmin);

        $superAdmin = Role::findByEnum(EmployeeRole::SuperAdmin);
        

        $password = Hash::make('123456789' );
        Employee::factory() 
            ->examiner()   
            ->create([
                'center_id' => $center,
                'email' => 'examiner@udsu.ru',
                'has_to_change_password' => false,
                'password' => $password
            ]);

        Employee::factory() 
            ->operator()   
            ->create([
                'center_id' => $center,
                'email' => 'operator@udsu.ru',
                'has_to_change_password' => false,
                'password' => $password
            ]);

        Employee::factory() 
            ->scheduler()   
            ->create([
                'center_id' => $center,
                'email' => 'scheduler@udsu.ru',
                'has_to_change_password' => false,
                'password' => $password
            ]);

        Employee::factory() 
            ->director()   
            ->create([
                'center_id' => $center,
                'email' => 'director@udsu.ru',
                'has_to_change_password' => false,
                'password' => $password
            ]);

        Employee::factory()
            ->operator()   
            ->create([
                'email' => 'operator2@udsu.ru',
                'has_to_change_password' => false,
                'password' => $password
            ]);

       

        $employee = Employee::create([
            'surname' => 'Петров',
            'name' => 'Николай',
            'patronymic' => 'Дмитрович',
            'email' => env('SUPER_ADMIN_LOGIN'),
            'password' => Hash::make(env('SUPER_ADMIN_PASSWORD')),
            'job_title' => 'Админ', 
            'center_id' => 1,
            'has_to_change_password' => false
        ]);

        Address::create([
            'address'=>'Ижевск, Университетская, 1/корпус 2/каб. 124',
            'max_capacity' => 15,
            'center_id' => $center->id,
            'creator_id' => $employee->id
        ]);

        Address::create([
            'address'=>'Ижевск, Удмуртская, 2 каб. 542',
            'max_capacity' => 14,
            'center_id' => $center->id,
            'creator_id' => $employee->id
        ]);

        $employee->roles()->attach($superAdmin);
        $employee->roles()->attach($roleSpecialist);
        $employee->roles()->attach($roleExaminer);
        $employee->roles()->attach($roleScheduler);
        $employee->roles()->attach($roleDirector);
        $employee->roles()->attach($roleOrgAdmin);
        
        $this->call([
            PatentSeeder::class,
            RvpSeeder::class,
            VnzhSeeder::class,
            //ExamSeeder::class,
            ForeignNationalSeeder::class
        ]);
        
    }
}
