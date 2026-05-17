@extends('templates.layouts.base')

@section('content')
    @include('templates.pdf.enrollment.statement.enrollment-statement-body', ['enrollment' => $enrollment])
@endsection