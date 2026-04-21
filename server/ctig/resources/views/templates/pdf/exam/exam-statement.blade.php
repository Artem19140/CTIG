<div class="center">
    Ведомость результатов экзамена по русскому языку как иностранному, истории России и основам законодательства Российской Федерации, проведенного в ФГБОУ ВО «УдГУ» 
    (Ижевск, Университетская, 1)
</div>

<div class="center text-small">
    Сертификат о владении русским языком, знании истории России и основ законодательства Российской Федерации на уровне, 
    соответствующем цели получения {{ $exam->type->protocol_name }}
</div>

<div class="text-small">Сессия № {{ $exam->session }}, группа № {{ $exam->group }}</div>
<div class="text-small">Дата и время: {{ $exam->begin_time->format('d.m.Y, H:i') }}</div>
<table class="table">
    <thead >
        <tr >
            <th rowspan="2">ФИО</th>
            <th rowspan="2">Паспортные данные</th>
            <th colspan="2">
                Время экзамена
            </th>
            @foreach ($statementTable['headers'] as $block)
                <th colspan="{{ count($block['subblocks']) }}">
                    {{ $block['name'] }}
                </th>
            @endforeach
            <th rowspan="2">Результат</th>
        </tr>

        <tr>
            <th>
                Начала
            </th>
            <th>
                Конца
            </th>
            @foreach ($statementTable['headers'] as $block)
                @foreach ($block['subblocks'] as $subblock)
                    <th>{{ $subblock['name'] }}</th>
                @endforeach
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($statementTable['rows'] as $row)
        <tr>
            <td >{{ $row['fullName'] }}</td>
            <td>{{ $row['fullPassport'] }}</td>
            <td>{{ $row['startedAt'] ?? ''}}</td>
            <td>{{ $row['finishedAt']  ?? ''}}</td>    
            @foreach ($row['subblockMarks'] as $marks)
                <td>{{ $marks['sum'] !== null ? $marks['sum'] : ''}}</td>   
            @endforeach
            <td>{{ $row['result']  ?? ''}}</td>
        </tr>
        @endforeach
        

        
    </tbody>
</table>
<div class="text-small">Результаты экзамена проверены.</div>
<div class="text-small">Ответственные по проведению экзамена (тесторы):</div>
@foreach($exam->examiners as $examiner)
    @include('templates.components.signature-section', [
                'date' =>  \Carbon\Carbon::now()->format('d.m.Y'), 
                'fio' => $examiner->full_name, 
            ])
@endforeach