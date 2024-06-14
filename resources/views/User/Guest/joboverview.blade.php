@extends('layouts.app')
@section('title', 'Job Hiring')
@section("content")
<div class="container">
    <div class="row">
        <div class="col-lg-12 col-sm-6">
            <div class="card my-5">
                <div class="card-header">
                    Hiring & Applicants Details
                    @include('User.Guest.layouts.modal')
                </div>        
                <div class="card-body">
                    <div class="card-body">
                        <div class="row">
                            @if($contract_type == "COS")
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-3">
                                        <p><b>JOB POSITION:</b></p>
                                        <p><b>DESCRIPTION:</b></p>
                                        <p><b>STATUS:</b></p>
                                        <p><b>DEPARTMENT:</b></p>
                                    </div>
                                    <div class="col-9">
                                        <p>{{ $job_position }}</p>
                                        <p>
                                            Description File: <a href="{{ asset('storage/' . $job_description) }}"
                                                target="_blank">View File</a>
                                        </p>
                                        <p>{{ $hiring_status }}</p>
                                        <P>{{ $department }}</P>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-6">
                                        <p><b>COMPETENCY EXAM DATE: </b></p>
                                        <p><b>PRE-EMPLOYMENT DATE: {{$pre_employement_date}}</b></p>
                                        <p><b>INITIAL INTERVIEW DATE {{$initial_date}}</b></p>
                                        <p><b>FINAL INTERVIEW DATE: {{$final_date}}</b></p>
                                    </div>
                                    <div class="col-6">
                                        <p>{{$competency_date}}</p>
                                        <p>{{$pre_employement_date}}</p>
                                        <p>{{$initial_date}}</p>
                                        <p>{{$final_date}}</p>
                                    </div>
                                </div>
                            </div>
                            @elseif ($contract_type == "Permanent")
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-3">
                                        <p><b>JOB POSITION:</b></p>
                                        <p><b>DESCRIPTION:</b></p>
                                        <p><b>STATUS:</b></p>
                                        <p><b>DEPARTMENT:</b></p>
                                    </div>
                                    <div class="col-9">
                                        <p>{{ $job_position }}</p>
                                        <p>
                                            Description File: <a href="{{ asset('storage/' . $job_description) }}"
                                                target="_blank">View File</a>
                                        </p>
                                        <p>{{ $hiring_status }}</p>
                                        <P>{{ $department }}</P>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-5">
                                        <p><b>BEI DATE: </b></p>
                                        @if ($job_type == "SRS-1" || $job_type == "Entry")
                                            <p><b>PRE-EMPLOYMENT DATE: </b></p>
                                        @else
                                        @endif
                                        <p><b>PSYCHOMETRIC TEST DATE: {{$psycho_date}}</b></p>
                                        <p><b>FINAL INTERVIEW DATE: {{$final_date}}</b></p>
                                    </div>
                                    <div class="col-7">
                                        <p>{{$bei_date}}</p>
                                        @if ($job_type == "SRS-1" || $job_type == "Entry")
                                            <p>{{$pre_employment_date}}</p>
                                        @else
                                        @endif
                                        <p>{{$psycho_date}}</p>
                                        <p>{{$final_date}}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="col-12 my-3">
                                <div class="table">
                                    <table class="table table-responsive text-center">
                                        <thead class="table-primary">
                                            @if($contract_type == "COS")
                                            <tr>
                                                <th>Name</th>
                                                @if (Auth::user()->usertype === 'admin' || Auth::user()->usertype === 'hr' )
                                                   <th>Application Status</th>
                                                @else
                                                @endif
                                                <th>Competency Exam</th>
                                                <th>Pre-Employment Exam</th>
                                                <th>Initial Interview</th>
                                                <th>Final Interview</th>
                                                <th>Requirements</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($applicants as $applicant)
                                            <tr>
                                                <td>{{ $applicant['user_name'] }}</td>
                                                @if (Auth::user()->usertype === 'admin' || Auth::user()->usertype === 'hr' )
                                                    <td>
                                                        {{ $applicant['application_status'] }}
                                                    </td>
                                                @else
                                                @endif
                                                <td>
                                                @if (!empty($applicant['competency']))  
                                                    <p class="{{ $applicant['competency_result'] === 'Not Selected' ? 'text-danger' : ($applicant['competency_result'] === 'Passed' ? 'text-success' : '') }}">
                                                        {{ $applicant['competency_result'] }}
                                                    </p>
                                                    <p>Exam File: <a href="{{ asset('storage/' . $applicant['competency']) }}" target="_blank">View</a></p>
                                                @endif
                                                </td>
                                                <td>
                                                    @if (!empty($applicant['pre_result']))  
                                                    <p class="{{ $applicant['pre_result'] === 'Not Selected' ? 'text-danger' : ($applicant['competency_result'] === 'Passed' ? 'text-success' : '') }}">
                                                        {{ $applicant['pre_result'] }}
                                                    </p>
                                                    <p>Exam File: <a href="{{ asset('storage/' . $applicant['pre_employment']) }}" target="_blank">View</a></p>
                                                    @endif
                                                </td>
                                                <td>
                                                @if (!empty($applicant['initial_result']))  {{$applicant['initial_result']}}
                                                    <a href="{{ asset('storage/' . $applicant['initial']) }}" target="_blank">Interview File</a>
                                                @endif
                                                </td>                                                  
                                                <td>
                                                    {{$applicant['final']}}
                                                </td>
                                                <td>
                                                    @include('User.Guest.layouts.applicationModal')                                 
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        @elseif ($contract_type == "Permanent")
                                            <tr>
                                                <th>Name</th>
                                                @if (Auth::user()->usertype === 'admin' || Auth::user()->usertype === 'hr' )
                                                   <th>Application Status</th>
                                                @else
                                                @endif
                                                @if ($job_type == 'SRS-1' || $job_type == 'Entry')
                                                <th>Pre-Employment Exam</th>
                                                @else
                                                @endif
                                                <th>Behavioral Event Interview</th>
                                                <th>Psychometric Test</th>
                                                <th>Final Interview</th>
                                                <th>Requirements</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($applicants as $applicant)
                                            <tr>
                                                <td>{{ $applicant['user_name'] }}</td>
                                                @if (Auth::user()->usertype === 'admin' || Auth::user()->usertype === 'hr' )
                                                    <td>
                                                        {{ $applicant['application_status'] }}
                                                    </td>
                                                @else
                                                @endif
                                                @if ($job_type == 'SRS-1' || $job_type == 'Entry')
                                                    <td>
                                                        @if (!empty($applicant['pre_result']))  
                                                        <p class="{{ $applicant['pre_result'] === 'Not Selected' ? 'text-danger' : ($applicant['competency_result'] === 'Passed' ? 'text-success' : '') }}">
                                                            {{ $applicant['pre_result'] }}
                                                        </p>
                                                        <p>Exam File: <a href="{{ asset('storage/' . $applicant['pre_employment']) }}" target="_blank">View</a></p>
                                                        @endif
                                                    </td>
                                                @else
                                                @endif
                                                <td>
                                                @if (!empty($applicant['bei_status']))
                                                    {{ $applicant['bei_status'] }}
                                                    <a href="{{route('generateBEI')}}">View BEI</a>
                                                @endif
                                                </td>
                                                <td>
                                                @if (!empty($applicant['psycho_result']))
                                                    {{ $applicant['psycho_result'] }}
                                                    <a href="{{ asset('storage/' . $applicant['psycho']) }}" target="_blank">Exam File</a>
                                                @endif
                                                </td>                                                  
                                                <td>
                                                    {{$applicant['final']}}
                                                </td>
                                                <td>
                                                    @include('User.Guest.layouts.applicationModal')                                          
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection