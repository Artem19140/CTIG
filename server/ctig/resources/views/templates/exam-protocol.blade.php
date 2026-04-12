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
</style>
</head>

<body>

<div class="container">

    <div class="title">ПРОТОКОЛ</div>

    <div class="subtitle">
        проведения экзамена для иностранных граждан по русскому языку как иностранному,
        истории России и основам законодательства Российской Федерации<br>
        на уровне, соответствующем цели получения<br>
        <span class="bold">{{ $exam->examType->protocol_name }}</span>
    </div>

    <div class="section">
        <span class="bold">Учреждение:</span> {{ $center->name }}
    </div>

    <table class="section">
        <tr>
            <td class="label">Дата проведения экзамена:</td>
            <td class="value">
                <span class="underline">
                    {{ \Carbon\Carbon::parse($exam->begin_time)->format('d.m.Y') }}
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
                <span class="underline">
                    {{ \Carbon\Carbon::parse($exam->begin_time)->format('H:i') }}
                </span>
            </td>
        </tr>

        <tr>
            <td>Окончание экзамена:</td>
            <td>
                <div class="underline">
                    {{ \Carbon\Carbon::parse($exam->end_time)->format('H:i') }}
                </div>
            </td>
        </tr>
    </table>

    <div class="section">
        <span class="bold">
            Нарушения / отсутствие нарушений:
        </span>
        <div>
            {{ $exam->protocol_comment}}
            <div>
                @foreach ( $bannedAttempts as $attempt )
                    <span>сдающий {{ $attempt->foreignNational->full_name }} был снят с экзамена по причине: "{{ $attempt->ban_reason }}"</span> 
                @endforeach
            </div>
        </div>
        <div>{{ !$exam->protocol_comment && !$bannedAttempts ? 'Нарушения не установлены' : '' }}</div>
    </div>
    
    

    <div class="section center" style="margin-top:50px;">
        <div class="bold" style="margin-bottom:10px;">
            ПРОТОКОЛ СОСТАВЛЕН
        </div>

        <div >
            <span style="border-bottom:1px solid black;"> 
                {{ \Carbon\Carbon::now()->format('«d»') }} 
            </span> 
            <span style="border-bottom:1px solid black; padding-left:20px; padding-right:20px;"> 
                {{ \Carbon\Carbon::now()->format('m') }} 
            </span> 
            {{ \Carbon\Carbon::now()->format(' Y г.') }}
        </div>
    </div>

    @foreach($exam->examiners as $examiner)
        <table style="width: 50%; margin-bottom: 20px; border-collapse: collapse; margin-top:50px;">
        <tbody>
            <tr>
                <td style="
                    border-bottom: 1px solid #000;
                    white-space: nowrap;
                    width:250px;
                    padding-bottom: 2px;
                ">
                    {{ $examiner->full_name }}
                </td>

                <td style="
                    width: 100%;
                    border-bottom: 1px solid #000;
                ">/</td>
            </tr>

            <!-- Подпись -->
            <tr>
                <td colspan="2" style="
                    font-size: 10px;
                    line-height: 10px;
                    padding-top: 2px;
                ">
                    Лицо, принимающее экзамен
                </td>
            </tr>
            </tbody>
        </table>

    @endforeach

</div>

</body>
</html>