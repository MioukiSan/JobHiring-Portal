e@extends('layouts.app')
@section("title", "Initial Interview")
@section("content")
<div class="container">
    <div class="row">
        <div class="col-mb-12 my-4">
            @php
                $competenciesSG1to6 = [
                    'DEPENDABILITY', 
                    'CREATIVE & INNOVATIVE THINKING', 
                    'INITIATIVE', 
                    'THE MANAGEMENT', 
                    'PLANNING & ORGANIZING'
                ];

                $competenciesSG9to16 = [
                    'DEPENDABILITY', 
                    'ADAPTABILITY', 
                    'CREATIVE & INNOVATIVE THINKING', 
                    'TEAMWORK', 
                    'SELF MANAGEMENT', 
                    'ORGANIZATIONAL AWARENESS', 
                    'COMMUNICATION', 
                    'INITIATIVE', 
                    'SERVICE DELIVERY', 
                    'CUSTOMER FOCUS'
                ];

                $competenciesSG18to24 = [
                    'CREATIVE & INNOVATIVE THINKING', 
                    'TEAMWORK', 
                    'SELF MANAGEMENT', 
                    'MANAGING PROJECTS OR PROGRAMS', 
                    'STAFF MANAGEMENT', 
                    'ORGANIZATIONAL AWARENESS', 
                    'STRATEGIC PLANNING', 
                    'MONITORING AND EVALUATING', 
                    'PLANNING, ORGANISING & DELIVERY', 
                    'SERVICE DELIVERY'
                ];

                $leadershipCompetencies = [
                    'THINKING STRATEGICALLY & CREATIVELY', 
                    'LEADING CHANGE', 
                    'BUILDING COLLABORATIVE INCLUSIVE WORKING RELATIONSHIPS', 
                    'MANAGING PERFORMANCE AND COACHING FOR RESULTS', 
                    'CREATING & NURTURING A HIGH PERFORMING ORGANISATION'
                ];
            @endphp
            @if ($salary_grade >= 1 && $salary_grade <= 8)
            <div class="card">
                <div class="card-header">
                    <select class="form-select" name="name">
                        <option value="" disabled selected>Select Applicant</option>
                        @foreach ($applicants as $applicant)
                            <option value="{{$applicant['user_name']}}">{{$applicant['user_name']}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="card-body">
                    <form action="" method="POST">
                        @csrf
                        @foreach ($competenciesSG1to6 as $competency)
                            <h5>{{ $competency }}</h5>
                            <div class="row">
                                <div class="col-2 mb-3">
                                    <div class="form-floating">
                                        <input type="number" class="form-control" name="{{ $competency }}_rating" placeholder="Rating">
                                        <label for="{{ $competency }}_rating">Rating</label>
                                    </div>
                                </div>
                                <div class="col-4 mb-3">
                                    <div class="form-floating">
                                        <textarea class="form-control" name="{{ $competency }}_situation" placeholder="Situation or Task/s"></textarea>
                                        <label for="{{ $competency }}_situation">Situation or Task/s</label>
                                    </div>
                                </div>
                                <div class="col-3 mb-3">
                                    <div class="form-floating">
                                        <textarea class="form-control" name="{{ $competency }}_action" placeholder="Action/s"></textarea>
                                        <label for="{{ $competency }}_action">Action/s</label>
                                    </div>
                                </div>
                                <div class="col-3 mb-3">
                                    <div class="form-floating">
                                        <textarea class="form-control" name="{{ $competency }}_result" placeholder="Result/s"></textarea>
                                        <label for="{{ $competency }}_result">Result/s</label>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="text-center mt-2">
                            <button type="submit" class="btn btn-primary" name="submit_application" title="submit BEI">SUBMIT</button>
                        </div>
                    </form>
                </div>
            </div>
        @elseif ($salary_grade >= 9 && $salary_grade <= 17)
            <!-- Same structure for SG 9 to 16 -->
        @elseif ($salary_grade >= 18 && $salary_grade <= 24)
            <!-- Same structure for SG 17 to 24 -->
        @endif
        
        @if ($salary_grade >= 1 && $salary_grade <= 8)
        <div class="card">
            <div class="card-header">
                <select class="form-select" name="name">
                    <option value="" disabled selected>Select Applicant</option>
                    @foreach ($applicants as $applicant)
                        <option value="{{$applicant['user_name']}}">{{$applicant['user_name']}}</option>
                    @endforeach
                </select>
            </div>
            <div class="card-body">
                <form action="" method="POST">
                    @csrf
                    @foreach ($competenciesSG1to6 as $competency)
                        <h5>{{ $competency }}</h5>
                        <div class="row">
                            <div class="col-2 mb-3">
                                <div class="form-floating">
                                    <input type="number" class="form-control" name="{{ $competency }}_rating" placeholder="Rating">
                                    <label for="{{ $competency }}_rating">Rating</label>
                                </div>
                            </div>
                            <div class="col-4 mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="{{ $competency }}_situation" placeholder="Situation or Task/s">
                                    <label for="{{ $competency }}_situation">Situation or Task/s</label>
                                </div>
                            </div>
                            <div class="col-3 mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="{{ $competency }}_action" placeholder="Action/s">
                                    <label for="{{ $competency }}_action">Action/s</label>
                                </div>
                            </div>
                            <div class="col-3 mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="{{ $competency }}_result" placeholder="Result/s">
                                    <label for="{{ $competency }}_result">Result/s</label>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="text-center mt-2">
                        <button type="submit" class="btn btn-primary" name="submit_application" title="submit BEI">SUBMIT</button>
                    </div>
                </form>
            </div>
        </div>
        @elseif ($salary_grade >= 9 && $salary_grade <= 17)
        <div class="card">
            <div class="card-header">
                <select class="form-select" name="name">
                    <option value="" disabled selected>Select Applicant</option>
                    @foreach ($applicants as $applicant)
                        <option value="{{$applicant['user_name']}}">{{$applicant['user_name']}}</option>
                    @endforeach
                </select>
            </div>
            <div class="card-body">
                <form action="" method="POST">
                    @csrf
                    @foreach ($competenciesSG9to16 as $competency)
                        <h5>{{ $competency }}</h5>
                        <div class="row">
                            <div class="col-2 mb-3">
                                <div class="form-floating">
                                    <input type="number" class="form-control" name="{{ $competency }}_rating" placeholder="Rating">
                                    <label for="{{ $competency }}_rating">Rating</label>
                                </div>
                            </div>
                            <div class="col-4 mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="{{ $competency }}_situation" placeholder="Situation or Task/s">
                                    <label for="{{ $competency }}_situation">Situation or Task/s</label>
                                </div>
                            </div>
                            <div class="col-3 mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="{{ $competency }}_action" placeholder="Action/s">
                                    <label for="{{ $competency }}_action">Action/s</label>
                                </div>
                            </div>
                            <div class="col-3 mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="{{ $competency }}_result" placeholder="Result/s">
                                    <label for="{{ $competency }}_result">Result/s</label>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="text-center mt-2">
                        <button type="submit" class="btn btn-primary" name="submit_application" title="submit BEI">SUBMIT</button>
                    </div>
                </form>
            </div>
        </div>
        @elseif ($salary_grade >= 18 && $salary_grade <= 24)
        <div class="card">
            <div class="card-header">
                <select class="form-select" name="name">
                    <option value="" disabled selected>Select Applicant</option>
                    @foreach ($applicants as $applicant)
                        <option value="{{$applicant['user_name']}}">{{$applicant['user_name']}}</option>
                    @endforeach
                </select>
            </div>
            <div class="card-body">
                <form action="" method="POST">
                    @csrf
                    @foreach ($competenciesSG9to16 as $competency)
                        <h5>{{ $competency }}</h5>
                        <div class="row">
                            <div class="col-2 mb-3">
                                <div class="form-floating">
                                    <input type="number" class="form-control" name="{{ $competency }}_rating" placeholder="Rating">
                                    <label for="{{ $competency }}_rating">Rating</label>
                                </div>
                            </div>
                            <div class="col-4 mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="{{ $competency }}_situation" placeholder="Situation or Task/s">
                                    <label for="{{ $competency }}_situation">Situation or Task/s</label>
                                </div>
                            </div>
                            <div class="col-3 mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="{{ $competency }}_action" placeholder="Action/s">
                                    <label for="{{ $competency }}_action">Action/s</label>
                                </div>
                            </div>
                            <div class="col-3 mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="{{ $competency }}_result" placeholder="Result/s">
                                    <label for="{{ $competency }}_result">Result/s</label>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        <div class="text-center my-4">
                            <h5 class="fw-bold">LEADERSHIP COMPETENCIES</h5>
                        </div>
                        @foreach ($leadershipCompetencies as $competencies)
                        <h5>{{$competencies}}</h5>
                        <div class="row">
                            <div class="col-2 mb-3">
                                <div class="form-floating">
                                    <input type="number" class="form-control" name="{{$competencies}}rating" placeholder="Rating">
                                    <label for="floatingInputGroup1">Rating</label>
                                </div>
                            </div>
                            <div class="col-4 mb-3">
                                <div class="form-floating">
                                    <input type="textarea" class="form-control" name="{{$competencies}}situation" placeholder="Situation or Task/s">
                                    <label for="floatingInputGroup1">Situation or Task/s</label>
                                </div>
                            </div>
                            <div class="col-3 mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="{{$competencies}}action" placeholder="Action/s">
                                    <label for="floatingInputGroup1">Action/s</label>
                                </div>
                            </div>
                            <div class="col-3 mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="{{$competencies}}result" placeholder="Result/s">
                                    <label for="floatingInputGroup1">Result/s</label>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="text-center mt-2">
                        <button type="submit" class="btn btn-primary" name="submit_application" title="submit BEI">SUBMIT</button>
                    </div>
                </form>
            </div>
        </div>
        @endif
        </div>
    </div>
</div>
@endsection