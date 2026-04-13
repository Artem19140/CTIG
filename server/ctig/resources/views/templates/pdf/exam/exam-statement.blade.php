<div class="center">
    Ведомость результатов экзамена по русскому языку как иностранному, истории России и основам законодательства Российской Федерации, проведенного в ФГБОУ ВО «УдГУ» 
    (Ижевск, Университетская, 1)
</div>

<div class="center">
    Сертификат о владении русским языком, знании истории России и основ законодательства Российской Федерации на уровне, 
    соответствующем цели получения {{ $exam->examType->protocol_name }}
</div>

<div>Сессия № {{ $exam->session }}, группа № {{ $exam->group }}</div>
<div>Дата и время: {{ $exam->begin_time->format('d.m.Y, H:i') }}</div>
<table class="table">
    <thead>
        <tr>
            <th rowspan="2">ФИО</th>
            <th rowspan="2">Паспортные данные</th>
            <th colspan="2">
                Время экзамена
            </th>
            @foreach ($columns as $block)
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
            @foreach ($columns as $block)
                @foreach ($block['subblocks'] as $subName)
                    <th>{{ $subName }}</th>
                @endforeach
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $row)
        <tr>
            <td>{{ $row['fio'] }}</td>
            <td>{{ $row['passport'] }}</td>
            <td>{{ $row['started_at'] }}</td>
            <td>{{ $row['finished_at'] }}</td>    
            @foreach ($columns as $blockId => $block)
                @foreach ($block['subblocks'] as $subId => $subName)
                    <td>
                        {{ $row['results'][$blockId][$subId] ?? '-' }}
                    </td>
                @endforeach
            @endforeach
            <td>{{ $row['result'] ?  'Сертификат' : 'Справка' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<div>Результаты экзамена проверены.</div>
<div>Ответственные по проведению экзамена (тесторы):</div>
@foreach($exam->examiners as $examiner)
    @include('templates.components.signature-section', [
                'date' =>  \Carbon\Carbon::now()->format('d.m.Y'), 
                'fio' => $examiner->full_name, 
            ])
@endforeach