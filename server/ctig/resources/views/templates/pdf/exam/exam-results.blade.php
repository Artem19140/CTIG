@extends('templates.layouts.base')
@section('title')
    Результаты
@endsection
@push('style')
    
    table, th, td {
        border: 1px solid black;
        text-align:center;
        font-size:8px;
    }
    
@endpush
@section('content')

@include('templates.pdf.exam.exam-statement', ['exam' => $exam, 'statementTable' => $statementTable])
<div class="page-break"></div>
@include('templates.pdf.exam.exam-marks', ['exam' => $exam, 'markTable' => $markTable])
@endsection