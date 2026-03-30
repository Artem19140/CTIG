<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Протокол экзамена</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12pt;
            line-height: 1.5;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            vertical-align: top;
            padding: 4px 2px;
        }
        .underline {
            border-bottom:1px solid black; 
            padding-bottom: 1px;
            display: inline-block;
            min-width: 150px;
        }
        .center {
            text-align: center;
        }
        .full-width {
            width: 100%;
        }
        .section {
            margin-top: 20px;
            
        }
        .signatures td {
            padding-top: 40px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="center" style="font-weight: bold; font-size:14pt;">ПРОТОКОЛ</div>

<p style="text-align: center;">
    проведения экзамена для иностранных граждан по русскому языку как иностранному, 
    истории России и основам законодательства Российской Федерации на уровне, соответствующем цели получения<br>
    <strong>{{ $exam->examType->protocol_name }}</strong>
</p>

<div class="section">
    УЧРЕЖДЕНИЕ, проводящее экзамен: <strong>{{ $organization->name }}</strong>
</div>

<table class="section">
    <tr>
        <td>ДАТА проведения экзамена:</td>
        <td class="underline"><strong>{{ \Carbon\Carbon::parse($exam->begin_time)->format('d.m.Y') }}</strong></td>
    </tr>
    <tr>
        <td>МЕСТО проведения экзамена (адрес):</td>
        <td><strong>{{ $exam->address_name }}</strong></td>
    </tr>
    <tr>
        <td>Начало экзамена:</td>
        <td class="underline"><strong>{{ \Carbon\Carbon::parse($exam->begin_time)->format('H:i') }}</strong></td>
    </tr>
    <tr>
        <td>Окончание экзамена:</td>
        <td class="underline"><strong>{{ \Carbon\Carbon::parse($exam->end_time)->format('H:i') }}</strong></td>
    </tr>
</table>

<div >
    НАРУШЕНИЯ/ОТСУТСТВИЕ НАРУШЕНИЙ в период проведения экзамена:
    <div style="border-bottom: 1px solid black; min-height: 18px; width: 100%; margin-top:90px"> 
        {{ $exam->protocol_comment ?? '' }}
    </div>
</div>
<div class="section center" style="margin-top:40px;">
    <p>ПРОТОКОЛ СОСТАВЛЕН</p>
    <p>
        <span style="border-bottom:1px solid black;">
            {{ \Carbon\Carbon::now()->format('«d»') }}
        </span>
        <span style="border-bottom:1px solid black; padding-left:20px; padding-right:20px;">
            {{ \Carbon\Carbon::now()->format('m') }}
        </span>
        {{ \Carbon\Carbon::now()->format(' Y г.') }}
    </p>

    <table class="full-width">
        @foreach($exam->examiners as $examiner)
        <tr>
            <td>_____________________/_____________<br>{{ $examiner->name }}</td>
            <td>Лицо, принимающее экзамен</td>
        </tr>
        @endforeach
    </table>
</div>

</body>
</html>