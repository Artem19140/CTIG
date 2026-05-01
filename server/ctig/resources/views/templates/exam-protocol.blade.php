<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<title>Протокол экзамена</title>

<style>
body {
    font-family: DejaVu Sans, sans-serif;
    font-size: 12pt;
    line-height: 1.4;
    color: #000;
}

.container {
    width: 100%;
}

.title {
    text-align: center;
    font-weight: bold;
    font-size: 15pt;
    margin-bottom: 10px;
}

.subtitle {
    text-align: center;
    font-size: 11pt;
    margin-bottom: 10px;
}

.section {
    margin-top: 20px;
}

.label {
    width: 45%;
}

.value {
    width: 55%;
}

table {
    width: 100%;
    border-collapse: collapse;
}

td {
    padding: 4px 0;
    vertical-align: top;
}

.underline {
    border-bottom: 1px solid #000;
    display: inline-block;
    min-width: 120px;
    padding-bottom: 2px;
}

.full-line {
    border-bottom: 1px solid #000;
    height: 40px;
    margin-top: 10px;
}

.center {
    text-align: center;
}

.bold {
    font-weight: bold;
}

.signatures td {
    padding-top: 25px;
    text-align: center;
}

.footer-date span {
    border-bottom: 1px solid #000;
    padding: 0 10px;
    display: inline-block;
    min-width: 30px;
}
.prebox {
    white-space: pre;   
}
</style>
</head>

<body>

<div class="container">

    <div class="title">ПРОТОКОЛ</div>

    <div class="subtitle">
        проведения экзамена для иностранных граждан по русскому языку как иностранному,
        истории России и основам законодательства Российской Федерации<br>
        на уровне, соответствующем цели получения<br>
        <span class="bold">{{ $exam->type->certificate_name }}</span>
    </div>

    <div class="section">
        <span class="bold">Учреждение:</span> {{ $center->name }}
    </div>

    <table class="section">
        <tr>
            <td class="label">Дата проведения экзамена:</td>
            <td class="value">
                <span class="underline" style="text-align: center;">
                    {{ $exam->begin_time_local->format('d.m.Y') }}
                </span>
            </td>
        </tr>

        <tr>
            <td>Место проведения (адрес):</td>
            <td>{{ $exam->address_name }}</td>
        </tr>

        <tr>
            <td>Начало экзамена:</td>
            <td>
                <span class="underline" style="text-align: center;">
                    {{ $beginTimeReal?->format('H:i') ?? 'Ашибка'}}
                </span>
            </td>
        </tr>

        <tr>
            <td>Окончание экзамена:</td>
            <td>
                <div class="underline" style="text-align: center;">
                    {{ $endTimeReal?->format('H:i') ?? 'Ашибка' }}
                </div>
            </td>
        </tr>
    </table>

    <div class="section">
        <span class="bold">
            Нарушения / отсутствие нарушений:
        </span>
        <div>
        @if ($exam->protocol_comment)
            <div class="prebox">
{{ $exam->protocol_comment}}
            </div>
        @endif
            <div>
                @foreach ( $bannedAttempts as $attempt )
                    <div>
                        - Cдающий {{ $attempt->foreignNational->full_name_short }}
                        (паспорт: {{ $attempt->foreignNational->full_passport }}) был снят с экзамена в {{ $attempt->banned_at->format('H:i') }} по причине: 
                        "{{ $attempt->ban_reason }}";
                    </div> 
                @endforeach
            </div>

            <div>
                @foreach ( $attemptWithViolations as $attempt )
                    <div style="margin-bottom: 10px;">
                        <div>
                            За сдающим {{ $attempt->foreignNational->full_name_short }}
                            (паспорт: {{ $attempt->foreignNational->full_passport }})
                            зафиксированы нарушения:
                        </div>

                        <ol style="margin-top: 5px; margin-left: 15px;">
                            @foreach ($attempt->violations as $violation)
                                <li style="margin-bottom: 3px;">
                                    {{ $violation->comment }};
                                </li>
                            @endforeach
                        </ol>
                    </div>
                @endforeach
            </div>
        </div>
        <div>{{ !$exam->protocol_comment && $bannedAttempts->isEmpty() ?  'Нарушения не установлены' : '' }}</div>
    </div>
    
    

    <div class="section center" style="margin-top:20px;">
        <div class="bold" style="margin-bottom:10px;">
            ПРОТОКОЛ СОСТАВЛЕН
        </div>
        <div >
            @include('templates.components.date-inline', ['date' => \Carbon\Carbon::now()])
        </div>
    </div>
    Лица, принимающие экзамен:
    @foreach($exam->examiners as $examiner)
        @include('templates.components.signature-section', [
            'date' =>  \Carbon\Carbon::now()->format('d.m.Y'), 
            'fio' => $examiner->full_name, 
        ])
    @endforeach

</div>

</body>
</html>