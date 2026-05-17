<div class="text-center">
   Баллы экзамена по русскому языку как иностранному, истории России и основам законодательства Российской Федерации, проведенного в ФГБОУ ВО «УдГУ» 
    (Ижевск, Университетская, 1)
</div>

<div class="text-center text-small">
    Экзамен на уровень {{ $exam->type->level }} - Сертификат о владении русским языком, знании истории России и основ законодательства Российской Федерации на уровне, 
    соответствующем цели получения {{ $exam->type->certificate_name }}
</div>

<div class="text-small">
    Сессия № {{ $exam->session }}, группа № {{ $exam->group }}
</div>
<div class="text-small">
    Дата и время: {{ $exam->begin_time_local->format('d.m.Y, H:i') }}
</div>   

<table class="table border text-center" style="small">
    <thead>
        <tr class="border">
            <th class="border">
                ФИО
            </th>
            <th class="border">
                Паспортные данные
            </th>
            @for($i = 1; $i <= $exam->type->tasks_count; $i++)
                <th class="border">
                    {{ $i }}
                </th>
            @endfor
        </tr>
       
    </thead>
    <tbody>
    
    @foreach ($markTable['rows'] as $row)
    <tr>
        <td class="border">{{ $row['fullName'] }}</td>
        <td class="border">{{ $row['fullPassport'] }}</td>

        @if ($row['answers'])
            @foreach ( $row['answers'] as $answer)
                <td class="border">{{ $answer->mark ?? 0}}</td>
            @endforeach                
        @endif
       @if ($row['answers'] === null)
            @for($i = 1; $i <= $exam->type->tasks_count; $i++)
                <td class="border">
                </td>
            @endfor
       @endif
    @endforeach

    </tbody>
</table>
<div class="text-small mt-10 mb-10">Результаты экзамена проверены.</div>
<div class="text-small mt-10">Ответственные по проведению экзамена (тесторы):</div>
@foreach($exam->examiners as $examiner)
    @include('pdf.components.signature-section', [
        'date' =>  \Carbon\Carbon::now()->format('d.m.Y'), 
        'fio' => $examiner->full_name, 
    ])
@endforeach

@if ($exam->hasSpeaking())
    <div class="break-avoid">
        <div class="text-small mt-10">
            Председатель комиссии:
        </div>
        @include('pdf.components.signature-section', [
            'date' =>  \Carbon\Carbon::now()->format('d.m.Y'), 
            'fio' => $exam->center->commission_chairman, 
        ])
    </div>
@endif