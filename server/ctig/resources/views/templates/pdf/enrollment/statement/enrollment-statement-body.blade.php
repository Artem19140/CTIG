<table border="1" class="table">
    <tr>
        <td colspan="2" class="center">Заявление-анкета</td>
    </tr>
    <tr>
        <td colspan="2" style="padding: 0px; border: none;">
            <table style="width:100%; border-collapse: collapse; margin-bottom: 0;">
                <tr>
                    <td style="white-space:nowrap;" class="no-border">Регистрационный номер:</td>
                    <td class="data no-border" style="width: 100%;">
                        <span style="display:inline-block; width:80%; border-bottom:1px solid black;">
                            {{ $enrollment->reg_number ?? '-'}} 
                        </span>
                    </td>
                </tr>
            </table>
            <div class="small" style="text-align: center;">номер счета</div>
            <div style="width: 100%; text-align: center;">ЭКЗАМЕН ПО РУССКОМУ ЯЗЫКУ КАК ИНОСТРАННОМУ, ИСТОРИИ РОССИИ И ОСНОВАМ ЗАКОНОДАТЕЛЬСТВА РОССИЙСКОЙ ФЕДЕРАЦИИ</div>
        </td>
    </tr>
    <tr>
        <td >Фамилия (кириллица): <span class="data">{{ $enrollment->foreignNational->surname }}</span></td>
        <td>Фамилия (латиница):  <span class="data">{{ $enrollment->foreignNational->surname_latin }}</span></td>
    </tr>
    <tr>
        <td >Имя (кириллица):<span class="data">{{ $enrollment->foreignNational->name }}</span></td>
        <td>Имя (латиница): <span class="data">{{ $enrollment->foreignNational->name_latin }}</span></td>
    </tr>
    <tr>
        <td >Отчество (при наличии, кириллица): <span class="data">{{ $enrollment->foreignNational->patronymic }}</span></td>
        <td>Отчество (при наличии,латиница): <span class="data">{{ $enrollment->foreignNational->patronymic_latin }}</span></td>
    </tr>

    @php
        $countries = collect(json_decode(file_get_contents(storage_path('app/public/countries.json')), true));
        $countryName = $countries->firstWhere('value', $enrollment->foreignNational->citizenship)['text'] ?? '';
    @endphp
    <tr>
        <td>
            Пол: <input style="vertical-align: -4px;" type="checkbox" {{ $enrollment->foreignNational->gender === 'M' ? 'checked' : '' }}>М <input style="vertical-align: -4px;" type="checkbox" {{ $enrollment->foreignNational->gender === 'F' ? 'checked' : '' }}>Ж
        </td>
        <td>Гражданство: <span class="data">{{ $countryName }}</span></td>
    </tr>
    <tr>
        <td>Дата рождения: <span class="data">{{ $enrollment->foreignNational->date_birth->format('d.m.Y') }}</span></td>
        <td>Место сдачи экзамена: <span class="data">{{ $enrollment->exam->address->address }}</span></td>
    </tr>
    <tr>
        <td>Контактный телефон: <span class="data">{{ $enrollment->foreignNational->phone }}</span></td>
        <td>Родной язык: <span class="data"></span></td>
    </tr>
    <tr>

        <td>
            Наименование услуги и ее стоимость:
            <p>{{ $enrollment->exam->type->name }}(уровень {{ $enrollment->exam->type->level }}) - стоимость <span class="data">{{ $enrollment->exam->type->cost}} </span>рублей</p>
        </td>
        <td>
            Вид документа, удостоверяющего личность <br><span class="data">{{ $enrollment->foreignNational->document_type}}</span><br>
            Серия: <span class="data">{{ $enrollment->foreignNational->passport_series }}</span> Номер: <span class="data">{{ $enrollment->foreignNational->passport_number }}</span><br>
            Дата выдачи: <span class="data">{{ $enrollment->foreignNational->issued_date->format('d.m.Y') }}</span><br>
            Кем выдан: <span class="data">{{$enrollment->foreignNational->issued_by}}</span>
        </td>
    </tr>
    <tr>
        <td colspan="2">Дополнительная информация (например, лицо с ограниченными возможностями здоровья): <span class="data">{{ $enrollment->foreignNational->comment }}</span></td>
    </tr>
    <tr>
        <td colspan="2">
            ДОСТОВЕРНОСТЬ ПРЕДОСТАВЛЕННЫХ СВЕДЕНИЙ ПОДТВЕРЖДАЮ<br>
            @include('templates.components.signature-section', [
                                                                'date' =>  $enrollment->exam->begin_time->format('d.m.Y'), 
                                                                'fio' => $enrollment->foreignNational->full_name, 
                                                            ])
            <p class="small" style="margin-bottom: 0; font-style: italic;">Согласие на использование средств видеофиксации.</p> 
            <p class="small" style="margin-top: 0;">Настоящим   даю   согласие  {{$enrollment->center->name_genitive}}
                (ИНН {{$enrollment->center->inn}} , ОГРН {{$enrollment->center->ogrn}}), 
                {{$enrollment->center->address}},
                на   использование   средств   видеофиксации   при   проведении
                экзамена   в   порядке   и   целях,   определяемых законодательством и заключаемом договором.
                Проинформирован об использовании средств видеофиксации и хранении материаловпри проведении экзамена.
            </p>
            @include('templates.components.signature-section', [
                                                                'date' =>  $enrollment->exam->begin_time->format('d.m.Y'), 
                                                                'fio' => $enrollment->foreignNational->full_name, 
                                                            ])
        </td>
    </tr>
</table>

<div class="page-break"></div>

@include('templates.components.labeled-field', [
                                                'value' => $enrollment->foreignNational->full_name, 
                                                'label' => 'Я,' , 
                                                'underline' => '(указать полностью ФИО)'
                                            ])

<p>настоящим подтверждаю, что с условиями публичного договора-оферты возмездного оказания услуг ознакомлен(а) и согласен(а). Необходимый пакет документов для оказания услуги прилагается:</p>

<ul style="list-style: none; padding-left: 0;">
    <li>□ Заявление-анкета</li>
    <li>□ копия паспорта</li>
    <li>□ копия нотариально заверенного перевода паспорта</li>
</ul>
Заказчик:
@include('templates.components.signature-section', [
                                                    'date' =>  $enrollment->exam->begin_time->format('d.m.Y'), 
                                                    'fio' => $enrollment->foreignNational->full_name, 
                                                ])
Исполнитель:
@include('templates.components.signature-section', [
                                                    'date' =>  $enrollment->exam->begin_time->format('d.m.Y'), 
                                                    'fio' =>  $enrollment->creator->full_name, 
                                                ])
<p>Услуга оказана в полном объеме. Претензий к оказанию Услуги не имею.</p>
<br><br>
<div style="border: 1px #000 solid; padding: 5px;">
    <p>
        Дата экзамена: <span class="data">{{ $enrollment->exam->begin_time->format('d.m.Y') }}</span><br>
        Время экзамена: <span class="data">{{ $enrollment->exam->begin_time->format('H:i')}}</span><br>
        Адрес проведения экзамена: <span class="data">{{ $enrollment->exam->address->address }}</span>
    </p>
</div>