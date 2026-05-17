<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 20px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .no-border {
            border: none;
        }

        .field {
            border-bottom: 1px solid black;
            padding-bottom: 2px;
        }

        .text-small {
            font-size: 10px;
        }

        .text-center {
            text-align: center;
        }

        .center{
            text-align: center;
        }

        .data{
            font-weight: bold;
        }

        .bold{
            font-weight: bold;
        }

        .underline{
            text-decoration: underline;
        }
        
        .border{
            border: 1px solid black;
        }
        .page-break {
            page-break-before: always;
        }

        .break-avoid{
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .mt-10{
            margin-top: 10px;
        }

        .mb-10{
            margin-bottom: 10px;
        }

        .mt-20{
            margin-top: 20px;
        }

        .mb-20{
            margin-bottom: 20px;
        }
        @stack('style')  
    </style>
</head>
<body>
    @yield('content')  
</body>
</html>