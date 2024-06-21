<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield("title", "DOST V -Job Portal")</title>
    <link rel="icon" href="{{asset('assets/image 1.png')}}" type="image/x-icon"/>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.js'></script>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://momentjs.com/downloads/moment.min.js"></script>
</head>
<body>
    <x-navigation>
    </x-navigation>
    @yield("content")
</body>
{{-- <footer>
    <div class="container rounded-top-5 bg-light shadow p-0">
        <div class="text-center text-muted">
            <a class="text-reset fw-bold" href="https://region5.dost.gov.ph/">© 2021 Copyright: DOST Region V</a>
        </div>
    </div>
</footer> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    @if (Session::has('success'))
    <script>
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "timeOut": "5000",
            "positionClass": "toast-top-right"
        }
        toastr.success("{{ Session::get('success') }}");
    </script>
    @elseif (Session::has('error'))
    <script>
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "timeOut": "5000",
            "positionClass": "toast-top-right"
        }
        toastr.error("{{ Session::get('error') }}");
    </script>
    @elseif (Session::has('notification'))
    <script>
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "timeOut": "5000",
            "positionClass": "toast-top-right"
        }
        toastr.info("You have a new notification. Check");
    </script>
    @elseif (Session::has('info'))
    <script>
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "timeOut": "5000",
            "positionClass": "toast-top-right"
        }
        toastr.info("{{ Session::get('info') }}");
    </script>
    @endif
    <script>
        $(document).ready(function() {
            // Inject styles dynamically
            var style = document.createElement('style');
            style.type = 'text/css';
            style.innerHTML = `
                #notifications-menu {
                    width: 400px;
                    word-wrap: break-word;
                    overflow-wrap: break-word;
                    height: 300px;
                    overflow-y: scroll;
                }
                .notification-item {
                    white-space: normal;
                    text-align: justify;
                    margin-bottom: 10px;
                }
            `;
            document.getElementsByTagName('head')[0].appendChild(style);

            function fetchNotifications() {
                $.ajax({
                    url: 'notifications/getUser',
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        var notifMenu = $("#notifications-menu");
                        notifMenu.empty(); // Clear any existing notifications
                        var unreadCount = data.unread_count; 
                        $("#unread-count").text(unreadCount);

                            // Create Mark as Read button form
                        var markAsReadForm = `
                            <form action="/markAsRead" method="POST" class="text-end">
                                <input name="_token" value="{{ csrf_token() }}" type="hidden">
                                <button type="submit" class="btn">Mark as Read</button>
                            </form>
                        `;
                        notifMenu.append(markAsReadForm);

                        data.notifications.forEach(function(notification, index) {
                            // Check if created_at exists and format it using moment.js
                            var time = notification.created_at ? '<small class="text-muted">' + moment(notification.created_at).fromNow() + '</small>' : '';

                            var backgroundColor = notification.status === 'unread' ? '#cceeff' : '#f2f2f2';

                            var notificationHtml = '<p class="dropdown-item notification-item" style="background-color: ' + backgroundColor + ';">' + notification.message + '<br>' + time + '</p>';

                            notifMenu.append(notificationHtml);

                            if (index < data.length - 1) {
                                notifMenu.append('<div class="dropdown-divider"></div>');
                            }
                        });
                    },
                    error: function() {
                        $("#notifications-menu").html('<a class="dropdown-item" href="#">An error occurred while fetching notifications</a>');
                    }
                });
            }

            // Fetch notifications on page load
            fetchNotifications();

            // Set an interval to fetch notifications every 10 seconds (10000 milliseconds)
            setInterval(fetchNotifications, 10000);
        });
    </script>
    <script>
        $(document).ready(function () {
            var calendarEl = document.getElementById("calendar");
      
            // Fetch events from the backend
            $.ajax({
                url: '/user-schedule',
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
                        height: 470,
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
</html>