@extends('templates.layouts.base')

@push('style')
    
    table, th, td {
        border: 1px solid black;
        text-align:center;
    }
    th{
        font-size:11px;
    }
    
@endpush
@section('content')

@include('templates.pdf.exam.exam-statement', ['data' => $data, 'columns' => $columns])
<div class="page-break"></div>
@include('templates.pdf.exam.exam-marks', ['exam' => $exam])
@endsection