<!DOCTYPE html>
<html>
<head>
    <title>BEI RESULT PDF</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        .table-custom {
            border-left: 0.01em solid #929292;
            border-right: 0;
            border-top: 0.01em solid #929292;
            border-bottom: 0.01em solid #929292;
            border-collapse: collapse;
        }
        .table-custom td,
        .table-custom th {
            border-left: 0;
            border-right: 0.01em solid #929292;
            border-top: 0;
            border-bottom: 0.01em solid #929292;
        }
        .table-custom td{
            padding: 1em;
        }
    </style>
</head>
<body>
    @php
        $salary_grade = $sgID;
    @endphp
    <div class="container-fluid">
        <div class="row">
            <div class="col-mb-12">
                <div class="header-text text-center">
                    <small>Department of Science and Technology</br>Regional Office V</small>
                    <h6 class="bold">BEHAVIORAL EVENT INTERVIEW RATING SHEET</h6>
                </div>
            </div>
            <div class="table  table-bordered">
                <table class="table">
                    <tbody>
                        <tr>
                            <td>
                                <p>Interviewee Name: <span style="text-decoration: underline">{{$name}}</span></p>
                            </td>
                            <td><p>Position Applied for: <span style="text-decoration: underline;">{{$position}}</span></p></td>
                            <td><p>Date: <span style="text-decoration: underline;">{{$beiDate}}</span></p></td>
                        </tr>
                    </tbody>
                </table>
            </div>
                <table class="table table-bordered table-custom">
                    <thead>
                        <tr class="text-center">
                            <th>COMPETENCIES</th>
                            <th>RATING</th>
                            <th colspan="3">DOCUMENTATION NOTES</th>
                        </tr>
                        <tr class="tr-custom text-center">
                            <th colspan="2"></th>
                            <th>SITUATION OR TASK/S</th>
                            <th>ACTION/S</th>
                            <th>RESULT/S</th>
                        </tr>
                    </thead>
                    <tbody>
                            @if ($salary_grade >= 1 && $salary_grade <= 17)
                                @foreach($beiDatas as $data)
                                    <tr>
                                        <td>{{ $data['label'] }}</td>
                                        {{-- @php
                                            $item = $data['data'];
                                        @endphp --}}
                                        @foreach ($data['data'] as $item)
                                            <td>{{$item}}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
