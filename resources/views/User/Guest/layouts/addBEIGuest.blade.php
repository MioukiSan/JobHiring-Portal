@extends('layouts.app')
@section("title", "Initial Interview")
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 mt-4">
                <div class="justify-content-end">
                      <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a href="{{route('applications.view')}}">Home</a></li>
                          <li class="breadcrumb-item active" aria-current="page">Initial Interview</li>
                        </ol>
                      </nav>
                </div>
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        Name of Applicant: {{ $name }}
                        <form action="{{route('UploadBEI', ['applicationID' => $applicantID, 'salaryGrade' => $salaryGrade, 'hiringID' =>$hiringID])}}" method="POST">
                            @csrf
                            <button type="submit"  class="btn btn-sm text-light float-end" style="background-color: #000789;">SUBMIT</button>
                    </div>
                    <div class="card-body">
                            @foreach ($competencies as $item)
                            <h6><b>{{$item['name']}}</b></h6>
                            <div class="col-12">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rating</span>
                                    </div>
                                    <input type="number" name="{{$item['labelDB']}}_rate" class="form-control" placeholder="Rate from 1 to 10" max="10" min="0" required>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>SITUATION OR TASK/S</label>
                                            <textarea name="{{$item['labelDB']}}_situation" class="form-control" rows="3" placeholder="Enter situation or task." required></textarea>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>ACTION/S</label>
                                            <textarea class="form-control" name="{{$item['labelDB']}}_action" rows="3" placeholder="Enter action taken by the applicant." required></textarea>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>RESULT/S</label>
                                            <textarea class="form-control" name="{{$item['labelDB']}}_result" rows="3" placeholder="Enter the result of the action." required></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @if ($leadershipCompetencies != NULL)
                        <h5 class="my-3 text-center"><b>LEADERSHIP COMPETENCIES</b></h5>
                        @foreach ($leadershipCompetencies as $item)
                        <h6><b>{{$item['name']}}</b></h6>
                        <div class="col-12">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rating</span>
                                </div>
                                <input type="number" name="{{$item['labelDB']}}_rate" class="form-control" placeholder="Rate from 1 to 10" max="10" min="0" required>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>SITUATION OR TASK/S</label>
                                        <textarea name="{{$item['labelDB']}}_situation" class="form-control" rows="3" placeholder="Enter situation or task." required></textarea>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>ACTION/S</label>
                                        <textarea class="form-control" name="{{$item['labelDB']}}_action" rows="3" placeholder="Enter action taken by the applicant." required></textarea>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>RESULT/S</label>
                                        <textarea class="form-control" name="{{$item['labelDB']}}_result" rows="3" placeholder="Enter the result of the action." required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </form>
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
