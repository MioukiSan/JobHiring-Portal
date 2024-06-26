@extends('adminlte::page', ['main-sidebar' => false])

@section('title', 'Applicants | View')

@section('content_header')
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-6"></div>
            <div class="col-6">
                <ol class="breadcrumb float-sm-right" style="background: none;">
                    <li class="breadcrumb-item"><a href="{{url('applicants')}}">Applicants</a></li>
                    <li class="breadcrumb-item active">{{$job_position}}</li>
                </ol>
            </div>
            <div class="col-12">
                <div class="card">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                   <p>{{ $error }}</p>
                                @endforeach
                            </ul>
                        </div>
                        @elseif (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="card-header">Hiring & Applicants Details
                    @if ($hiring_status === 'Closed' && Auth::user()->usertype === 'hr')
                    <div class="card-tools">
                        <x-adminlte-button class="btn-sm" label="Click to View" data-toggle="modal" data-target="#SelectApplicants" />
                        <x-adminlte-modal id="SelectApplicants" title="Select Applicants" v-centered>
                            <div class="row">
                                <div class="col-12">
                                    <div class="modal-body">
                                        <small>
                                            Instruction: Check the box if the applicant passed the Phase 1 shortlisting.
                                        </small>
                                        <div class="mb-3">
                                            <button type="button" class="btn btn-sm float-end" id="selectAll">Select All</button>
                                        </div>
                                        <p><b>Applicants</b></p>
                                        <form method="POST" action="{{route('applicants.shortlist', ['hiringID' => $hiring_id])}}">
                                          @csrf
                                          @foreach ($applicants as $select)
                                              <div class="form-check">
                                                  <input class="form-check-input" type="checkbox" value="{{ $select['applicant_id'] }}" name="applicantSelected[]" id="applicantSelected{{ $select['applicant_id'] }}">
                                                  <label class="form-check-label" for="applicantSelected{{ $select['applicant_id'] }}">
                                                      {{ $select['user_name'] }}
                                                  </label>
                                              </div>
                                          @endforeach
                                          <div class="text-center">
                                            <button type="submit" class="btn btn-primary mt-3">Submit</button>
                                          </div>
                                      </form>
                                    </div>
                                </div>
                            </div>
                        </x-adminlte-modal>
                    </div>
                    @endif
                    <div class="card-body">
                        <div class="row">
                            @if($contract_type == "COS")
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-6">
                                        <p><b>JOB POSITION:</b></p>
                                        <p><b>DESCRIPTION:</b></p>
                                        <p><b>STATUS:</b></p>
                                        <p><b>DEPARTMENT:</b></p>
                                    </div>
                                    <div class="col-6">
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
                                        <p><b>PRE-EMPLOYMENT DATE: </b></p>
                                        <p><b>INITIAL INTERVIEW DATE </b></p>
                                        <p><b>FINAL INTERVIEW DATE: </b></p>
                                    </div>
                                    <div class="col-6">
                                        <p>{{$competency_date}}</p>
                                        <p>{{$employment_date}}</p>
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
                                        @if ($job_type == "SRS-1" || $job_type == "Entry")
                                            <p><b>PRE-EMPLOYMENT DATE: </b></p>
                                        @else
                                        @endif
                                        <p><b>BEI DATE: </b></p>
                                        <p><b>PSYCHOMETRIC TEST DATE: </b></p>
                                        <p><b>FINAL INTERVIEW DATE: </b></p>
                                    </div>
                                    <div class="col-7">
                                        @if ($job_type == "SRS-1" || $job_type == "Entry")
                                            <p>{{$employment_date}}</p>
                                        @else
                                        @endif
                                        <p>{{$bei_date}}</p>
                                        <p>{{$psycho_date}}</p>
                                        <p>{{$final_date}}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="col-12">
                                <div class="table">
                                    <table class="table table-striped text-center">
                                        <thead>
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
                                                @if (Auth::user()->usertype != 'selection board')
                                                <th>Action</th>
                                                @else
                                                @endif
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
                                                    <p class="{{ $applicant['competency_result'] === 'Failed' ? 'text-danger' : ($applicant['competency_result'] === 'Passed' ? 'text-success' : '') }}">
                                                        {{ $applicant['competency_result'] }}
                                                    </p>
                                                    <p>Exam File: <a href="{{ asset('storage/' . $applicant['competency']) }}" target="_blank">View</a></p>
                                                @endif
                                                </td>
                                                <td>
                                                    @if (!empty($applicant['pre_result']))  
                                                    <p class="{{ $applicant['pre_result'] === 'Failed' ? 'text-danger' : ($applicant['competency_result'] === 'Passed' ? 'text-success' : '') }}">
                                                        {{ $applicant['pre_result'] }}
                                                    </p>
                                                    <p>Exam File: <a href="{{ asset('storage/' . $applicant['pre_employment']) }}" target="_blank">View</a></p>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($applicant['application_status'] != 'Failed')
                                                        @if (!empty($applicant['initial_result']))
                                                            <p class="{{ $applicant['initial_result'] === 'Failed' ? 'text-danger' : ($applicant['initial_result'] === 'Passed' ? 'text-success' : '') }}">
                                                                {{ $applicant['initial_result'] }}
                                                            </p>
                                                        @endif
                                                        @if ($applicant['applicantId'] != NULL)
                                                            <a href="{{route('generateBEI', ['applicantID' => $applicant['applicant_id']])}}" target="_blank">View BEI</a>
                                                        @endif
                                                    @endif
                                                </td>                                                  
                                                <td>
                                                    {{$applicant['final']}}
                                                </td>
                                                <td>
                                                    @include('Admin.layouts.applicationModal')                                 
                                                </td>
                                                @if (Auth::user()->usertype != 'selection board')
                                                <td>
                                                    @if($applicant['application_status'] != 'Passed')
                                                    @else
                                                        <x-adminlte-button label="Update" data-toggle="modal" data-target="#updateApplication{{ $applicant['user_id'] }}" />
                                                        @include('Admin.layouts.updateApplicationModal')          
                                                    @endif                             
                                                </td>
                                                @else
                                                @endif
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
                                                @if (Auth::user()->usertype != 'selection board')
                                                <th>Action</th>
                                                @else
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($applicants as $applicant)
                                            <tr>
                                                <td>{{ $applicant['user_name'] }}</td>
                                                @if (Auth::user()->usertype === 'admin' || Auth::user()->usertype === 'hr' )
                                                    <td>
                                                        <p class="{{ $applicant['application_status'] === 'Failed' ? 'text-danger' : ($applicant['application_status'] === 'Passed' ? 'text-success' : '') }}">
                                                            {{ $applicant['application_status'] }}
                                                        </p>
                                                    </td>
                                                @else
                                                @endif
                                                @if ($job_type == 'SRS-1' || $job_type == 'Entry')
                                                    <td>
                                                        @if (!empty($applicant['pre_result']))  
                                                        <p class="{{ $applicant['pre_result'] === 'Failed' ? 'text-danger' : ($applicant['competency_result'] === 'Passed' ? 'text-success' : '') }}">
                                                            {{ $applicant['pre_result'] }}
                                                        </p>
                                                        <p>Exam File: <a href="{{ asset('storage/' . $applicant['pre_employment']) }}" target="_blank">View</a></p>
                                                        @endif
                                                    </td>
                                                @else
                                                @endif
                                                <td>
                                                    @if (Auth::user()->usertype === 'selection board')
                                                        @if (!empty($applicant['bei']))
                                                            <p class="{{ $applicant['bei'] === 'Failed' ? 'text-danger' : ($applicant['bei'] === 'Passed' ? 'text-success' : '') }}">
                                                                {{ $applicant['bei'] }}
                                                            </p> 
                                                        @endif
                                                        @if ($applicant['selection_id'] === Auth::user()->id && $applicant['applicant_id'] != NULL)
                                                            <a href="{{route('generateBEI', ['applicantID' => $applicant['applicant_id']])}}" target="_blank">View BEI</a>
                                                        @else
                                                            <a href="{{ route('IndividualBEI', ['applicantID' => $applicant['applicant_id']]) }}">Add BEI</a>
                                                        @endif
                                                    @else
                                                        @if ($applicant['application_status'] != 'Failed')
                                                            @if (!empty($applicant['bei']))
                                                                <p class="{{ $applicant['bei'] === 'Failed' ? 'text-danger' : ($applicant['bei'] === 'Passed' ? 'text-success' : '') }}">
                                                                    {{ $applicant['bei'] }}
                                                                </p>
                                                            @endif
                                                            @if ($applicant['applicantId'] != NULL)
                                                                <a href="{{route('generateBEI', ['applicantID' => $applicant['applicant_id']])}}" target="_blank">View BEI</a>
                                                            @endif
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>
                                                @if (!empty($applicant['psycho_result']))
                                                    <p class="{{ $applicant['psycho_result'] === 'Failed' ? 'text-danger' : ($applicant['psycho_result'] === 'Passed' ? 'text-success' : '') }}">
                                                        {{ $applicant['psycho_result'] }}
                                                    </p> 
                                                    <a href="{{ asset('storage/' . $applicant['psycho']) }}" target="_blank">Exam File</a>
                                                @endif
                                                </td>                                                  
                                                <td>
                                                    <p class="{{ $applicant['final'] === 'Failed' ? 'text-danger' : ($applicant['final'] === 'Passed' ? 'text-success' : '') }}">
                                                        {{$applicant['final']}}
                                                    </p> 
                                                </td>
                                                <td>
                                                   @include('Admin.layouts.applicationModal')                    
                                                </td>
                                                @if (Auth::user()->usertype != 'selection board')
                                                <td>
                                                    @if($applicant['application_status'] != 'Passed')
                                                    @else
                                                        <x-adminlte-button label="Update" data-toggle="modal" data-target="#updateApplication{{ $applicant['user_id'] }}" />
                                                        @include('Admin.layouts.updateApplicationModal')          
                                                    @endif                           
                                                </td>
                                                @else
                                                @endif
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
@stop

@section('css')
@stop

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllBtn = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.form-check-input');

        selectAllBtn.addEventListener('click', function() {
            const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);

            if (allChecked) {
                checkboxes.forEach(checkbox => checkbox.checked = false);
                selectAllBtn.textContent = 'Select All';
            } else {
                checkboxes.forEach(checkbox => checkbox.checked = true);
                selectAllBtn.textContent = 'Unselect All';
            }
        });
    }); 
</script>
@stop
