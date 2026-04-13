<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Список на экзамен</title>

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

<h2>Список</h2>
<div>Экзамен: {{ $exam->examType->short_name }}</div>
<div style="margin-bottom: 10px;">Дата и время: {{ $exam->begin_time->format('d.m.Y, H:i') }}</div>
<table>
    <tr>
        <th>ФИО</th>
        <th>Паспорт</th>
    </tr>

    @foreach ($foreignNationals as $f)
        <tr>
            <td>{{ $f->full_name }}</td>
            <td>{{ $f->full_passport }}</td>
        </tr>
    @endforeach

</table>

</body>
</html>