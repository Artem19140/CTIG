@extends('templates.layouts.base')

@section('content')
    @include('templates.pdf.enrollment.statement.enrollment-statement-body', ['enrollment' => $enrollment])
    <div class="page-break"></div>
    @include('templates.pdf.enrollment.approval.enrollment-approval-body', ['foreignNational' => $enrollment->foreignNational])
@endsection