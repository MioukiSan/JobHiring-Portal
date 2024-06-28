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
                            <td><p>Date: <span style="text-decoration: underline;">
                                {{$beiDate}}
                            @if ($jobType === 'Permanent')
                                {{$beiDate}}
                            @else
                                {{$initialInterview}}
                            @endif
                            </span></p></td>
                        </tr>
                    </tbody>
                </table>
            </div>
                @foreach ($beiDatas as $beiData)
                <div class="table table-bordered table-custom">
                    <table class="table">
                        <thead>
                            <tr class="text-center">
                                <th rowspan="2">COMPETENCIES</th>
                                <th rowspan="2">RATING</th>
                                <th colspan="3">DOCUMENTATION NOTES</th>
                            </tr>
                            <tr class="tr-custom text-center">
                                <th>SITUATION OR TASK/S</th>
                                <th>ACTION/S</th>
                                <th>RESULT/S</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($beiData['sgID'] >= 1 && $beiData['sgID'] <= 24)
                                @foreach ($beiData['competencies'] as $key => $competency)
                                    @if ($key !== 'leadership')
                                        <tr>
                                            <td>{{ $competency['label'] }}</td>
                                            @foreach ($competency['data'] as $item)
                                            {{-- @php
                                            $totalRating = 0;
                                            $totalCount = 0;
                                            
                                                if (is_numeric($competency['data'])) {
                                                    $totalRating += intval($competency['data']);
                                                    $totalCount++;
                                                }
                                            @endphp --}}
                                                <td>{{ $item }}</td>
                                            @endforeach
                                        </tr>
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center">LEADERSHIP COMPETENCIES</td>
                                        </tr>
                                        @foreach ($competency as $subKey => $subCompetency)
                                            <tr>
                                                <td>{{ $subCompetency['label'] }}</td>
                                                @foreach ($subCompetency['data'] as $item)
                                                    <td>{{ $item }}</td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif


    
                            {{-- @if ($beiData['sgID'] >= 18 && $beiData['sgID'] <= 24)
                                <tr>
                                    <td colspan="5" class="text-center">LEADERSHIP COMPETENCIES</td>
                                </tr>
                                @if (array_key_exists('leadership', $beiData['competencies']))
                                    @foreach ($beiData['competencies']['leadership'] as $leadership)
                                        <tr>
                                            <td>{{ $leadership['label'] }}</td>
                                            @foreach ($leadership['data'] as $item)
                                                <td>{{ $item }}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                @endif
                            @endif --}}
                        </tbody>
                    </table>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</body>
</html>
