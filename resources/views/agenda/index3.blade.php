<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda - CoDentaL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
    <style>
        body {
            background: #f8fafc;
        }

        .hero-card {
            border: 0;
            border-radius: 20px;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
        }

        .metric {
            border-radius: 18px;
            padding: 18px;
            color: #fff;
            min-height: 120px;
        }

        .metric h3 {
            margin: 0;
            font-size: 34px;
            font-weight: 800;
        }

        #calendar {
            min-height: 720px;
        }

        .event-box {
            font-size: 12px;
            line-height: 1.15;
        }

        .event-box strong {
            display: block;
        }
    </style>
</head>
<body>
@include('layouts.headerprof')

<main class="container py-4">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card hero-card p-4 mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h1 class="h3 mb-1">Agenda principal</h1>
                <p class="text-muted mb-0">Vista operativa para {{ $nombreProf ?? session('nom') }}</p>
            </div>
            <div class="text-muted">Rol: <strong>{{ $rolUsuario ?? session('rol') }}</strong></div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="metric" style="background: linear-gradient(135deg, #0284c7, #0f172a);">
                <div>Citas hoy</div>
                <h3>{{ $citasHoy->count() }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="metric" style="background: linear-gradient(135deg, #16a34a, #14532d);">
                <div>Citas semanales</div>
                <h3>{{ $citasSemanales->count() }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="metric" style="background: linear-gradient(135deg, #f59e0b, #92400e);">
                <div>Doctores visibles</div>
                <h3>{{ $dentistas->count() }}</h3>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-4">
            <div class="card hero-card p-4 h-100">
                <h2 class="h5 mb-3">Filtro de agenda</h2>
                <form method="GET" action="{{ route('agenda') }}" class="vstack gap-3">
                    @if(in_array($rolUsuario, [\App\Enums\UserRolEnum::ADMINISTRADOR->value, \App\Enums\UserRolEnum::RECEPCIONISTA->value], true))
                        <div>
                            <label class="form-label">Dentista</label>
                            <select name="dentista" class="form-select">
                                <option value="">Selecciona un dentista</option>
                                @foreach($dentistas as $dentista)
                                    <option value="{{ $dentista->user }}" @selected($dentistaSeleccionado === $dentista->user)>
                                        {{ trim(($dentista->nom ?? '') . ' ' . ($dentista->app ?? '')) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <button class="btn btn-primary" type="submit">Ver agenda</button>
                </form>

                @if(in_array($rolUsuario, [\App\Enums\UserRolEnum::ADMINISTRADOR->value, \App\Enums\UserRolEnum::RECEPCIONISTA->value], true))
                    <hr>
                    <h3 class="h6">Nueva cita interna</h3>
                    <form method="POST" action="{{ route('agenda.cita.store') }}" class="vstack gap-3">
                        @csrf
                        <div>
                            <label class="form-label">Paciente</label>
                            <select name="id_pac" class="form-select" required>
                                <option value="">Selecciona un paciente</option>
                                @foreach($pacientes as $paciente)
                                    <option value="{{ $paciente->idp }}" @selected($pacienteSeleccionado === $paciente->idp)>
                                        {{ $paciente->pnom }} {{ $paciente->papp }} - {{ $paciente->ptel }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Dentista</label>
                            <select name="d_user" class="form-select" required>
                                <option value="">Selecciona un dentista</option>
                                @foreach($dentistas as $dentista)
                                    <option value="{{ $dentista->user }}" @selected($dentistaSeleccionado === $dentista->user)>
                                        {{ trim(($dentista->nom ?? '') . ' ' . ($dentista->app ?? '')) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="form-label">Fecha</label>
                                <input type="date" name="fec" class="form-control" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Hora</label>
                                <input type="time" name="hor" class="form-control" required>
                            </div>
                        </div>
                        <div>
                            <label class="form-label">Duración</label>
                            <select name="dur" class="form-select" required>
                                <option value="15">15 min</option>
                                <option value="30" selected>30 min</option>
                                <option value="45">45 min</option>
                                <option value="60">1 hora</option>
                                <option value="90">1.5 horas</option>
                                <option value="120">2 horas</option>
                                <option value="180">3 horas</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Estado</label>
                            <select name="est" class="form-select">
                                <option value="pendiente">Pendiente</option>
                                <option value="confirmada">Confirmada</option>
                            </select>
                        </div>
                        <button class="btn btn-success" type="submit">Agendar</button>
                    </form>
                @endif
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card hero-card p-3">
                <div id="calendar"></div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card hero-card p-4 h-100">
                <h2 class="h5 mb-3">Citas de hoy</h2>
                <ul class="list-group list-group-flush">
                    @forelse($citasHoy as $cita)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $cita->paciente?->pnom }} {{ $cita->paciente?->papp }}</strong>
                                <div class="text-muted small">{{ $cita->fec_i?->format('H:i') }}
                                    - {{ $cita->fec_f?->format('H:i') }}</div>
                            </div>
                            <span class="badge bg-secondary">{{ $cita->est }}</span>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">No hay citas para hoy.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card hero-card p-4 h-100">
                <h2 class="h5 mb-3">Agenda semanal</h2>
                <ul class="list-group list-group-flush">
                    @forelse($citasSemanales as $cita)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $cita->paciente?->pnom }} {{ $cita->paciente?->papp }}</strong>
                                <div class="text-muted small">{{ $cita->fec_i?->format('d/m/Y H:i') }}</div>
                            </div>
                            <span class="badge bg-info text-dark">{{ $cita->est }}</span>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">No hay citas esta semana.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</main>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    const eventosNode = document.getElementById('agenda-eventos');
    const eventos = JSON.parse(eventosNode.textContent || '[]');

    const calendar = new FullCalendar.Calendar(calendarEl, {
      locale: 'es',
      initialView: 'timeGridWeek',
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay'
      },
      slotMinTime: '08:00:00',
      slotMaxTime: '19:00:00',
      allDaySlot: false,
      nowIndicator: true,
      selectable: false,
      events: eventos,
      eventContent: function (arg) {
        const doctor = arg.event.extendedProps.doctor
          ? `<div class="small">${ arg.event.extendedProps.doctor }</div>`
          : '';
        return {
          html: `<div class="event-box"><strong>${ arg.event.title }</strong><div>${ arg.timeText }</div>${ doctor }<div>${ arg.event.extendedProps.estado ||
          '' }</div></div>`
        };
      }
    });

    calendar.render();
  });
</script>

<script id="agenda-eventos" type="application/json">{{ $eventosJson }}</script>
</body>
</html>
