@extends("layouts.app")
@section("title", "Apply")
@section("content")
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 mt-5">
                @foreach ($hiringData as $hiring)
                <div class="row">
                    <div class="col-md-7">
                        <h1><b>{{$hiring->job_position}}</b></h1>
                    </div>
                    <div class="col-md-5">
                        @if (Auth::user()->account_status === 'verified')
                            <a href="{{route('application.applyUpload', [ 'hiringID' => $hiring->id ])}}" class="btn text-light float-end" style="background-color: #000789;">Apply</a>
                        @else
                            <button class="btn text-light float-end" style="background-color: #000789;" type="button" data-bs-toggle="modal" data-bs-target="#verifyAccount">Apply</button>
                            <div class="modal fade" id="verifyAccount" tabindex="-1" aria-labelledby="verifyAccountLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="verifyAccountLabel">Verify Account</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>You need to verify your account before you can apply for a job.</p>
                                            <a href="{{route('profile.index')}}" class="btn btn-primary">Verify</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        {{-- <button class="btn float-end mx-2" onclick="copyLink('{{ url()->current() }}')">
                            <ion-icon size="small" name="share-social-outline" style="color: #000789;"></ion-icon>
                        </button> --}}
                    </div>
                </div>
                <div style="border-bottom: 2px solid #000789; margin-bottom: 20px;"></div>
                <div class="row flex-row m-4">
                    <div class="col-7 border-end">
                        <div class="row m-2">
                            <div class="col-5">
                                <p><b>Reference</b></p>
                                <p><b>Job Position: </b></p>
                                <p><b>Job Description: </b></p>
                                <p><b>Salary Grade: </b></p>
                                <p><b>Contract Type: </b></p>
                                <p><b>Job Status: </b></p>
                                <p><b>Department: </b></p>
                                <p><b>Date Posted: </b></p>
                                <p><b>Closing: </b></p>
                            </div>
                            <div class="col-7">
                                <p>{{ $hiring->reference }}</p>
                                <p>{{ $hiring->job_position }}</p>
                                <p><small class="form-text text-muted">
                                    Description File: <a href="{{ asset('storage/' . $hiring->job_description) }}"
                                        target="_blank">View File</a>
                                </small></p>  
                                <p>{{ $hiring->salary_grade }}</p>
                                <p>{{ $hiring->contract_type }}</p>
                                <p>{{ $hiring->job_status }}</p>
                                <p>{{ $hiring->department }}</p>
                                <p>{{ $hiring->created_at }}</p>
                                <p>{{ $hiring->closing }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-5">
                        <h4><b>Requirements: </b></h4>
                        <div class="accordion" id="requirementsAccordion{{ $hiring->id }}">
                            @foreach ($requirements as $requirement)
                                @foreach(['csc_form', 'tor_diploma', 'training_cert', 'eligibility'] as $requirementName)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading{{ ucfirst($requirementName) }}{{ $hiring->id }}">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ ucfirst($requirementName) }}{{ $hiring->id }}" aria-expanded="false" aria-controls="collapse{{ ucfirst($requirementName) }}{{ $hiring->id }}">
                                                {{ ucfirst(str_replace('_', ' ', $requirementName)) }}
                                            </button>
                                        </h2>
                                        <div id="collapse{{ ucfirst($requirementName) }}{{ $hiring->id }}" class="accordion-collapse collapse" aria-labelledby="heading{{ ucfirst($requirementName) }}{{ $hiring->id }}" data-bs-parent="#requirementsAccordion{{ $hiring->id }}">
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
                @endforeach
                <!-- Alert container -->
                <div id="alert-container" class="mt-3"></div>
            </div>
        </div>
    </div>
    <script>
        function copyLink(link) {
            // Create a temporary input element to hold the link
            const tempInput = document.createElement('input');
            tempInput.value = link;
            document.body.appendChild(tempInput);
    
            // Select the input value and copy it to the clipboard
            tempInput.select();
            document.execCommand('copy');
    
            // Remove the temporary input element
            document.body.removeChild(tempInput);
    
            // Show toastr notification
            toastr.info('Link copied to clipboard!');
        }
    </script>    
@endsection
