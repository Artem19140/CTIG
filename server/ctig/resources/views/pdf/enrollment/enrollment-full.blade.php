@extends('pdf.layouts.base')
@section('title')
    Заявление
@endsection
@section('content')
    @include('pdf.enrollment.statement.enrollment-statement-body', ['enrollment' => $enrollment])
        <div class="page-break"></div>
    @include('pdf.enrollment.approval.enrollment-approval-body', ['foreignNational' => $enrollment->foreignNational])
@endsection