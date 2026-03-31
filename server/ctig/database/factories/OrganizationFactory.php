<?php

namespace Database\Factories;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Organization>
 */
class OrganizationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Федеральное государственное бюджетное образовательное учреждение высшего образования «Удмуртский государственный университет» (ФГБОУ ВО «УдГУ»)',
            'time_zone' => 'Europe/Samara',
            'director_fio' => 'Рязанова Анна Юрьевна',
            'certificates_issue_address' => 'Удмуртская республика, г. Ижевск, ул. Университетская, д.1',
            'ogrn' => '1021801503382',
            'inn' => '1833010750',
            'address' => 'Удмуртская Республика, г. Ижевск, улица Университетская',
            'name_genitive' => 'федеральному государственному бюджетному образовательному учреждению высшего образования «Удмуртский государственный университет»'
        ];
    }
}