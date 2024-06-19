@extends('adminlte::page')

@section('title', 'Users')

@section('content_header')
    <h1>Users</h1>
    {{-- @vite(['resources/sass/app.scss', 'resources/js/app.js']) --}}
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
@stop

@section('content')
<div class="row">
    <div class="col-12">
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
        <div class="card">
            <div class="card-header">User Table
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive">
                <table class="table text-nowrap text-center searchresult">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Email</th>
                            <th>Number</th>
                            <th>Address</th>
                            <th>Requirements</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->number }}</td>
                            <td>{{ $user->address }}</td>
                            <td class="justify-content-center">
                                <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="{{$user->progress}}%" aria-valuemin="0" aria-valuemax="100">
                                    <div class="progress-bar bg-info" style="width: {{$user->progress}}%;">{{$user->progress}}%</div>
                                  </div>
                            </td>
                            <td>
                                @if ($user->usertype == 'guest')
                                    @if(auth()->user()->usertype != 'admin' && auth()->user()->usertype != 'hr')
                                        <x-adminlte-button id="btnDelete" theme="danger" icon="fas fa-solid fa-trash" label="Delete" data-toggle="modal" data-target="#deleteModal{{ $user->id }}" hidden/>
                                    @else
                                        <x-adminlte-button id="btnDelete" theme="danger" icon="fas fa-solid fa-trash" label="Delete" data-toggle="modal" data-target="#deleteModal{{ $user->id }}"/>
                                    @endif
                                @else
                                    <x-adminlte-button id="btnView" label="View" icon="fas fa-solid fa-eye" theme="success" data-toggle="modal" data-target="#viewModal{{ $user->id }}" />
                                    @if(auth()->user()->usertype != 'admin' && auth()->user()->usertype != 'hr')
                                        <x-adminlte-button id="btnDelete" theme="danger" icon="fas fa-solid fa-trash" label="Delete" data-toggle="modal" data-target="#deleteModal{{ $user->id }}" hidden/>
                                    @else
                                        <x-adminlte-button id="btnDelete" theme="danger" icon="fas fa-solid fa-trash" label="Delete" data-toggle="modal" data-target="#deleteModal{{ $user->id }}"/>
                                    @endif
                                @endif
                                    {{-- <x-adminlte-button id="btnView" label="Edit" icon="fas fa-solid fa-edit" theme="warning" data-toggle="modal" data-target="#editModal{{ $user->id }}" /> --}}
                            </td>
                        </tr>
                        <x-adminlte-modal id="viewModal{{$user->id}}" title="View User Details" v-centered size="lg">
                            <div class="row px-5">
                                <div class="col-8 border">
                                    <p class="text-center"><b>PERSONAL INFORMATION</b></p>
                                    <div class="row mx-3">
                                        <div class="col-3">
                                            <p><b>Name:</b></p>
                                            <p><b>Email:</b></p>
                                            <p><b>Number:</b></p>
                                            <p><b>Address:</b></p>
                                        </div>
                                        <div class="col-7">
                                            <p>{{ $user->name }}</p>
                                            <p>{{ $user->email }}</p>
                                            <p>{{ $user->number }}</p>
                                            <p>{{ $user->address }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4 text-center">
                                    <p class=""><b>REQUIREMENTS</b></p>
                                    <p></p>
                                    <div class="btn-group-vertical">
                                        <x-adminlte-button id="btnViewTOR" label="TOR/Diploma" data-toggle="modal" data-target="#viewTORModal{{ $user->id }}" />
                                        <x-adminlte-button id="btnViewTraining" label="Training Certificate" data-toggle="modal" data-target="#viewTCModal{{ $user->id }}" />
                                        <x-adminlte-button id="btnViewEligibility" label="Eligibility" data-toggle="modal" data-target="#viewEligibilityModal{{ $user->id }}" />
                                        <x-adminlte-button id="btnViewAward" label="Award/Recognition" data-toggle="modal" data-target="#viewAwardModal{{ $user->id }}" />
                                        <x-adminlte-button id="btnViewPerformanceRating" label="Performance Rating" data-toggle="modal" data-target="#viewRatingModal{{ $user->id }}" />
                                    </div>
                                </div>
                                {{-- <div class="col-12">
                                    <p class="text-center m-2"><b>APPLICATION HISTORY</b></p>
                                    <div class="border-bottom mx-5"></div>
                                </div> --}}
                            </div>
                        </x-adminlte-modal>
                        
                        <x-adminlte-modal id="deleteModal{{$user->id}}" title="Delete User" v-centered>
                            <p>Are you sure you want to delete user: {{ $user->name }}?</p>
                            <x-slot name="footerSlot">
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <x-adminlte-button theme="danger" label="Delete" onclick="this.form.submit()" />
                                </form>
                                <x-adminlte-button theme="secondary" label="Cancel" data-dismiss="modal" />
                            </x-slot>
                        </x-adminlte-modal>      

                        <x-adminlte-modal id="viewTORModal{{$user->id}}" title="TOR|Diploma" v-centered size="lg">
                            @if ($user->tor_diploma == null)
                                <p>No TOR/Diploma file found</p>
                            @else
                                <iframe src="{{ asset('storage/' . $user->tor_diploma) }}" frameborder="full" width="750" height="500"></iframe>
                            @endif
                            <x-slot name="footerSlot">
                                <x-adminlte-button theme="secondary" label="Back" data-dismiss="modal" />
                            </x-slot>
                        </x-adminlte-modal> 
                        
                        <x-adminlte-modal id="viewTCModal{{$user->id}}" title="Training Certificate" v-centered size="lg">
                            @if ($user->training_cert == null)
                                <p>No Training Certificate file found</p>
                            @else
                                <iframe src="{{ asset('storage/' . $user->training_cert) }}" frameborder="full" width="750" height="500"></iframe>
                            @endif
                            <x-slot name="footerSlot">
                                <x-adminlte-button theme="secondary" label="Back" data-dismiss="modal" />
                            </x-slot>
                        </x-adminlte-modal> 
                        
                        <x-adminlte-modal id="viewEligibilityModal{{$user->id}}" title="Eligibility" v-centered size="lg">
                            @if ($user->eligibility == null)
                                <p>No Eligibility file found</p>
                            @else
                                <iframe src="{{ asset('storage/' . $user->eligibility) }}" frameborder="full" width="750" height="500"></iframe>
                            @endif
                            <x-slot name="footerSlot">
                                <x-adminlte-button theme="secondary" label="Back" data-dismiss="modal" />
                            </x-slot>
                        </x-adminlte-modal>
                        
                        <x-adminlte-modal id="viewAwardModal{{$user->id}}" title="Award" v-centered size="lg">
                            @if ($user->csc_form == null)
                                <p>No Award file found</p>
                            @else
                                <iframe src="{{ asset('storage/' . $user->csc_form) }}" frameborder="full" width="750" height="500"></iframe>
                            @endif
                            <x-slot name="footerSlot">
                                <x-adminlte-button theme="secondary" label="Back" data-dismiss="modal" />
                            </x-slot>
                        </x-adminlte-modal>  
                        
                        {{-- <x-adminlte-modal id="viewRatingModal{{$user->id}}" title="Rating" v-centered size="lg">
                            @if ($user->eligibility == null)
                                <p>No Rating file found</p>
                            @else
                                <iframe src="{{ asset('assets/PUROK (1).pdf') }}" frameborder="full" width="750" height="500"></iframe>
                            @endif
                            <x-slot name="footerSlot">
                                <x-adminlte-button theme="secondary" label="Back" data-dismiss="modal" />
                            </x-slot>
                        </x-adminlte-modal>                 --}}
                        @endforeach
                    </tbody>
                </table>     
            </div>
            <div class="card-footer clearfix pagination-sm">
                    {{ $data->links('pagination::bootstrap-5') }}
            </div>
        </div>
        <div class="card mt-4">
            <div class="card-header">
                Other User Type User's Table
                <div class="card-tools">
                    <div class="input-group">
                        {{-- <form action="" class="form-inline">
                            <input type="text" id="search" name="search" class="form-control form-control-sm float-right" placeholder="Search">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default btn-sm mr-2">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form> --}}
                        @if(auth()->user()->usertype == 'admin' || auth()->user()->usertype != 'hr')
                        <x-adminlte-button class="btn btn-sm" id="btnView" label="Add User" icon="fas fa-solid fa-user-plus" theme="primary" data-toggle="modal" data-target="#addusermodal" />
                        <x-adminlte-modal id="addusermodal" title="Add New User">
                            <form id="addUser" action="{{route('users.store')}}" method="POST">
                                @csrf
                                <x-adminlte-input name="name" label="Name" placeholder="Enter name" />
                                <x-adminlte-input name="email" label="Email" placeholder="Enter email" />
                                <x-adminlte-input name="password" label="Password" placeholder="Enter password" type="password" />
                                <div class="form-group">
                                    <label for="usertype">User Type</label>
                                    <select class="form-control" id="usertype" name="usertype" required>
                                        <option value="" disabled selected></option>
                                        <option value="user">User</option>
                                        <option value="hr">HR</option>
                                        <option value="selection board">Selection Board</option>
                                    </select>
                                </div>
                                <x-slot name="footerSlot">
                                    <button type="submit" class="btn btn-primary text-end">Add</button>
                                </form>
                                <x-adminlte-button theme="secondary" label="Cancel" data-dismiss="modal" />
                            </x-slot>
                        </x-adminlte-modal>      
                        @else 
                            <div class="btn btn-primary my-2" hidden><ion-icon name="person-add"></ion-icon></div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>User Type</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($otherUsers as $item)
                        @if (Auth::user()->id != $item->id)
                            <tr class="text-center">
                                <td>{{$item->id}}</td>
                                <td>{{$item->name}}</td>
                                <td>{{$item->email}}</td>
                                <td>{{$item->usertype}}</td>
                                <td>
                                    <x-adminlte-button id="btnDelete" theme="danger" icon="fas fa-solid fa-trash" label="Delete" data-toggle="modal" data-target="#deleteModal{{ $item->id }}"/>
                                </td>
                            </tr>
                        @else
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
  
@stop

@section('js')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#search').on('keyup', function(){
                var value = $(this).val();
                $.ajax({
                    type: "get",
                    url: "/search",
                    data: {'search':value},
                    success: function (data) {
                        $('.searchresult').html(data);
                    }
                });
            });
        });
    </script>
@stop
