@extends('layouts.app')
@section('title', 'Profile')
@section("content")
<div class="container">
    <div class="row">
        <div class="col-lg-12 col-sm-6">
            <div class="card my-5">
                <div class="card-header">
                    Hiring & Applicants Details
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
                                    <div class="col-5">
                                        <p><b>COMPETENCY EXAM DATE:</b></p>
                                        <p><b>PRE-EMPLOYMENT DATE:</b></p>
                                        <p><b>INITIAL INTERVIEW DATE</b></p>
                                        <p><b>FINAL INTERVIEW DATE:</b></p>
                                    </div>
                                    <div class="col-7">
                                        <p>{{ $closing }}</p>
                                        <p>
                                            @if ($job_type == "Entry")
                                                {{ $unique_exam_entry_date }}</p>
                                            @elseif ($job_type == "SRS")
                                                {{ $unique_exam_srs_date }}</p>
                                            @endif
                                        <p>{{ $unique_interview_date }}</p>
                                    </div>
                                </div>
                            </div>
                            @elseif ($contract_type == "Permanent" || $contract_type == "Permanent with Pre-Employment")
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
                                        <p><b>BEI DATE:</b></p>
                                        @if ($contract_type == "Permanent with Pre-Employment")
                                            <p><b>PRE-EMPLOYMENT DATE:</b></p>
                                        @else
                                        @endif
                                        <p><b>PSYCHOMETRIC TEST DATE:</b></p>
                                        <p><b>FINAL INTERVIEW DATE:</b></p>
                                    </div>
                                    <div class="col-7">
                                        <p>{{ $closing }}</p>
                                        <p>
                                            @if ($job_type == "Entry")
                                                {{ $unique_exam_entry_date }}</p>
                                            @elseif ($job_type == "SRS")
                                                {{ $unique_exam_srs_date }}</p>
                                            @endif
                                        <p>{{ $unique_interview_date }}</p>
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
                                                <th>Competency Exam</th>
                                                <th>Pre-Employment Exam</th>
                                                <th>Initial Interview</th>
                                                <th>Final Interview</th>
                                                <th>Requirements</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($applicants as $applicant)
                                            <tr>
                                                <td>{{ $applicant['user_name'] }}</td>
                                                <td>{{ $applicant['application_status'] }}</td>
                                                <td>
                                                    @if ($job_type == "Entry")
                                                        {{ $applicant['exam_entry_results'] }}
                                                    @else
                                                        {{ $applicant['exam_srs_results'] }}
                                                    @endif
                                                </td>
                                                <td>{{ $applicant['interview_rating'] }}</td>
                                                <td>{{ $applicant['selection_board_score'] }}</td>
                                                <td>
                                                    <x-adminlte-button label="Click to View" data-toggle="modal" data-target="#viewRequirementsModal{{ $applicant['user_id'] }}" />
                                                    <x-adminlte-modal id="viewRequirementsModal{{ $applicant['user_id'] }}" title="View Requirements" v-centered>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <p><b>Requirements:</b></p>
                                                                <div class="btn-group">
                                                                    <x-adminlte-button label="CSC Form" data-toggle="modal" data-target="#viewCSCFormModal{{ $applicant['user_id'] }}" />
                                                                    <x-adminlte-button label="TOR/Diploma" data-toggle="modal" data-target="#viewTORModal{{ $applicant['user_id'] }}" />
                                                                    <x-adminlte-button label="Training Certificate" data-toggle="modal" data-target="#viewTCModal{{ $applicant['user_id'] }}" />
                                                                    <x-adminlte-button label="Eligibility" data-toggle="modal" data-target="#viewEligibilityModal{{ $applicant['user_id'] }}" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </x-adminlte-modal>
                                                    <x-adminlte-modal id="viewCSCFormModal{{ $applicant['user_id'] }}" title="CSC Form" v-centered size="lg">
                                                        <iframe src="{{ asset('storage/' . $applicant['csc_form']) }}" frameborder="0" style="width:100%;height:500px;"></iframe>
                                                    </x-adminlte-modal>
    
                                                    <x-adminlte-modal id="viewTORModal{{ $applicant['user_id'] }}" title="TOR/Diploma" v-centered size="lg">
                                                        <iframe src="{{ asset('storage/' . $applicant['tor_diploma']) }}" frameborder="0" style="width:100%;height:500px;"></iframe>
                                                    </x-adminlte-modal>
                                                    
                                                    <x-adminlte-modal id="viewTCModal{{ $applicant['user_id'] }}" title="Training Certificate" v-centered size="lg">
                                                        <iframe src="{{ asset('storage/' . $applicant['training_cert']) }}" frameborder="0" style="width:100%;height:500px;"></iframe>
                                                    </x-adminlte-modal>
                                                    
                                                    <x-adminlte-modal id="viewEligibilityModal{{ $applicant['user_id'] }}" title="Eligibility" v-centered size="lg">
                                                        <iframe src="{{ asset('storage/' . $applicant['eligibility']) }}" frameborder="0" style="width:100%;height:500px;"></iframe>
                                                    </x-adminlte-modal>                                         
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        @elseif ($contract_type == "Permanent" || $contract_type == "Permanent with Pre-Employment")
                                            <tr>
                                                <th>Name</th>
                                                @if ($contract_type == "Permanent with Pre-Employment")
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
                                                <td>{{ $applicant['application_status'] }}</td>
                                                <td>
                                                    @if ($job_type == "Entry")
                                                        {{ $applicant['exam_entry_results'] }}
                                                    @else
                                                        {{ $applicant['exam_srs_results'] }}
                                                    @endif
                                                </td>
                                                <td>{{ $applicant['interview_rating'] }}</td>
                                                <td>{{ $applicant['selection_board_score'] }}</td>
                                                <td>
                                                    <x-adminlte-button label="Click to View" data-toggle="modal" data-target="#viewRequirementsModal{{ $applicant['user_id'] }}" />
                                                    <x-adminlte-modal id="viewRequirementsModal{{ $applicant['user_id'] }}" title="View Requirements" v-centered>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <p><b>Requirements:</b></p>
                                                                <div class="btn-group">
                                                                    <x-adminlte-button label="CSC Form" data-toggle="modal" data-target="#viewCSCFormModal{{ $applicant['user_id'] }}" />
                                                                    <x-adminlte-button label="TOR/Diploma" data-toggle="modal" data-target="#viewTORModal{{ $applicant['user_id'] }}" />
                                                                    <x-adminlte-button label="Training Certificate" data-toggle="modal" data-target="#viewTCModal{{ $applicant['user_id'] }}" />
                                                                    <x-adminlte-button label="Eligibility" data-toggle="modal" data-target="#viewEligibilityModal{{ $applicant['user_id'] }}" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </x-adminlte-modal>
                                                    <x-adminlte-modal id="viewCSCFormModal{{ $applicant['user_id'] }}" title="CSC Form" v-centered size="lg">
                                                        <iframe src="{{ asset('storage/' . $applicant['csc_form']) }}" frameborder="0" style="width:100%;height:500px;"></iframe>
                                                    </x-adminlte-modal>
    
                                                    <x-adminlte-modal id="viewTORModal{{ $applicant['user_id'] }}" title="TOR/Diploma" v-centered size="lg">
                                                        <iframe src="{{ asset('storage/' . $applicant['tor_diploma']) }}" frameborder="0" style="width:100%;height:500px;"></iframe>
                                                    </x-adminlte-modal>
                                                    
                                                    <x-adminlte-modal id="viewTCModal{{ $applicant['user_id'] }}" title="Training Certificate" v-centered size="lg">
                                                        <iframe src="{{ asset('storage/' . $applicant['training_cert']) }}" frameborder="0" style="width:100%;height:500px;"></iframe>
                                                    </x-adminlte-modal>
                                                    
                                                    <x-adminlte-modal id="viewEligibilityModal{{ $applicant['user_id'] }}" title="Eligibility" v-centered size="lg">
                                                        <iframe src="{{ asset('storage/' . $applicant['eligibility']) }}" frameborder="0" style="width:100%;height:500px;"></iframe>
                                                    </x-adminlte-modal>                                         
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