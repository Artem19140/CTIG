<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Кода на экзамен</title>

<style>
body {
    font-size: 16px;
    line-height: 1.3;
}

h2 {
    text-align: center;
    margin-bottom: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

th, td {
    border: 1px solid #000;
    padding: 5px;
    text-align: left;
    vertical-align: top;
}

th {
    background-color: #f0f0f0;
    font-weight: bold;
}

tr:nth-child(even) td {
    background-color: #f9f9f9;
}
</style>

</head>
<body>

<h2>Коды ИГ</h2>
<div>Экзамен: {{ $exam->type->short_name }}</div>
<div style="margin-bottom: 10px;">Дата и время: {{ $exam->begin_time_local->format('d.m.Y в H:i') }}</div>
<table>
    <tr>
        <th>ФИО</th>
        <th>Паспорт</th>
        <th>Код</th>
    </tr>

    @foreach ($exam->enrollments as $enrollment)
        <tr>
            <td>{{  $enrollment->foreignNational->surname }} {{ $enrollment->foreignNational->name }} {{ $enrollment->foreignNational->patronymic }}</td>
            <td>{{  $enrollment->foreignNational->passport_series }} {{  $enrollment->foreignNational->passport_number }}</td>
            <td>{{ substr($enrollment?->exam_code, 0, 3) . "  " .  substr($enrollment?->exam_code, 3, 3) }}</td>
        </tr>
    @endforeach

</table>

</body>
</html>