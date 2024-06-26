@extends("layouts.app")
@section("title", "Home")
@section("content")
    <x-img>

    </x-img>
    <div class="container p-5">
        {{-- @if($errors->any())
    {{ implode('', $errors->all('<div>:message</div>')) }}
@endif --}}
        <div class="row mb-3 d-flex justify-content-between">
            <div class="col-10">
                <div class="input-group">
                    <form action="{{route('home')}}" method="get">
                        @csrf
                        <input type="search" name="querySearch" class="form-control shadow-sm form-control-sm" placeholder="Search" value="{{$search}}" onchange="this.form.submit()">
                    </form>
                </div>
            </div>
            <div class="col-2">
                <form action="{{ route('home') }}" method="GET">
                    <select name="filter" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="" disabled selected>Filter</option>
                        <option value="COS" {{ request('filter') == 'COS' ? 'selected' : '' }}>COS</option>
                        <option value="Permanent" {{ request('filter') == 'Permanent' ? 'selected' : '' }}>PERMANENT</option>
                    </select>
                </form>                
            </div>
        </div>
        <div class="row">
            <div class="col-mb-12">
                <div class="table-responsive">
                    <table class="table rounded-1 overflow-hidden">
                        <thead class="table-primary">
                            <tr class="text-center">
                                <th>DEPARTMENT</th>
                                <th>POSITION TITLE</th>
                                <th>SALARY GRADE</th>
                                <th>POSTING DATE</th>
                                <th>CLOSING DATE</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!$hirings->isEmpty())
                            @foreach ($hirings as $hiring)
                            <tr class="text-center">
                                    <td>{{$hiring->department}}</td>
                                    <td>{{$hiring->job_position}}</td>
                                    <td>{{$hiring->salary_grade}}</td>
                                    <td>{{$hiring->created_at}}</td>
                                    <td>{{$hiring->closing}}</td>
                                    <td>
                                        @auth
                                            @php 
                                                $missingDocuments = false;
                                                foreach($requirements as $requirement) {
                                                    if ($requirement->csc_form == NULL || $requirement->tor_diploma == NULL) {
                                                        $missingDocuments = true;
                                                        break;
                                                    }
                                                }
                                            @endphp 
                                            @if($missingDocuments == true)
                                                <button type="button" class="btn text-white border-1 rounded-1" style="background-color: #000789;" data-bs-toggle="modal" data-bs-target="#missingDocumentsModal{{ $hiring->id }}">
                                                    Apply Now
                                                </button>
                                            @else
                                                @if($hiring->hasApplication == true && $hiring->application_status == "pending")
                                                    <button class="btn" type="button" style="color: #000789; border-color:#000789;" data-bs-toggle="modal" data-bs-target="#cancelApplication{{ $hiring->id }}">
                                                        Cancel Application
                                                    </button>
                                                @elseif($hiring->hasApplication == true && $hiring->application_status == "Cancelled" || $hiring->application_status == "Failed")
                                                    <button class="btn bg-danger rounded-1 border-0 text-light" disabled data-bs-toggle="tooltip">Apply Now</button>
                                                @else
                                                    <a href="{{ route('application.apply', ['hiringID' => $hiring->id]) }}" class="btn border-1 rounded-1 text-white  btn-warning">Apply Now</a>
                                                @endif
                                            @endif
                                        @else
                                            <button class="btn text-white border-1 rounded-1" style="background-color: #000789;" data-bs-target="#loginToggle" data-bs-toggle="modal">Apply Now</button>
                                        @endauth    
                                    
                                        <button type="button" class="btn border rounded-1" data-bs-toggle="modal" data-bs-target="#details{{ $hiring->id }}">
                                            Details
                                        </button>
                                        <!-- Modal for Job Details -->
                                        <div class="modal fade" id="details{{$hiring->id}}" tabindex="-1" aria-labelledby="detailsLabel{{$hiring->id}}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-body">
                                                        <iframe src="{{ asset('storage/' .  $hiring->job_description) }}" frameborder="0" style="width: 100%; height: 600px;"></iframe>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>                                           
                                    {{-- cancel application modal --}}
                                    @auth
                                    <div class="modal fade" id="cancelApplication{{$hiring->id}}" tabindex="-1" aria-labelledby="cancelApplicationLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="cancelApplicationLabel">Cancel Application</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to cancel your application for this job?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <form action="{{route('application.cancel')}}" method="POST">
                                                        @csrf
                                                        <input type="text" name="hiringID" value="{{$hiring->id}}" hidden>
                                                        <button type="submit" class="btn btn-danger">Cancel Application</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Alert missing requirements modal --}}
                                    <div class="modal fade" id="missingDocumentsModal{{$hiring->id}}" tabindex="-1" aria-labelledby="missingDocumentsModalLabel{{$hiring->id}}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="missingDocumentsModalLabel">Missing Documents</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>You are missing some required documents. Please upload them before applying:</p>
                                                    <ul>
                                                        @foreach($requirements as $requirement)
                                                            @if($requirement->csc_form == NULL)
                                                                <li>CSC Form</li>
                                                            @endif
                                                            @if($requirement->tor_diploma == NULL)
                                                                <li>TOR/Diploma</li>
                                                            @endif
                                                            @if($requirement->training_cert == NULL)
                                                                <li>Training Certificate(Not required but if you have upload scanned file.)</li>
                                                            @endif
                                                            @if($requirement->eligibility == NULL)
                                                                <li>Eligibility(Not required but if you have upload scanned file.)</li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <a href="{{route('upload-requirement')}}" class="btn btn-primary">Upload Documents</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endauth
                                @endforeach
                            </tr>
                            @else
                            <td colspan='6' class="text-center">
                                <p><b>No Job Posting Available</b></p>
                                <p>Visit CSC Career <a href="http://csc.gov.ph/career/" target="_blank">csc.gov.ph/career/</a> to find more job opportunities.</p>
                            </td>
                            @endif           
                        </tbody>
                    </table>
                    <div class="card-footer clearfix pagination-sm">
                        {{ $hirings->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection