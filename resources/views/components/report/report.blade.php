@props(['title' => null, $format => null])

<!DOCTYPE html>
@php
    if (isset($format) && $format == 'xls') {
        header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=" . $title . ".xls");  //File name extension was wrong
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private",false);
    }
@endphp
<html>
    <head>
        <meta charset="UTF-8">
        <title>{{ $title }}</title>
        @if (!isset($format) || $format == 'html')
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
            <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
            <style type="text/css">
                body {
                    margin: 0 auto;
                }
                body, td, th {
                    font-family: 'Source Sans Pro', sans-serif;
                    font-size: 12px;
                }
                td, th {
                    vertical-align: top;
                }
                th {
                    text-align: center;
                }
                .font-normal th {
                    font-weight: normal;
                    vertical-align: middle;
                }
                .header td, .header th {
                    font-size: 16px;
                }
                .mid td, .mid th {
                    font-size: 14px;
                }
                .center td, .center th {
                    text-align: center;
                    vertical-align: middle;
                }
                table.border {
                    border-collapse: collapse;
                }
                table.border td, table.border th {
                    border: 1px solid black;
                }
                tr.noborder td, tr.noborder th {
                    border: none;
                }
                hr {
                    margin: 10px auto;
                    border: 1px solid black;
                    border-width: 1px 0 0 0;
                }
                .text-center {
                    text-align: center;
                }
                .btn-flat {
                    -webkit-border-radius: 0;
                    -moz-border-radius: 0;
                    border-radius: 0;
                    -webkit-box-shadow: none;
                    -moz-box-shadow: none;
                    box-shadow: none;
                    border-width: 1px;
                }
                .btn.btn-primary {
                    background-color: #0052A2;
                    border-color: #008D4C;
                }
                .btn.btn-primary:hover, .btn.btn-primary:active, .btn.btn-primary.hover {
                    background-color: #008D4C;
                }
                .bg-info {
                    background-color: #D9EDF7;
                }
                .bg-success {
                    background-color: #DFF0D8;
                }

                .title{
                    font-size: 14px;
                    font-weight: bold;
                }
                .padded th, .padded td {
                    padding: 4px;
                }
                @media print {
                    .col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 {
                        float: left;
                    }

                    .pagebreak {
                        page-break-before: always;
                    }

                    #button-cetak {
                        display: none;
                    }
                    .col-md-12 {
                        width: 100%;
                    }
                    .col-md-11 {
                        width: 91.66666666666666%;
                    }
                    .col-md-10 {
                        width: 83.33333333333334%;
                    }
                    .col-md-9 {
                        width: 75%;
                    }
                    .col-md-8 {
                        width: 66.66666666666666%;
                    }
                    .col-md-7 {
                        width: 58.333333333333336%;
                    }
                    .col-md-6 {
                        width: 50%;
                    }
                    .col-md-5 {
                        width: 41.66666666666667%;
                    }
                    .col-md-4 {
                        width: 33.33333333333333%;
                    }
                    .col-md-3 {
                        width: 25%;
                    }
                    .col-md-2 {
                        width: 16.666666666666664%;
                    }
                    .col-md-1 {
                        width: 8.333333333333332%;
                    }
                }
            </style>
            <script>
                function display() {
                   window.print();
                }
            </script>
        @endif
    </head>
    <body>
        @if (!isset($format) || $format == 'html')
            <div class="d-flex justify-content-end pe-5" id="button-cetak">
                <button type="button" class="btn btn-info btn-flat" id="button-cetak" data-type="print" onclick="display()">Cetak</button>
            </div>
        @endif

        <div align="center">
            <div style="width:90%">
                @if (!isset($format) || $format == 'html')
                    <x-report.header-report></x-report.header-report>
                @endif
                <u>
                    <h5 style="font-size: 13pt;">{!!$title!!}</h5>
                </u>
                @yield('content')
            </div>
        </div>

    </body>
</html>
