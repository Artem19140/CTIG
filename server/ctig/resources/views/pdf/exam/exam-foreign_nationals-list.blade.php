@extends('pdf.layouts.base')
@section('title')
    Список
@endsection
@push('style')
    table, th, td {
        border: 1px solid black;
        text-align:center;
    }
@endpush
@section('content')

<h2 class="text-center">Список</h2>
<div>Экзамен: {{ $exam->type->short_name }}</div>
<div class="mb-10">Дата: {{ $exam->begin_time_local->format('H:i, d.m.Y') }}</div>
<table class="table">
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

@endsection