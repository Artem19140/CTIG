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
        .border{
            border: 1px solid black;
        }
        .page-break {
            page-break-before: always;
        }
        @stack('style')  
    </style>
</head>
<body>
    @yield('content')  
</body>
</html>