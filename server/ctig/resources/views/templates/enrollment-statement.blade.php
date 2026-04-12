<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Заявление-анкета</title>
<style>
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 12px;
        line-height: 1.4;
        margin: 20px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 15px;
    }
    .statement-td {
        padding: 2px;
       
    }

    .no-border-td{
        border:none;
    }

    .small {
        font-size: 11px;
    }
    .checkbox {
        display: inline-block;
        width: 12px;
        height: 12px;
        border: 1px solid #000;
        margin-right: 5px;
    }
    hr {
        border: 1px dashed #000;
        margin: 20px 0;
    }
    .page-break {
        page-break-before: always;
    }
    .title{
        text-align: center;
    }
    .data{
        font-weight: bold;
    }
</style>
</head>
<body>

    <table border="1">
        <tr>
            <td colspan="2" class="title statement-td">Заявление-анкета</td>
        </tr>
        <tr>
            <td class="statement-td" colspan="2" style="padding: 0px; border: none;">
                <table style="width:100%; border-collapse: collapse; margin-bottom: 0;">
                    <tr>
                        <td style="white-space:nowrap;" class="no-border-td">Регистрационный номер:</td>
                        <td class="data no-border-td" style="width: 100%;">
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
            <td class="statement-td">Фамилия (кириллица): <span class="data">{{ $enrollment->foreignNational->surname }}</span></td>
            <td class="statement-td">Фамилия (латиница):  <span class="data">{{ $enrollment->foreignNational->surname_latin }}</span></td>
        </tr>
        <tr>
            <td class="statement-td">Имя (кириллица):<span class="data">{{ $enrollment->foreignNational->surname }}</span></td>
            <td class="statement-td">Имя (латиница): <span class="data">{{ $enrollment->foreignNational->surname_latin }}</span></td>
        </tr>
        <tr>
            <td class="statement-td">Отчество (при наличии, кириллица): <span class="data">{{ $enrollment->foreignNational->patronymic }}</span></td>
            <td class="statement-td">Отчество (при наличии,латиница): <span class="data">{{ $enrollment->foreignNational->patronymic_latin }}</span></td>
        </tr>

        @php
            $countries = collect(json_decode(file_get_contents(storage_path('app/public/countries.json')), true));
            $countryName = $countries->firstWhere('value', $enrollment->foreignNational->citizenship)['text'] ?? '';
        @endphp
        <tr>
            <td class="statement-td" >
                Пол: <input style="vertical-align: -4px;" type="checkbox" {{ $enrollment->foreignNational->gender === 'M' ? 'checked' : '' }}>М <input style="vertical-align: -4px;" type="checkbox" {{ $enrollment->foreignNational->gender === 'F' ? 'checked' : '' }}>Ж
            </td>
            <td class="statement-td">Гражданство: <span class="data">{{ $countryName }}</span></td>
        </tr>
        <tr>
            <td class="statement-td">Дата рождения: <span class="data">{{ $enrollment->foreignNational->date_birth->format('d.m.Y') }}</span></td>
            <td class="statement-td">Место сдачи экзамена: <span class="data">{{ $enrollment->exam->address->address }}</span></td>
        </tr>
        <tr>
            <td class="statement-td">Контактный телефон: <span class="data">{{ $enrollment->foreignNational->phone }}</span></td>
            <td class="statement-td">Родной язык: <span class="data"></span></td>
        </tr>
        <tr>

            <td class="statement-td">
                Наименование услуги и ее стоимость:
                <p>{{ $enrollment->exam->examType->name }}(уровень {{ $enrollment->exam->examType->level }}) - стоимость <span class="data">{{ $enrollment->exam->examType->cost}} </span>рублей</p>
            </td>
            <td class="statement-td">
                Вид документа, удостоверяющего личность <br><span class="data">{{ $enrollment->foreignNational->document_type}}</span><br>
                Серия: <span class="data">{{ $enrollment->foreignNational->passport_series }}</span> Номер: <span class="data">{{ $enrollment->foreignNational->passport_number }}</span><br>
                Дата выдачи: <span class="data">{{ $enrollment->foreignNational->issued_date->format('d.m.Y') }}</span><br>
                Кем выдан: <span class="data">{{$enrollment->foreignNational->issued_by}}</span>
            </td>
        </tr>
        <tr>
            <td class="statement-td" colspan="2">Дополнительная информация (например, лицо с ограниченными возможностями здоровья): <span class="data">{{ $enrollment->foreignNational->comment }}</span></td>
        </tr>
        <tr>
            <td class="statement-td" colspan="2">
                ДОСТОВЕРНОСТЬ ПРЕДОСТАВЛЕННЫХ СВЕДЕНИЙ ПОДТВЕРЖДАЮ<br>

                @include('templates.partials.signature-section', [
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
                @include('templates.partials.signature-section', [
                                                                    'date' =>  $enrollment->exam->begin_time->format('d.m.Y'), 
                                                                    'fio' => $enrollment->foreignNational->full_name, 
                                                                ])
            </td>
        </tr>
    </table>

    <div class="page-break"></div>

    @include('templates.partials.labeled-field', [
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
    @include('templates.partials.signature-section', [
                                                        'date' =>  $enrollment->exam->begin_time->format('d.m.Y'), 
                                                        'fio' => $enrollment->foreignNational->full_name, 
                                                    ])
    Исполнитель:
    @include('templates.partials.signature-section', [
                                                        'date' =>  $enrollment->exam->begin_time->format('d.m.Y'), 
                                                        'fio' =>  $enrollment->creator->full_name, 
                                                    ])
    <p>Услуга оказана в полном объеме. Претензий к оказанию Услуги не имею.</p>
    <br><br>
    <div style="border: 1px #000 solid; padding: 5px;">
        <p>Дата экзамена: <span class="data">{{ $enrollment->exam->begin_time->format('d.m.Y') }}</span><br>
        Время экзамена: <span class="data">{{ $enrollment->exam->begin_time->format('H:i')}}</span><br>
        Адрес проведения экзамена: <span class="data">{{ $enrollment->exam->address->address }}</span></p>
    </div>
    <div class="page-break"></div>
    @include('templates.enrollment-approval', ['foreignNational' => $enrollment->foreignNational])
</body>
</html>