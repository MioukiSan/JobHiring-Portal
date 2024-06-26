@extends('adminlte::page')

@section('title', 'Job Hiring')

@section('content_header')
    <h1>Job Hiring</h1>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    
@stop

@section('content')
<section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
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
              <div class="card-header">Jobs Hiring Table
                <div class="card-tools">
                  <div class="input-group">
                    @if(auth()->user()->usertype == 'admin' || auth()->user()->usertype == 'hr')
                        <x-adminlte-button class="btn" label="Add Hiring" icon="fas fa-solid fa-plus" theme="primary" data-toggle="modal" data-target="#addhiring" />
                        
                        <x-adminlte-modal id="addhiring" title="Add New Hiring">
                            <form action="{{ route('hirings.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <!-- Job Position -->
                                <x-adminlte-input name="job_position" label="Job Position" placeholder="Enter job position" />
                                
                                <!-- Job Description -->
                                {{-- <x-adminlte-textarea name="job_description" label="Job Description" placeholder="Enter job description" /> --}}
                                <div class="form-group">
                                  <label for="JobDescriptionPDF">Example file input</label>
                                  <input type="file" class="form-control-file border p-1 rounded" name="JobDescriptionPDF">
                                </div>
                                <!-- Contract Type -->
                                <div class="row">
                                  <div class="col-4">
                                    <div class="form-group">
                                      <label for="contract_type">Contract Type</label>
                                      <select class="form-control" id="contract_type" name="contract_type" required>
                                          <option value="" disabled selected>Select contract type</option>
                                          <option value="COS">COS</option>
                                          <option value="Permanent">Permanent</option>
                                      </select>
                                    </div>
                                  </div>
                                  <div class="col-4">
                                    <div class="form-group">
                                      <label for="job_type">Job Type</label>
                                      <select class="form-control" id="job_type" name="job_type" required>
                                          <option value="" disabled selected>Select job type</option>
                                          <option value="Entry">Entry</option>
                                          <option value="SRS-1">SRS-1</option>
                                          <option value="SRS-2">SRS-2</option>
                                      </select>
                                    </div>
                                  </div>
                                  <div class="col-4">
                                    <div class="form-group">
                                      <label for="salary_grade">Salary Grade</label>
                                      <select class="form-control" id="salary_grade" name="salary_grade" required>
                                        <option value="" disabled selected>Select salary grade</option>
                                        <?php 
                                          for($id = 1; $id <= 24; $id++) {
                                            echo "<option value='$id'>$id</option>";
                                          }
                                        ?>
                                      </select>
                                    </div>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="department">Department</label>
                                    <select class="form-control" id="department" name="department" required>
                                        <option value="" disabled selected>Select department</option>
                                        <option value="Management Information System">Management Information System</option>
                                        <option value="Office of the Regional Director">Office of the Regional Director</option>
                                        <option value="Regional Metrology Laboratory">Regional Metrology Laboratory</option>
                                        <option value="Human Resources">Human Resources</option>
                                        <option value="Technical Office">Technical Office</option>
                                        <option value="Regional Standard and Testing Laboratory">Regional Standard and Testing Laboratory</option>
                                        <option value="PSTO ALBAY">PSTO ALBAY</option>
                                        <option value="PSTO CAMARINES SUR">PSTO CAMSUR</option>
                                        <option value="PSTO CAMARINES NORTE">PSTO CAM NORTE</option>
                                        <option value="PSTO CATANDUANES">PSTO CATANDUANES</option>
                                        <option value="PSTO MASBATE">PSTO MASBATE</option>
                                        <option value="PSTO SORSOGON">PSTO SORSOGON</option>
                                    </select>
                                </div>
                                
                                <!-- Closing Date -->
                                <x-adminlte-input name="closing" label="Closing Date" placeholder="Enter closing date" type="datetime-local"/>
                
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
            <div class="card-body">
              <div class="row">
                  <div class="col-12">
                      <div class="card">
                          <ul class="nav nav-pills ml-auto px-3 pt-2">
                              <li class="nav-item btn-sm">
                                  <a class="nav-link active btn-sm" href="#OpenTab" data-toggle="tab">Open</a>
                              </li>
                              <li class="nav-item">
                                  <a class="nav-link" href="#inProgressTab" data-toggle="tab">In Progress</a>
                              </li>
                              <li class="nav-item">
                                  <a class="nav-link" href="#ArchivedTab" data-toggle="tab">Archived</a>
                              </li>
                          </ul>
                          <div class="tab-content">
                              <div class="tab-pane active" id="OpenTab">
                                  <div class="card-header">
                                      Open Applications
                                  </div>
                                  <div class="card-body">
                                      <!-- Ongoing jobs table -->
                                      <table class="table table-nowrap text-center">
                                          <thead class="table-primary">
                                              <tr>
                                                  <th>Reference</th>
                                                  <th>Job Position</th>
                                                  <th>Department</th>
                                                  <th>Applicants</th>
                                                  <th>Action</th>
                                              </tr>
                                          </thead>
                                          <tbody>
                                              @forelse ($hiringOpen as $hiring)
                                              <tr>
                                                  <td>{{ $hiring->reference }}</td>
                                                  <td>{{ $hiring->job_position }}</td>
                                                  <td>{{ $hiring->department }}</td>
                                                  <td><span class="badge bg-danger">{{ $hiring->application_count }}</span></td>
                                                  <td>
                                                      <!-- View and delete buttons -->
                                                      <a class="btn btn-success" href="{{ route('hirings.show', $hiring) }}">View</a>
                                                        <x-adminlte-button class="btn btn-danger" label="Delete" theme="danger" data-toggle="modal" data-target="#deletehiring-{{ $hiring->id }}" />
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
                                                  </td>
                                              </tr>
                                              @empty
                                              <tr>
                                                  <td colspan="5">No Open Job Hiring</td>
                                              </tr>
                                              @endforelse
                                          </tbody>
                                      </table>
                                      <div class="card-footer clearfix pagination-sm">
                                          {{ $hiringOpen->links('pagination::bootstrap-5') }}
                                      </div>
                                  </div>
                              </div>
                              <div class="tab-pane" id="inProgressTab">
                                  <div class="card-header">
                                      In Progress Job Hirings
                                  </div>
                                  <div class="card-body">
                                      <!-- Closed jobs table -->
                                      <table class="table table-bordered text-center">
                                          <thead class="table-primary">
                                              <tr>
                                                  <th>Reference</th>
                                                  <th>Job Position</th>
                                                  <th>Status</th>
                                                  <th>Department</th>
                                                  <th>Applicants</th>
                                                  <th>Action</th>
                                              </tr>
                                          </thead>
                                          <tbody>
                                              @forelse ($hiringInProgress as $inProgress)
                                              <tr>
                                                  <td>{{ $inProgress->reference }}</td>
                                                  <td>{{ $inProgress->job_position }}</td>
                                                  <td>{{ $inProgress->job_status . ' Phase' }}</td>
                                                  <td>{{ $inProgress->department }}</td>
                                                  <td><span class="badge bg-danger">{{ $inProgress->application_count }}</span></td>
                                                  <td>
                                                      <!-- View and delete buttons -->
                                                      <a class="btn btn-success" href="{{ route('hirings.show', $inProgress) }}">View</a>
                                                      <x-adminlte-button class="btn btn-danger" label="Delete" theme="danger" data-toggle="modal" data-target="#deletehiring-{{ $inProgress->id }}" />
                                                      <x-adminlte-modal id="deletehiring-{{ $inProgress->id }}" title="Delete Hiring">
                                                        <h5>Do you want to delete the job posting? All other data from application will be deleted also.</h5>
                                                        <form action="{{ route('hirings.destroy', $inProgress) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <x-slot name="footerSlot">
                                                                <button type="submit" class="btn btn-danger text-end">Delete</button>
                                                            </form>
                                                                <x-adminlte-button theme="secondary" label="Cancel" data-dismiss="modal" />
                                                            </x-slot>
                                                      </x-adminlte-modal> 
                                                  </td>
                                              </tr>
                                              @empty
                                              <tr>
                                                  <td colspan="5">No Closed Hiring Jobs</td>
                                              </tr>
                                              @endforelse
                                          </tbody>
                                      </table>
                                      <div class="card-footer clearfix pagination-sm">
                                        {{ $hiringOpen->links('pagination::bootstrap-5') }}
                                    </div>
                                  </div>
                              </div>
                              <div class="tab-pane" id="ArchivedTab">
                                <div class="card-header">
                                    Archived Job Hirings
                                </div>
                                <div class="card-body">
                                    <!-- Archived jobs table -->
                                    <table class="table table-bordered text-center">
                                        <thead class="table-primary">
                                            <tr>
                                                <th>Reference</th>
                                                <th>Job Position</th>
                                                <th>Department</th>
                                                <th>Applicants</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($hiringArchived as $hiringArchive)
                                            <tr>
                                                <td>{{ $hiringArchive->reference }}</td>
                                                <td>{{ $hiringArchive->job_position }}</td>
                                                <td>{{ $hiringArchive->department }}</td>
                                                <td><span class="badge bg-danger">{{ $hiringArchive->application_count }}</span></td>
                                                <td>
                                                    <!-- View and delete buttons -->
                                                    <a class="btn btn-success" href="{{ route('hirings.show', $hiringArchive) }}">View</a>
                                                    <!-- Adjust the modal ID to make it unique -->
                                                    <x-adminlte-button class="btn btn-danger" label="Delete" theme="danger" data-toggle="modal" data-target="#deletehiring-{{ $hiringArchive->id }}" />
                                                    <x-adminlte-modal id="deletehiring-{{ $hiringArchive->id }}" title="Delete Hiring">
                                                      <h5>Do you want to delete the job posting? All other data from application will be deleted also.</h5>
                                                      <form action="{{ route('hirings.destroy', $hiringArchive) }}" method="POST">
                                                          @csrf
                                                          @method('DELETE')
                                                          <x-slot name="footerSlot">
                                                              <button type="submit" class="btn btn-danger text-end">Delete</button>
                                                          </form>
                                                              <x-adminlte-button theme="secondary" label="Cancel" data-dismiss="modal" />
                                                          </x-slot>
                                                    </x-adminlte-modal>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="5">No Archived Hiring Jobs</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    <div class="card-footer clearfix pagination-sm">
                                      {{ $hiringOpen->links('pagination::bootstrap-5') }}
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
  </section>
@stop
@section('footer')
    <div class="float-right">
        2024
    </div>

    <strong>
        <a href="{{route('home')}}">
          Department of Science and Technology-REGION V
        </a>
    </strong>
@stop
@section('css')
  
@stop

@section('js')
<script>
    $(document).ready(function(){
        // Remember the active tab
        var activeTab = localStorage.getItem('activeTab');
        if(activeTab){
            $('.nav-link[href="' + activeTab + '"]').tab('show');
        }

        // Save the active tab when a tab is clicked
        $('.nav-link').on('shown.bs.tab', function (e) {
            var tab = e.target.getAttribute('href');
            localStorage.setItem('activeTab', tab);
        });
    });
</script>
@stop
