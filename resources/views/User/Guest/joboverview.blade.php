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
                                            <p><b>PRE-EMPLOYMENT DATE: {{$employment_date}}</b></p>
                                            <p><b>INITIAL INTERVIEW DATE {{$initial_date}}</b></p>
                                            <p><b>FINAL INTERVIEW DATE: {{$final_date}}</b></p>
                                        </div>
                                        <div class="col-6">
                                            <p>{{$competency_date}}</p>
                                            <p>{{$employment_date}}</p>
                                            <p>{{$initial_date}}</p>
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
                                                    @if ($hiring_status != 'Initial Interview')
                                                        @if ($applicant['application_status'] != 'Failed')
                                                            @if (!empty($applicant['initial_result']))
                                                                <p class="{{ $applicant['initial_result'] === 'Failed' ? 'text-danger' : ($applicant['initial_result'] === 'Passed' ? 'text-success' : '') }}">
                                                                    {{ $applicant['initial_result'] }}
                                                                </p>
                                                            @endif
                                                                @if ($applicant['applicantId'] != NULL)
                                                                <a class="btn" href="{{route('generateBEI', ['applicantID' => $applicant['applicant_id']])}}" target="_blank">View BEI</a>
                                                            @else
                                                                <a href="{{ route('IndividualBEIGuest', ['applicantID' => $applicant['applicant_id']]) }}">Initial Interview</a>
                                                            @endif
                                                        @endif
                                                    @endif
                                                </td>                                                  
                                                <td>
                                                    @if ($hiring_status != 'Final Interview')
                                                    <button class="btn" data-bs-toggle="modal" type="button" data-bs-target="#finalModal">Update</button>
                                                    <div class="modal fade" id="finalModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Final Interview Result</h5>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <h6><b>NAME: </b>{{$applicant['user_name']}}</h6>
                                                                    <form action="" method="POST">
                                                                        @csrf
                                                                        <div class="my-3">
                                                                            <select class="form-control" name="" id="">Select Result
                                                                                <option value="">Passed</option>
                                                                                <option value="">Failed</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="text-center">
                                                                            <button type="submit" class="btn text-white" style="background-color:rgb(0, 0, 226)">Submit</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
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