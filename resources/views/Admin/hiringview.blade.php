@extends('adminlte::page')

@section('title', 'Hiring Job | Overview')

@section('content_header')
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
@stop

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right" style="background: none;">
                    <li class="breadcrumb-item"><a href="{{url('hirings')}}">Job Hiring</a></li>
                    <li class="breadcrumb-item active">{{$hiring->job_position}} Hiring</li>
                </ol>
            </div>
            <div class="col-sm-12">
                @if($errors->any())
                {{ implode('', $errors->all('<div>:message</div>')) }}
                @endif
                <div class="card">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                <div class="card shadow-sm">
                    <div class="card-header">
                        Job Hiring Details
                        <x-adminlte-button class="btn btn-sm btn-danger float-right" label="Delete" theme="danger" data-toggle="modal" data-target="#deletehiring-{{ $hiring->id }}" />
                        <x-adminlte-modal id="deletehiring-{{ $hiring->id }}" title="Delete Hiring">
                            <h5>Do you want to delete the job posting? All other data from application will be deleted also.</h5>
                            <form action="{{ route('hirings.destroy', $hiring) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <x-slot name="footerSlot">
                                    <button type="submit" class="btn btn-danger text-end">Delete</button>
                                </form>
                                    <x-adminlte-button theme="secondary" label="Cancel" data-dismiss="modal" />
                                </x-slot>
                        </x-adminlte-modal>
                        <x-adminlte-button class="btn float-right btn-sm mr-2" label="Edit Hiring" icon="fas fa-solid fa-edit" theme="warning" data-toggle="modal" data-target="#editHiring{{ $hiring->id }}" />
                        
                        <x-adminlte-modal id="editHiring{{ $hiring->id }}" title="Edit Hiring">
                            <form action="{{ route('hirings.update', $hiring) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                
                                <!-- Job Position -->
                                <x-adminlte-input name="job_position" label="Job Position" placeholder="Enter job position" value="{{ $hiring->job_position }}" />
                                
                                <!-- Job Description -->
                                {{-- <x-adminlte-textarea name="job_description" label="Job Description" placeholder="Enter job description" /> --}}
                                <div class="form-group">
                                    <label for="JobDescriptionPDF">Job Description PDF</label>
                                    <input type="file" class="form-control-file border p-1 rounded" name="JobDescriptionPDF">
                                    @if($hiring->job_description)
                                        <small class="form-text text-muted">
                                            Current file: <a href="{{ asset('storage/' . $hiring->job_description) }}"
                                                target="_blank">View PDF</a>
                                        </small>
                                    @endif
                                </div>
                                
                                <!-- Contract Type -->
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="contract_type">Contract Type</label>
                                            <select class="form-control" id="contract_type" name="contract_type" required>
                                                <option value="" disabled>Select contract type</option>
                                                <option value="COS" {{ $hiring->contract_type == 'COS' ? 'selected' : '' }}>COS</option>
                                                <option value="Permanent" {{ $hiring->contract_type == 'Permanent' ? 'selected' : '' }}>Permanent</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="job_type">Job Type</label>
                                            <select class="form-control" id="job_type" name="job_type" required>
                                                <option value="" disabled>Select job type</option>
                                                <option value="Entry" {{ $hiring->job_type == 'Entry' ? 'selected' : '' }}>Entry</option>
                                                <option value="SRS-1" {{ $hiring->job_type == 'SRS-1' ? 'selected' : '' }}>SRS-1</option>
                                                <option value="SRS-2" {{ $hiring->job_type == 'SRS-2' ? 'selected' : '' }}>SRS-2</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="salary_grade">Salary Grade</label>
                                            <select class="form-control" id="salary_grade" name="salary_grade" required>
                                                <option value="" disabled>Select salary grade</option>
                                                @for($id = 1; $id <= 24; $id++)
                                                    <option value="{{ $id }}" {{ $hiring->salary_grade == $id ? 'selected' : '' }}>{{ $id }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="job_status">Job Status</label>
                                            <select class="form-control" id="job_status" name="job_status" required>
                                                @php
                                                    $job_status = ['Open', 'Closed', 'Archived', 'Profiling Stage', 'Pre-Employment Exam', 'Competency Exam', 'Final Shortlisting', 'Initial Interview', 'Final Interview', 'PsychoTest', 'BEI'];
                                                @endphp
                                                @foreach ($job_status as $status)
                                                    <option value="{{ $status }}" {{ $hiring->job_status == $status ? 'selected' : '' }}>{{ $status }}</option>
                                                @endforeach
                                            </select>                                            
                                        </div>
                                    </div>
                                    <div class="col-8">
                                        <div class="form-group">
                                            <label for="department">Department</label>
                                            <select class="form-control" id="department" name="department" required>
                                                <option value="Management Information System" {{ $hiring->department == 'Management Information System' ? 'selected' : '' }}>Management Information System</option>
                                                <option value="Office of the Regional Director" {{ $hiring->department == 'Office of the Regional Director' ? 'selected' : '' }}>Office of the Regional Director</option>
                                                <option value="Regional Metrology Laboratory" {{ $hiring->department == 'Regional Metrology Laboratory' ? 'selected' : '' }}>Regional Metrology Laboratory</option>
                                                <option value="Human Resources" {{ $hiring->department == 'Human Resources' ? 'selected' : '' }}>Human Resources</option>
                                                <option value="Technical Office" {{ $hiring->department == 'Technical Office' ? 'selected' : '' }}>Technical Office</option>
                                                <option value="Regional Standard and Testing Laboratory" {{ $hiring->department == 'Regional Standard and Testing Laboratory' ? 'selected' : '' }}>Regional Standard and Testing Laboratory</option>
                                                <option value="PSTO ALBAY" {{ $hiring->department == 'PSTO ALBAY' ? 'selected' : '' }}>PSTO ALBAY</option>
                                                <option value="PSTO CAMARINES SUR" {{ $hiring->department == 'PSTO CAMSUR' ? 'selected' : '' }}>PSTO CAMSUR</option>
                                                <option value="PSTO CAMARINES NORTE" {{ $hiring->department == 'PSTO CAM NORTE' ? 'selected' : '' }}>PSTO CAM NORTE</option>
                                                <option value="PSTO CATANDUANES" {{ $hiring->department == 'PSTO CATANDUANES' ? 'selected' : '' }}>PSTO CATANDUANES</option>
                                                <option value="PSTO MASBATE" {{ $hiring->department == 'PSTO MASBATE' ? 'selected' : '' }}>PSTO MASBATE</option>
                                                <option value="PSTO SORSOGON" {{ $hiring->department == 'PSTO SORSOGON' ? 'selected' : '' }}>PSTO SORSOGON</option>
                                            </select>                                               
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Closing Date -->
                                    <x-adminlte-input name="closing" label="Closing Date" placeholder="Enter closing date" type="datetime-local" value="{{ \Carbon\Carbon::parse($hiring->closing)->format('Y-m-d\TH:i') }}"/>
                                @if ($hiring->contract_type === 'COS')
                                <div class="row">
                                    <div class="col-6">
                                        <x-adminlte-input name="competency_exam_date" label="Competency Exam Date" type="date" value="{{$hiring->competency_exam_date}}"/>
                                        <x-adminlte-input name="pre_employment_exam_date" label="Pre Employment Exam Date" type="date" value="{{$hiring->pre_employment_exam_date}}" />
                                    </div>
                                    <div class="col-6">
                                        <x-adminlte-input name="initial_interview_date" label="Initial Interview Date" type="date" value="{{$hiring->initial_interview_date}}" />
                                        <x-adminlte-input name="final_interview_date" label="Final Interview Date" type="date" value="{{$hiring->final_interview_date}}" />
                                    </div>
                                </div>
                                @elseif ($hiring->contract_type === 'Permanent')
                                    @if ($hiring->job_type === 'Entry' || $hiring->job_type === 'SRS-1')
                                    <div class="row">
                                        <div class="col-6">
                                            <x-adminlte-input name="pre_employment_exam_date" label="Pre-Employment Exam Date" type="date" value="{{$hiring->pre_employment_exam_date}}"/>
                                            <x-adminlte-input name="bei_date" label="BEI Date" type="date" value="{{$hiring->bei_date}}" />
                                        </div>
                                        <div class="col-6">
                                            <x-adminlte-input name="psycho_test_date" label="Psychometric Test Date" type="date" value="{{$hiring->psycho_test_date}}"/>
                                            <x-adminlte-input name="final_interview_date" label="Final Interview Date" type="date" value="{{$hiring->final_interview_date}}" />
                                        </div>
                                    </div>
                                    @else
                                    <div class="row">
                                        <div class="col-12">
                                            <x-adminlte-input name="bei_date" label="BEI Date" type="date" value="{{$hiring->bei_date}}" />
                                        </div>
                                        <div class="col-6">
                                            <x-adminlte-input name="pyscho_test_date" label="Psychometric Test Date" type="date" value="{{$hiring->psycho_test_date}}"/>
                                        </div>
                                        <div class="col-6">
                                            <x-adminlte-input name="final_interview_date" label="Final Interview Date" type="date" value="{{$hiring->final_interview_date}}" />
                                        </div>
                                    </div>
                                    @endif
                                @endif
                                <x-slot name="footerSlot">
                                    <button type="submit" class="btn btn-warning">Update</button>
                            </form>
                                    <x-adminlte-button theme="secondary" label="Cancel" data-dismiss="modal" />
                                </x-slot>
                        </x-adminlte-modal>                        
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-2">
                                <p><b>Reference</b></p>
                                <p><b>Job Position: </b></p>
                                <p><b>Job Description: </b></p>
                                <p><b>Salary Grade: </b></p>
                                <p><b>Contract Type: </b></p>
                                <p><b>Job Status: </b></p>
                                <p><b>Department: </b></p>
                                <p><b>Date Posted: </b></p>
                                <p><b>Closing: </b></p>
                                <p><b>Guest Information: </b></p>
                            </div>
                            <div class="col-sm-3">
                                <p>{{ $hiring->reference }}</p>
                                <p>{{ $hiring->job_position }}</p>
                                <x-adminlte-button class="btn" data-toggle="modal" data-target="#iframeDescription{{ $hiring->id }}" label="View Description" />
                                <x-adminlte-modal id="iframeDescription{{ $hiring->id }}">
                                    <iframe src="{{ asset('storage/' . $hiring->job_description) }}" frameborder="0" style="width: 100%; height: 500px;"></iframe>
                                </x-adminlte-modal>                                    
                                <p>{{ $hiring->salary_grade }}</p>
                                <p>{{ $hiring->contract_type }}</p>
                                <p>{{ $hiring->job_status }}</p>
                                <p>{{ $hiring->department }}</p>
                                <p>{{ $hiring->created_at }}</p>
                                <p>{{ $hiring->closing }}</p>
                                @foreach ($getGuest as $guest)
                                    <p>Email: {{ $guest->email }}</p>
                                    <p>Password: {{ $guest->address }}</p>
                                @endforeach
                            </div>
                            <div class="col-sm-7">
                                <div class="d-flex justify-content-end mb-2">
                                    <a href="{{route('applications.view', ['hiringID' => $hiring['id']])}}" class="btn btn-primary btn-sm">View All</a>
                                </div>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Applicants</th>
                                            <th>Application Status</th>
                                            <th>Application Date</th>
                                        </tr>
                                        <tbody>
                                            @if (count($applications) > 0)
                                                @foreach ($applications as $applicant)
                                                    <tr class="text-center">
                                                        <td>{{$applicant->name }}</td>
                                                        <td>{{ $applicant->application_status }}</td>
                                                        <td>{{ $applicant->created_at }}</td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="3" class="text-center">
                                                       No applicants yet. Please wait for applicants to apply.
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </thead>
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
@stop