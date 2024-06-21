@extends('layouts.app')
@section('title', 'Profile')
@section("content")
<div class="container" style="padding-top: 5em;">
    @if ($errors->any())
    <div class="alert alert-danger mt-3">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <div class="row justify-content-center">
        <div class="col-lg-2 h-100">
            <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <button class="nav-link active" id="v-pills-Profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-Profile" type="button" role="tab" aria-controls="v-pills-Profile" aria-selected="true">Profile</button>
                <button class="nav-link" id="v-pills-Applications-tab" data-bs-toggle="pill" data-bs-target="#v-pills-Applications" type="button" role="tab" aria-controls="v-pills-Applications" aria-selected="false">Applications</button>
                <button class="nav-link" id="v-pills-Calendar-tab" data-bs-toggle="pill" data-bs-target="#v-pills-Calendar" type="button" role="tab" aria-controls="v-pills-Calendar" aria-selected="false">Calendar</button>
                <button class="nav-link" id="v-pills-setting-tab" data-bs-toggle="pill" data-bs-target="#v-pills-setting" type="button" role="tab" aria-controls="v-pills-setting" aria-selected="false">Account Settings</button>
                
            </div>
        </div>
        <div class="col-lg-9 shadow border-1 rounded-2" style="height: 35em;">
            <div class="tab-content" id="v-pills-tabContent">
                <div class="tab-pane fade show active" id="v-pills-Profile" role="tabpanel" aria-labelledby="v-pills-Profile-tab" tabindex="0"style="height: 33em; overflow-y: auto; scrollbar-width: none; -ms-overflow-style: none;">
                    <h3 class="mt-5 mb-2 mx-3 text-primary"><b>USER PROFILE<ion-icon name="person-circle-outline"></ion-icon></b></h3>
                    <div class="row m-3">
                        <div class="col-12 border-top">
                            <p class="mt-3"><b>Account Status: </b>
                                @if (Auth::user()->title === NULL)
                                    <span class="text-warning">Not verfiied<ion-icon name="alert-circle-outline"></ion-icon></span><a href="{{route('send.verification')}}">  verify now</a></p>
                                @elseif (Auth::user()->title != NULL && Auth::user()->title != 'verified')
                                    <span class="text-warning">Verification in Process<ion-icon name="footsteps-outline"></ion-icon></span>
                                        <button class="btn btn-sm" title="Click to enter the code sent to your number." data-bs-toggle="modal" data-bs-target="#verify">Enter Code</button>
                                        <div class="modal fade" id="verify"  tabindex="-1" aria-labelledby="verifyLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Verify Account</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{route('verify.account')}}" method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="verification_code" class="form-label">Verification Code</label>
                                                                <input type="text" name="verification_code" placeholder="Enter the verification code." class="form-control" required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Submit</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </p>
                                @else
                                    <span class="text-primary">Verified<ion-icon name="checkmark-done-outline"></ion-icon></span></p>
                                @endif
                            <p><b>Number of Application: {{$countApplication}}</b><span></span></p>
                        </div>
                        <h5 class="text-success my-1">Personal Information</h5>
                        <div class="col-md-12 border-top border-bottom mb-3">
                            <div class="form-group m-4">
                                <div class="row">
                                    <div class="col-6">
                                        <label for="name" class="form-label">Name:</label>
                                        <input type="text" class="form-control" id="name" value="{{ Auth::user()->name }}" disabled>
                                    </div>
                                    <div class="col-6">
                                        <label for="name" class="form-label">Address:</label>
                                        <input type="text" class="form-control" id="name" value="{{ Auth::user()->address }}" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group m-4">
                                <div class="row">
                                    <div class="col-6">
                                        <label for="name" class="form-label">Email:</label>
                                        <input type="text" class="form-control" id="name" value="{{ Auth::user()->email }}" disabled>
                                    </div>
                                    <div class="col-6">
                                        <label for="name" class="form-label">Contact Number:</label>
                                        <input type="text" class="form-control" id="name" value="{{ Auth::user()->number }}" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h5 class="text-success my-1">Requirements</h5>
                        <div class="col-md-12 border-top border-bottom mb-3">
                            <div class="accordion my-3" id="requirementsAccordion">
                                @foreach ($requirements as $requirement)
                                    @foreach(['csc_form', 'tor_diploma', 'training_cert', 'eligibility'] as $requirementName)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading{{ ucfirst($requirementName) }}">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ ucfirst($requirementName) }}" aria-expanded="false" aria-controls="collapse{{ ucfirst($requirementName) }}">
                                                    {{ ucfirst(str_replace('_', ' ', $requirementName)) }}
                                                </button>
                                            </h2>
                                            <div id="collapse{{ ucfirst($requirementName) }}" class="accordion-collapse collapse" aria-labelledby="heading{{ ucfirst($requirementName) }}" data-bs-parent="#requirementsAccordion">
                                                <div class="accordion-body">
                                                    @if(isset($requirement->$requirementName))
                                                        <iframe src="{{ asset('storage/' . $requirement->$requirementName) }}" frameborder="0" style="width: 100%; height: 350px;"></iframe>
                                                    @else
                                                        <p>No file available.</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="v-pills-Applications" role="tabpanel" aria-labelledby="v-pills-Applications-tab" tabindex="0" style="height: 32em; overflow-y: auto; scrollbar-width: none; -ms-overflow-style: none;">
                    <h3 class="mt-5 mb-2 mx-3 text-primary"><b>APPLICATIONS</b></h3>
                     <div class="row m-3">
                        <div class="col-12 border-top border-bottom mb-4">
                            <h5 class="m-3"><b>Recent Applications</b></h5>
                            <div class="table">
                                <table class="table table-responsive">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Job Position</th>
                                            <th>Application Status</th>
                                            <th>Job Status</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($applicationOngoing->isNotEmpty())
                                            @foreach ($applicationOngoing as $item)
                                                <tr class="text-center">
                                                    <td>{{$item['job_position']}}</td>
                                                    <td>{{$item['application_status']}}</td>
                                                    <td>{{$item['job_status'] . ' Phase'}}</td>
                                                    <td>{{$item['created_at']}}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="3" class="text-center">No Data</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <h5 class="m-3"><b>Application History</b></h5>
                            <div class="table">
                                <table class="table table-responsive">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Job Position</th>
                                            <th>Application Status</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($applicationHistory->isNotEmpty())
                                            @foreach ($applicationHistory as $item)
                                                <tr class="text-center">
                                                    <td>{{ $item->job_position }}</td>
                                                    <td>{{ $item->application_status }}</td>
                                                    <td>{{ $item->created_at }}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="3" class="text-center">No Data</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="v-pills-Calendar" role="tabpanel" aria-labelledby="v-pills-Calendar-tab" tabindex="0">
                    <div id='calendar' class="m-3">

                    </div>
                </div>
                <div class="tab-pane fade" id="v-pills-setting" role="tabpanel" aria-labelledby="v-pills-setting-tab" tabindex="0">
                    <h3 class="mt-5 mb-2 mx-3 text-primary"><b>Account Setting</b></h3>
                    <div class="row border-top m-3">
                        <div class="col-lg-12 m-3">
                            <h5><b>Change Password</b></h5>
                            <div class="row">
                                <form action="{{route('change.password')}}" method="POST">
                                    @csrf
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="current_password" class="form-label">Current Password</label>
                                            <input type="password" id="current_password" name="current_password" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="new_password" class="form-label">New Password</label>
                                            <input type="password" id="new_password" name="new_password" class="form-control" required>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary mt-3" type="submit">Change</button>
                                </form>
                            </div>
                            <h5 class="mt-3"><b>Delete Password</b></h5>
                            <button class="btn btn-danger mt-2" data-bs-toggle="modal" data-bs-target="#deleteAccount" type="button">
                                <i class="fas fa-trash"></i> Delete Account
                            </button>
                            <p><span>By deleting your account, all your personal information you have uploaded will be deleted permanently.</span></p>
                            <div class="modal fade" id="deleteAccount" tabindex="-1" aria-labelledby="deleteAccountLabel" aria-hidden="true">>
                                <div class="modal-dialog modal-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteAccountLabel">Delete Account</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <h6>
                                                By deleting your account, all your personal information you have uploaded will be deleted permanently.
                                            </br>
                                            Are you sure you want to delete your account?
                                            </h6>
                                        </div>
                                        <div class="modal-footer">
                                            <form action="{{ route('profile.destroy', Auth::user()->id)}}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>                                            
                                        </div>
                                    </div>
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