@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.js'></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
@stop

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-6">
                  <div class="small-box bg-info">
                    <div class="inner">
                      <h3>{{ $activeJobHiringsCount }}</h3>
                      <p>Active Job Hiring</p>
                    </div>
                    <div class="icon">
                      <i class="fas fa-briefcase "></i>
                    </div>
                    <a href="#" class="small-box-footer">
                      More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                  </div>
                </div>
                <div class="col-lg-3 col-6">
                  <div class="small-box bg-success">
                    <div class="inner">
                      <h3>{{$applicantsCount}}</h3>
                      <p>Applicants</p>
                    </div>
                    <div class="icon">
                      <i class="fas fa-file"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                      More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                  </div>
                </div>
                <div class="col-lg-3 col-6">
                  <div class="small-box bg-warning">
                    <div class="inner">
                      <h3>{{$usersCount}}</h3>
      
                      <p>Users</p>
                    </div>
                    <div class="icon">
                      <i class="fas fa-users"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                      More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                  </div>
                </div>
                <div class="col-lg-3 col-6">
                  <div class="small-box bg-dark">
                    <div class="inner">
                      <h3>{{$archivedJobHiringsCount}}</h3>
                      <p>Archived Jobs</p>
                    </div>
                    <div class="icon">
                      <i class="fas fa-box-archive"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                      Archived Hiring <i class="fas fa-arrow-circle-right"></i>
                    </a>
                  </div>
                </div>
                <div class="col-12 mb-5" style="background: white; border-radius: 10px; padding: 10px;">
                  <div id='calendar'></div>
                </div>
                {{-- <div class="col-4 mt-3">
                    <div class="card card-success">
                      <div class="card-header">
                        <h3 class="card-title">Collapsable</h3>
        
                        <div class="card-tools">
                          <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                          </button>
                        </div>
                        <!-- /.card-tools -->
                      </div>
                      <!-- /.card-header -->
                      <div class="card-body">
                        The body of the card
                      </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
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
  $(document).ready(function () {
      var calendarEl = document.getElementById("calendar");

      // Fetch events from the backend
      $.ajax({
          url: '/getDates',
          method: 'GET',
          success: function (data) {
              // Log the events to the console for debugging
              console.log(data);

              // Initialize FullCalendar with the fetched events
              var calendar = new FullCalendar.Calendar(calendarEl, {
                  headerToolbar: {
                      left: "prev,next today",
                      center: "title",
                      right: "dayGridMonth,timeGridWeek,listMonth"
                  },
                  events: data,
                  editable: false,
                  droppable: false,
                  drop: function (info) {
                      if (checkbox.checked) {
                          info.draggedEl.parentNode.removeChild(info.draggedEl);
                      }
                  }
              });

              calendar.render();
          },
          error: function (error) {
              console.error('Error fetching events:', error);
          }
      });
  });
</script>
@stop