@extends('adminlte::page')

@section('title', 'Applicants | BEI')

@section('content_header')
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        Name of Applicant: {{ $name }}
                        <div class="card-tools">
                            <form action="{{route('UploadBEI', ['applicationID' => $applicantID, 'salaryGrade' => $salaryGrade, 'hiringID' =>$hiringID])}}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm">SUBMIT</button>
                        </div>
                    </div>
                    <div class="card-body">
                            @foreach ($competencies as $item)
                            <h6><b>{{$item['name']}}</b></h6>
                            <div class="col-12">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rating</span>
                                    </div>
                                    <input type="number" name="{{$item['labelDB']}}_rate" class="form-control" placeholder="Rate from 1 to 10" max="10" min="0" >
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>SITUATION OR TASK/S</label>
                                            <textarea name="{{$item['labelDB']}}_situation" class="form-control" rows="3" placeholder="Enter situation or task." ></textarea>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>ACTION/S</label>
                                            <textarea class="form-control" name="{{$item['labelDB']}}_action" rows="3" placeholder="Enter action taken by the applicant." ></textarea>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>RESULT/S</label>
                                            <textarea class="form-control" name="{{$item['labelDB']}}_result" rows="3" placeholder="Enter the result of the action." ></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </form>
                        @if ($leadershipCompetencies != NULL)
                        <h5 class="my-3 text-center"><b>LEADERSHIP COMPETENCIES</b></h5>
                        @foreach ($leadershipCompetencies as $item)
                        <h6><b>{{$item['name']}}</b></h6>
                        <div class="col-12">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rating</span>
                                </div>
                                <input type="number" name="{{$item['labelDB']}}_rate" class="form-control" placeholder="Rate from 1 to 10" max="10" min="0" >
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>SITUATION OR TASK/S</label>
                                        <textarea name="{{$item['labelDB']}}_situation" class="form-control" rows="3" placeholder="Enter situation or task." ></textarea>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>ACTION/S</label>
                                        <textarea class="form-control" name="{{$item['labelDB']}}_action" rows="3" placeholder="Enter action taken by the applicant." ></textarea>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>RESULT/S</label>
                                        <textarea class="form-control" name="{{$item['labelDB']}}_result" rows="3" placeholder="Enter the result of the action." ></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @else
                        @endif
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
