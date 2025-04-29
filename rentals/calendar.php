<?php include '../includes/db_connect.php'; ?>
<?php include '../includes/admin_sidebar.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Rental Calendar</title>
  <meta charset="utf-8" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
  <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
  <style>
    body {
      background-color: #121212;
      color: #fff;
    }
    .main-content {
      margin-left: 260px;
      padding: 30px;
    }
    #calendar {
      max-width: 1000px;
      margin: auto;
      background: #fff;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0,0,0,0.2);
      color: #000;
    }
    .fc-toolbar-title {
      font-size: 1.5rem !important;
    }
  </style>
</head>
<body>

<div class="main-content">
  <h2 class="text-center mb-4"><i class="bi bi-calendar-event me-2"></i>Rental Calendar</h2>

  <div id='calendar'></div>
</div>

<!-- Modal for Event Details -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-dark text-light">
      <div class="modal-header border-0">
        <h5 class="modal-title" id="eventTitle"></h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <img id="eventImage" src="" class="img-fluid mb-3" style="max-height:300px; border-radius:12px;">
        <p id="eventCustomer" class="mb-2"></p>
        <p id="eventDates" class="text-muted"></p>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      height: 600,
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,listWeek'
      },
      events: 'fetch_rentals.php',
      eventClick: function(info) {
        const event = info.event;
        const title = event.title;
        const start = new Date(event.start).toLocaleDateString();
        const end = new Date(event.end).toLocaleDateString();
        const customer = event.extendedProps.customer;
        const image = event.extendedProps.image;

        // Show nice Bootstrap modal
        const modalHtml = `
          <div class="modal fade" id="rentalModal" tabindex="-1">
            <div class="modal-dialog">
              <div class="modal-content text-dark"> <!-- dark text here -->
                <div class="modal-header bg-dark text-white">
                  <h5 class="modal-title"><i class="bi bi-calendar-event"></i> Rental Details</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                  <img src="${image}" class="img-fluid rounded mb-3" alt="Car Image">
                  <h5 class="mb-2">${title}</h5>
                  <p><strong>Customer:</strong> ${customer}</p>
                  <p><strong>From:</strong> ${start}</p>
                  <p><strong>To:</strong> ${end}</p>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
        `;

        // Remove existing modal if any
        const existingModal = document.getElementById('rentalModal');
        if (existingModal) {
          existingModal.remove();
        }

        document.body.insertAdjacentHTML('beforeend', modalHtml);
        const rentalModal = new bootstrap.Modal(document.getElementById('rentalModal'));
        rentalModal.show();
      }
    });
    calendar.render();
  });
</script>

</body>
</html>
