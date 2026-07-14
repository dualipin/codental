<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar Horario - CoDentaL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
    <style>
        body { background: linear-gradient(180deg, #f8fafc 0%, #eef6ff 100%); font-family: 'Segoe UI', sans-serif; }
        .card-box { border: none; box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08); border-radius: 16px; background: white; padding: 28px; }
        .banner-paciente { background: linear-gradient(135deg, #e0f2fe 0%, #f8fbff 100%); border: 1px solid #cfe8fb; border-left: 6px solid #0284c7; border-radius: 14px; padding: 18px; margin-bottom: 22px; }
        .doctor-card { display: flex; align-items: center; gap: 14px; background: #ffffff; border: 1px solid #dbeafe; border-radius: 14px; padding: 14px; box-shadow: 0 6px 20px rgba(2, 132, 199, 0.08); }
        .doctor-card img { width: 76px; height: 76px; border-radius: 50%; object-fit: cover; background: #e9eef5; flex: 0 0 auto; }
        .doctor-card__name { font-weight: 700; color: #0f172a; margin-bottom: 2px; }
        .doctor-card__specialty { color: #475569; font-size: 14px; }
        .doctor-card__meta { min-width: 0; }
        .legend { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 14px; }
        .legend-item { display: inline-flex; align-items: center; gap: 8px; font-size: 13px; color: #334155; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 999px; padding: 6px 10px; }
        .legend-swatch { width: 12px; height: 12px; border-radius: 999px; display: inline-block; }
        .legend-swatch--pending { background: #dc2626; }
        .legend-swatch--accepted { background: #16a34a; }
        .legend-swatch--other { background: #64748b; }
        .schedule-hint { font-size: 13px; color: #64748b; background: #f8fafc; border-left: 3px solid #38bdf8; border-radius: 8px; padding: 10px 12px; margin-top: 12px; }
        .selection-box { background: #f8fafc; border: 1px dashed #cbd5e1; padding: 12px 14px; border-radius: 10px; margin-top: 16px; color: #334155; }
        #calendar { background: white; padding: 12px; border-radius: 14px; box-sizing: border-box; min-height: 680px; }
        .fc .fc-timegrid-slot { height: 54px !important; }
        .fc .fc-timegrid-axis-cushion,
        .fc .fc-col-header-cell-cushion,
        .fc .fc-timegrid-slot-label-cushion { font-size: 13px; }
        .fc .fc-event { border: 0; border-radius: 10px; overflow: hidden; }
        .calendar-event { display: flex; flex-direction: column; gap: 2px; line-height: 1.2; white-space: normal; padding: 2px 4px; }
        .calendar-event__time { font-size: 11px; font-weight: 700; opacity: 0.95; }
        .calendar-event__title { font-size: 12px; font-weight: 700; }
        .calendar-event__status { font-size: 11px; opacity: 0.95; }
    </style>
</head>
<body>
<div class="container mt-3">
    <a href="{{ route('nosotros') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fa-solid fa-right-from-bracket me-1"></i> Salir
    </a>
</div>
<div class="container my-5">

    <div class="card-box">
        <div class="banner-paciente shadow-sm">
            <div class="row g-3 align-items-center">
                <div class="col-lg-7">
                    <h5 class="text-dark mb-1"><i class="fa-solid fa-user-check text-primary me-2"></i> Datos del Solicitante</h5>
                    <p class="mb-0 fs-5 text-dark">
                        <strong>Paciente:</strong> {{ $paciente->pnom }} {{ $paciente->papp }} {{ $paciente->papm }}
                        <span class="badge bg-secondary ms-2">ID clínico seguro</span>
                    </p>
                    <p class="mb-0 text-secondary mt-1"><strong>Médico asignado:</strong> {{ $doctor?->nom ? 'Dr(a). ' . $doctor->nom . ' ' . $doctor->app : 'Sin doctor asignado' }}</p>
                    <div class="legend">
                        <span class="legend-item"><span class="legend-swatch legend-swatch--pending"></span> Pendientes</span>
                        <span class="legend-item"><span class="legend-swatch legend-swatch--accepted"></span> Aceptadas</span>
                        <span class="legend-item"><span class="legend-swatch legend-swatch--other"></span> Otros estados</span>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="doctor-card">
                        <img src="{{ $doctor && $doctor->img ? asset('storage/' . ltrim($doctor->img, '/')) : 'https://via.placeholder.com/96?text=Dr' }}" alt="Foto del dentista">
                        <div class="doctor-card__meta">
                            <div class="doctor-card__name">{{ $doctor?->nom ? 'Dr(a). ' . $doctor->nom . ' ' . $doctor->app : 'Doctor no disponible' }}</div>
                            <div class="doctor-card__specialty">{{ $doctor?->esp ?: 'Sin especialidad registrada' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <form action="{{ route('citas.store') }}" method="POST" class="p-3 bg-light rounded shadow-sm">
                    @csrf
                    <h4 class="h5 mb-3 text-secondary">Horario de la Cita</h4>
                    
                    <input type="hidden" name="fec" id="fec-input" required>
                    <input type="hidden" name="hor" id="hor-input" required>
                    
                    <div class="mb-3">
                        <label class="form-label">Fecha Seleccionada:</label>
                        <input type="text" id="fec-display" class="form-control fw-bold text-primary" readonly placeholder="Elige en el mapa">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hora Seleccionada:</label>
                        <input type="text" id="hor-display" class="form-control fw-bold text-primary" readonly placeholder="Elige en el mapa">
                    </div>

                    <div class="schedule-hint">
                        Usa la vista semanal o diaria para elegir una hora libre. Las citas ocupadas aparecen en rojo o verde según su estado.
                    </div>

                    <div class="selection-box" id="selection-box">Día y hora seleccionados: ninguno</div>

                    <button type="submit" class="btn btn-primary w-100 py-2 mt-3 fw-bold">
                        <i class="fa-solid fa-calendar-check me-2"></i> Agendar con {{ $doctor?->nom ? 'Dr(a). ' . $doctor->nom : 'el doctor asignado' }}
                    </button>
                </form>
            </div>

            <div class="col-md-8">
                <div id="calendar" class="p-2 bg-white border rounded shadow-sm"></div>
            </div>
        </div>
    </div>
</div>

<script id="eventos-data" type="application/json">{!! $eventosJson !!}</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var fecInput = document.getElementById('fec-input');
        var horInput = document.getElementById('hor-input');
        var fecDisplay = document.getElementById('fec-display');
        var horDisplay = document.getElementById('hor-display');
        var selectionBox = document.getElementById('selection-box');
        var eventosData = document.getElementById('eventos-data');

        var currentEvents = JSON.parse(eventosData.textContent || '[]');

        function formatDate(date) {
            return new Intl.DateTimeFormat('es-MX', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            }).format(date);
        }

        function setSelection(dateValue, timeValue) {
            fecInput.value = dateValue;
            horInput.value = timeValue;
            fecDisplay.value = formatDate(new Date(dateValue + 'T00:00:00'));
            horDisplay.value = timeValue;
            selectionBox.textContent = 'Día y hora seleccionados: ' + fecDisplay.value + ' a las ' + timeValue;
        }

        function clearSelection() {
            fecInput.value = '';
            horInput.value = '';
            fecDisplay.value = '';
            horDisplay.value = '';
            selectionBox.textContent = 'Día y hora seleccionados: ninguno';
        }

        function hasOverlap(start, end) {
            return currentEvents.some(function(evento) {
                var eventStart = new Date(evento.start);
                var eventEnd = new Date(evento.end || evento.start);
                return start < eventEnd && end > eventStart;
            });
        }

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            locale: 'es',
            timeZone: 'local',
            height: 'auto',
            expandRows: true,
            selectable: true,
            selectMirror: true,
            nowIndicator: true,
            allDaySlot: false,
            eventOverlap: false,
            selectOverlap: false,
            slotMinTime: '08:00:00',
            slotMaxTime: '19:00:00',
            slotDuration: '00:30:00',
            slotLabelInterval: '01:00:00',
            snapDuration: '00:30:00',
            eventTimeFormat: {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            },
            buttonText: {
                today: 'Hoy',
                month: 'Mes',
                week: 'Semana',
                day: 'Día'
            },
            headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek,timeGridDay' },
            events: currentEvents,
            eventContent: function(arg) {
                var statusText = arg.event.extendedProps.estadoTexto || '';
                var timeText = arg.timeText || '';

                return {
                    html: '<div class="calendar-event">' +
                        (timeText ? '<div class="calendar-event__time">' + timeText + '</div>' : '') +
                        '<div class="calendar-event__title">' + arg.event.title + '</div>' +
                        (statusText ? '<div class="calendar-event__status">' + statusText + '</div>' : '') +
                    '</div>'
                };
            },
            dateClick: function(info) {
                if (calendar.view.type === 'dayGridMonth') {
                    calendar.changeView('timeGridDay', info.date);
                    return;
                }

                var dateValue = info.dateStr.split('T')[0];
                var timeValue = info.dateStr.split('T')[1] ? info.dateStr.split('T')[1].slice(0, 5) : '';

                if (timeValue) {
                    var slotEnd = new Date(info.date.getTime() + 30 * 60000);

                    if (hasOverlap(info.date, slotEnd)) {
                        alert('Ese horario ya está ocupado para este doctor. Elige otra hora.');
                        return;
                    }

                    setSelection(dateValue, timeValue);
                }
            },
            selectAllow: function(info) {
                return !hasOverlap(info.start, info.end);
            },
            select: function(info) {
                var dateValue = info.startStr.split('T')[0];
                var timeValue = info.startStr.split('T')[1] ? info.startStr.split('T')[1].slice(0, 5) : '';

                if (!timeValue) {
                    return;
                }

                if (hasOverlap(info.start, info.end)) {
                    alert('Ese horario ya está ocupado para este doctor. Elige otra hora.');
                    calendar.unselect();
                    return;
                }

                setSelection(dateValue, timeValue);
            }
        });

        clearSelection();
        calendar.render();
    });
</script>
</body>
</html> 