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
    </style>
</head>
<body>
    @php
    $competenciesSG1to6 = [
    ['name' => 'DEPENDABILITY', 'labelDB' => 'dependability'],
    ['name' => 'CREATIVE & INNOVATIVE THINKING', 'labelDB' => 'creative'],
    ['name' => 'INITIATIVE', 'labelDB' => 'initiative'],
    ['name' => 'TIME MANAGEMENT', 'labelDB' => 'time_management'],
    ['name' => 'PLANNING & ORGANIZING', 'labelDB' => 'planning']
    ];

    $competenciesSG9to16 = [
        ['name' => 'DEPENDABILITY', 'labelDB' => 'dependability'],
        ['name' => 'ADAPTABILITY', 'labelDB' => 'adaptability'],
        ['name' => 'CREATIVE & INNOVATIVE THINKING', 'labelDB' => 'creative'],
        ['name' => 'TEAMWORK', 'labelDB' => 'teamwork'],
        ['name' => 'SELF MANAGEMENT', 'labelDB' => 'self_management'],
        ['name' => 'ORGANIZATIONAL AWARENESS', 'labelDB' => 'org_awareness'],
        ['name' => 'COMMUNICATION', 'labelDB' => 'communication'],
        ['name' => 'INITIATIVE', 'labelDB' => 'initiative'],
        ['name' => 'SERVICE DELIVERY', 'labelDB' => 'service_delivery'],
        ['name' => 'CUSTOMER FOCUS', 'labelDB' => 'customer_focus']
    ];

    $competenciesSG18to24 = [
        ['name' => 'CREATIVE & INNOVATIVE THINKING', 'labelDB' => 'creative'],
        ['name' => 'TEAMWORK', 'labelDB' => 'teamwork'],
        ['name' => 'SELF MANAGEMENT', 'labelDB' => 'self_management'],
        ['name' => 'MANAGING PROJECTS OR PROGRAMS', 'labelDB' => 'management'],
        ['name' => 'STAFF MANAGEMENT', 'labelDB' => 'staff_management'],
        ['name' => 'ORGANIZATIONAL AWARENESS', 'labelDB' => 'org_awareness'],
        ['name' => 'STRATEGIC PLANNING', 'labelDB' => 'strategic_planning'],
        ['name' => 'MONITORING AND EVALUATING', 'labelDB' => 'monitor_evaluation'],
        ['name' => 'PLANNING, ORGANISING & DELIVERY', 'labelDB' => 'planning'],
        ['name' => 'SERVICE DELIVERY', 'labelDB' => 'service_delivery']
    ];

    $leadershipCompetencies = [
        ['name' => 'THINKING STRATEGICALLY & CREATIVELY', 'labelDB' => 'strategy_creatively'],
        ['name' => 'LEADING CHANGE', 'labelDB' => 'leading_change'],
        ['name' => 'BUILDING COLLABORATIVE INCLUSIVE WORKING RELATIONSHIPS', 'labelDB' => 'building_relationship'],
        ['name' => 'MANAGING PERFORMANCE AND COACHING FOR RESULTS', 'labelDB' => 'coaching'],
        ['name' => 'CREATING & NURTURING A HIGH PERFORMING ORGANISATION', 'labelDB' => 'creating_nurture_performance']
    ];
    $salary_grade = 1;
    @endphp
    @if ($salary_grade >= 1 && $salary_grade <= 8)
        <h3>Competencies for SG 1 to 8</h3>
        <ul>
            @foreach($competenciesSG1to6 as $competency)
                <li>{{ $competency['name'] }}</li>
                <li>{{ $competency['labelDB'] }}</li>
            @endforeach
        </ul>
    @endif

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
                                <p>Interviewee Name:  <a href="#" class="link-dark link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover">Juan Dela Cruz</a></p>
                            </td>
                            <td>Position Applied for: Graphic Designing</td>
                            <td>Date: 01-04-2924</td>
                        </tr>
                    </tbody>
                </table>
            </div>
                <table class="table table-bordered table-sm table-custom">
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
                        <tr class="text-center">
                            <td>Ability to work under pressure</td>
                            <td>4</td>
                            <td>
                                <p>
                                    <ul>
                                        <li>I was able to work under pressure.</li>
                                        <li>I was not able to work under pressure.</li>
                                        <li>I was able to work under pressure.</li>
                                        <li>I was able to work under pressure.</li>
                                        <li>I was able to work under pressure.</li>
                                        <li>I was able to work under pressure.</li>
                                    </ul>
                                </p>
                            </td>
                            <td>
                                Supp
                            </td>
                            <td>
                                Yes
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
