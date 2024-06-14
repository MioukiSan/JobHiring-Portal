@extends("layouts.app")
@section("title", "Upload Requirements")
@section("content")
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-8 mt-5">
                <div class="card border-sm shadow-sm">
                    <div class="card-header">
                        <b>Upload Requirements</b>
                        <a href="{{ route('home') }}" class="btn float-end border shadow btn-sm">Back</a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('store-requirement') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @foreach ($requirements as $requirement)
                                @if (is_null($requirement->csc_form))
                                    <div class="m-3">
                                        <label for="csc_form" class="form-label">CSC Form</label>
                                        <input type="file" id="csc_form" name="csc_form" class="form-control" required>
                                    </div>
                                @endif
                                @if (is_null($requirement->tor_diploma))
                                    <div class="m-3">
                                        <label for="tor_diploma" class="form-label">TOR Diploma</label>
                                        <input type="file" id="tor_diploma" name="tor_diploma" class="form-control" required>
                                    </div>
                                @endif
                                @if (is_null($requirement->training_cert))
                                    <div class="m-3">
                                        <label for="training_cert" class="form-label">Training Certificate</label>
                                        <input type="file" id="training_cert" name="training_cert" class="form-control" required>
                                    </div>
                                @endif
                                @if (is_null($requirement->eligibility))
                                    <div class="m-3">
                                        <label for="eligibility" class="form-label">Eligibility</label>
                                        <input type="file" id="eligibility" name="eligibility" class="form-control">
                                    </div>
                                @endif
                            @endforeach
                            <div class="mb-3 mx-4 text-end">
                                <button type="submit" name="upload" class="btn btn-primary">Upload</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
