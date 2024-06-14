@extends('adminlte::page')

@section('title', 'Applicants')

@section('content_header')
    <h1>Applicants</h1>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                @if($errors->any())
                    <div class="alert alert-danger">
                        {{ implode('', $errors->all('<div>:message</div>')) }}
                    </div>
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
                </div>
                <div class="card">
                    <ul class="nav nav-pills ml-auto px-3 pt-2">
                        <li class="nav-item btn-sm">
                            <a class="nav-link active btn-sm" href="#OpenTab" data-toggle="tab">Ongoing</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#ClosedTab" data-toggle="tab">Closed</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="OpenTab">
                            <div class="card-header">
                                Ongoing Applications
                            </div>
                            <div class="card m-4">
                                @foreach ($applicantsOpen as $open)
                                <div class="card-header mx-4 mt-3">
                                        <b>{{$open['job_position']}}</b>
                                    <div class="card-tools">
                                        <a href="{{route('applications.view', ['hiringID' => $open['hiring_id']])}}" class="btn btn-float-end btn-primary">View</a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered table-sm text-center">
                                        <thead class="table-primary">
                                            <tr>
                                                <th>Name</th>
                                                <th>Status</th>
                                                <th>Date Applied</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (count($open['applicants']) > 0)
                                                @foreach ($open['applicants'] as $applicant)
                                                <tr>
                                                    <td>{{$applicant['user_name']}}</td>
                                                    <td>{{$applicant['application_status']}}</td>
                                                    <td>{{$applicant['date_applied']}}</td>
                                                </tr>
                                                @endforeach
                                            @else
                                            <tr>
                                                <td colspan="4">No available Applicants</td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                @endforeach
                                <div class="card-footer clearfix pagination-sm">
                                    {{ $applicantsOpen->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="ClosedTab">
                            
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
