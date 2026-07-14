<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Identificación - CoDentaL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Segoe UI', sans-serif;
        }

        .card-box {
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border-radius: 12px;
            background: white;
            padding: 30px;
        }

        .hidden-section {
            display: none;
        }

        .doctor-preview {
            display: none;
            gap: 12px;
            align-items: center;
            background: #f8fbff;
            border: 1px solid #d8e5f3;
            border-radius: 8px;
            padding: 12px;
            margin-top: 12px;
        }

        .doctor-preview.is-visible {
            display: flex;
        }

        .doctor-preview img {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            object-fit: cover;
            background: #e9eef5;
            flex: 0 0 auto;
        }

        .doctor-preview .doctor-preview__meta {
            min-width: 0;
        }

        .doctor-preview .doctor-preview__name {
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .doctor-preview .doctor-preview__specialty {
            font-size: 13px;
            color: #4b5563;
        }

        .doctor-preview .doctor-preview__empty {
            font-size: 13px;
            color: #6b7280;
        }

        /* Estilos personalizados para tu helper text con la paleta de la clínica */
        .helper-text {
            font-size: 13px;
            color: #64748b;
            margin-top: 8px;
            padding: 8px 12px;
            background-color: #f0f9ff;
            /* Tono azul celeste muy claro */
            border-left: 3px solid #87CEEB;
            /* Azul celeste */
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <div class="container mt-3 d-flex justify-content-end gap-2">
        <a href="{{ route('nosotros') }}" class="btn btn-outline-secondary btn-sm">Inicio</a>
        <a href="{{ route('login') }}" class="btn btn-primary btn-sm"><i class="fa-solid fa-right-to-bracket me-1"></i> Iniciar sesión</a>
    </div>
    <div class="container my-5">

        @if(session('success'))
        <div class="alert alert-success text-center shadow-sm mb-4">{{ session('success') }}</div>
        @endif
        @if($errors->any())
        <div class="alert alert-danger shadow-sm mb-4">
            <ul class="mb-0">@foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach</ul>
        </div>
        @endif

        <div class="card-box text-center mb-4" id="selector-flujo">
            <h3 class="mb-4 text-secondary">Bienvenido a la Agenda de Citas</h3>
            <h4 class="mb-4">¿Es usted un paciente nuevo en la clínica?</h4>
            <div class="d-md-flex justify-content-center gap-3">
                <button class="btn btn-primary btn-lg px-4 mb-2 mb-md-0" onclick="mostrarNuevo()"><i class="fa-solid fa-user-plus me-2"></i> Sí, soy paciente nuevo</button>
                <button class="btn btn-outline-secondary btn-lg px-4" onclick="mostrarRegistrado()"><i class="fa-solid fa-id-card me-2"></i> No, ya estoy registrado</button>
            </div>
        </div>

        <div id="form-registrado" class="card-box hidden-section">
            <h3 class="text-secondary mb-4"><i class="fa-solid fa-user-check me-2"></i> Verificar Mis Datos</h3>
            <form action="{{ route('agenda.verificar') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-4"><label class="form-label">Nombre(s):</label><input type="text" name="pnom" class="form-control" required></div>
                    <div class="col-md-4"><label class="form-label">Apellido Paterno:</label><input type="text" name="papp" class="form-control" required></div>
                    <div class="col-md-4"><label class="form-label">Apellido Materno:</label><input type="text" name="papm" class="form-control" required></div>
                    <div class="col-md-6"><label class="form-label">Teléfono Registrado (10 dígitos):</label><input type="tel" name="ptel" class="form-control" maxlength="10" required></div>
                </div>
                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-light" onclick="regresar()"><i class="fa-solid fa-arrow-left"></i> Atrás</button>
                    <button type="submit" class="btn btn-success"><i class="fa-solid fa-magnifying-glass me-2"></i> Buscar y Continuar</button>
                </div>
            </form>
        </div>

        <div id="form-nuevo" class="card-box hidden-section">
            <h3 class="text-primary mb-4"><i class="fa-solid fa-file-medical me-2"></i> Cuestionario de Expediente Nuevo</h3>
            <form action="{{ route('agenda.registrar_nuevo') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-4"><label class="form-label">Nombre(s) *</label><input type="text" name="pnom" class="form-control" maxlength="40" required></div>
                    <div class="col-md-4"><label class="form-label">Apellido Paterno *</label><input type="text" name="papp" class="form-control" maxlength="40" required></div>
                    <div class="col-md-4"><label class="form-label">Apellido Materno *</label><input type="text" name="papm" class="form-control" maxlength="40" required></div>
                    <div class="col-md-3"><label class="form-label">Fecha de Nacimiento *</label><input type="date" name="pnac" class="form-control" required></div>
                    <div class="col-md-3">
        <label class="form-label">Sexo *</label>
        <select name="psex" id="psex" class="form-select" required>
            <option value="M">Masculino</option>
            <option value="F">Femenino</option>
        </select>
    </div>
                    <div class="col-md-3"><label class="form-label">Ocupación *</label><input type="text" name="pocu" class="form-control" maxlength="10" required></div>
                    <div class="col-md-3"><label class="form-label">Estado Civil *</label><input type="text" name="pciv" class="form-control" maxlength="15" required></div>
                    <div class="col-md-3"><label class="form-label">Teléfono *</label><input type="tel" name="ptel" class="form-control" maxlength="10" required></div>
                    <div class="col-md-3"><label class="form-label">Correo Electrónico *</label><input type="email" name="pcor" class="form-control" maxlength="35" required></div>
                    <div class="col-md-3"><label class="form-label">Estado *</label><input type="text" name="pest" class="form-control" value="Tabasco" required></div>
                    <div class="col-md-3"><label class="form-label">Municipio *</label><input type="text" name="pmun" class="form-control" required></div>
                    <div class="col-md-9"><label class="form-label">Dirección Completa *</label><input type="text" name="pdir" class="form-control" maxlength="100" required></div>
                    <div class="col-md-3"><label class="form-label">Religion *</label><input type="text" name="prel" class="form-control" maxlength="30" required></div>
                    <div class="col-md-6"><label class="form-label">Enviado por: *</label><input type="text" name="penv" class="form-control" maxlength="40" required></div>
                    <div class="col-md-6"><label class="form-label">Motivo de la consulta: *</label><input type="text" name="pmot" class="form-control" maxlength="20" required></div>

                    <!-- Antecedentes familiares-->
                    <div class="col-md-12"><label class="form-label">Antecedentes hereditarios familiares *</label><input type="text" name="hfam" class="form-control" maxlength="255" required></div>

                    <!-- Antecedentes dentales patologicos -->
                    <label class="form-label fw-bold text-dark">Antecedentes dentales patologicos</label>
                    <div class="col-md-6"><label class="form-label">Alergico a: *</label><input type="text" name="ale" class="form-control" maxlength="50"></div>
                    <div class="col-md-6"><label class="form-label">Medicacion actual: *</label><input type="text" name="meda" class="form-control" maxlength="50"></div>
                    <div class="col-md-6"><label class="form-label">Nombre de su médico *</label><input type="text" name="nmed" class="form-control" maxlength="50"></div>
                    <div class="col-md-3"><label class="form-label">Telefono del medico *</label><input type="text" name="mtel" class="form-control" maxlength="10"></div>
                    <!-- Enfermedades -->
                    <label class="form-label fw-bold text-dark text-center d-block mb-3">Marque el cuadro si tiene o ha tenido alguna de las siguientes enfermedades:</label>

                    <div class="row g-3 mb-3"> <!-- Enfermedades -->
                        <div class="col-md-4 col-sm-6">
                            <label><input type="checkbox" name="e1" value="e1"> Diabetes</label>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label><input type="checkbox" name="e2" value="e2"> VIH</label>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label><input type="checkbox" name="e3" value="e3"> Asma</label>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label><input type="checkbox" name="e4" value="e4"> Hipertension</label>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label><input type="checkbox" name="e5" value="e5"> Sida</label>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label><input type="checkbox" name="e6" value="e6"> Infartos</label>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label><input type="checkbox" name="e7" value="e7"> Cáncer</label>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label><input type="checkbox" name="e8" value="e8"> VPH</label>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label><input type="checkbox" name="e9" value="e9"> Epilepsia</label>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label><input type="checkbox" name="e10" value="e10"> Enfermedades mentales</label>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label><input type="checkbox" name="e11" value="e11"> Enfermedades cardiacas</label>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label><input type="checkbox" name="e12" value="e12"> Hepatitis</label>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label><input type="checkbox" name="e13" value="e13"> Enfermedades hepáticas</label>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label><input type="checkbox" name="e14" value="e14"> Enfermedades glandulares</label>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label><input type="checkbox" name="e15" value="e15"> Anemia</label>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label><input type="checkbox" name="e16" value="e16"> Enfermedades metabólicas</label>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label><input type="checkbox" name="e17" value="e17"> Enfermedades respiratorias</label>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label><input type="checkbox" name="e18" value="e18"> Tuberculosis</label>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label><input type="checkbox" name="e19" value="e19"> Enfermedades de transmisión sexual</label>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label><input type="checkbox" name="e20" value="e20"> Enfermedades digestivas</label>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label><input type="checkbox" name="e21" value="e21" onclick="habilitarInput()" id="e21"> Otras enfermedades</label>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label><input type="checkbox" name="e22" value="e22"> Enfermedades urinarias</label>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label><input type="checkbox" name="e23" value="e23"> Enfermedades óseas</label>
                        </div>
                    </div>

                    <!-- Campo "Especifique" -->
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Especifique:</label>
                            <input type="text" name="esp" id="esp" class="form-control" maxlength="30" disabled>
                        </div>
                    </div>

                    

                    <label class="form-label fw-bold text-dark text-center d-block mb-3">Antecedentes personales no patologicos</label>

                    
    <div class="row g-3">
        <div class="col-md-6">
        <label class="form-label d-block fw-bold">Consumo de:</label>
        <div class="d-flex flex-wrap gap-3 mt-2">
            <div class="form-check">
                <input class="form-check-input check-consumo" type="checkbox" name="c_tab" id="tabaco" value="Si">
                <label class="form-check-label" for="tabaco">Tabaco</label>
            </div>
            <div class="form-check">
                <input class="form-check-input check-consumo" type="checkbox" name="c_alc" id="alcohol" value="Si">
                <label class="form-check-label" for="alcohol">Alcohol</label>
            </div>
            <div class="form-check">
                <input class="form-check-input check-consumo" type="checkbox" name="c_dro" id="drogas" value="Si">
                <label class="form-check-label" for="drogas">Drogas</label>
            </div>
        </div>
    </div>

    <div class="col-md-3" id="div_frecuencia" style="display: none;">
        <label class="form-label fw-bold">Frecuencia de consumo:</label>
        <input type="text" name="fre" class="form-control" maxlength="15" placeholder="Ej. Ocasional, Diario">
    </div>

        <div class="col-md-3">
            <label class="form-label fw-bold">Grupo Sanguíneo:</label>
            <input type="text" name="san" class="form-control" maxlength="3" placeholder="Ej. O+">
        </div>

        <hr class="my-3 text-muted">

        <div id="bloque_femenino" style="display: none;" class="row col-md-9 g-3 m-0 p-0">
        <div class="col-md-4">
            <label class="form-label fw-bold">¿Actualmente está embarazada?</label>
            <select name="emb" id="emb" class="form-select">
                <option value="No">No</option>
                <option value="Si">Sí</option>
            </select>
        </div>

        <div class="col-md-4" id="div_gestacion" style="display: none;">
            <label class="form-label fw-bold">Tiempo de gestación:</label>
            <input type="text" name="tie" class="form-control" maxlength="10" placeholder="Ej. 3 meses">
        </div>

        <div class="col-md-4">
            <label class="form-label fw-bold">¿Estado de lactancia?</label>
            <select name="lat" id="lat" class="form-select">
                <option value="No">No</option>
                <option value="Si">Sí</option>
            </select>
        </div>

        <div class="col-md-4" id="div_bebe" style="display: none;">
            <label class="form-label fw-bold">¿Cuántos meses tiene tu bebé?</label>
            <input type="text" name="beb" class="form-control" maxlength="2" placeholder="Ej. 6">
        </div>
        <hr class="my-3 text-muted">
    </div>

        

        <div class="col-md-4">
            <label class="form-label fw-bold">¿Realizas alguna actividad deportiva?</label>
            <input type="text" name="dep" class="form-control" maxlength="20" placeholder="Especifique deporte">
        </div>

        <div class="col-md-4">
            <label class="form-label fw-bold">¿Cómo consideras tu alimentación?</label>
            <select name="ali" class="form-select">
                <option value="Buena">Buena</option>
                <option value="Regular">Regular</option>
                <option value="Deficiente">Deficiente</option>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label fw-bold">¿Cómo consideras tu higiene personal?</label>
            <select name="hig" class="form-select">
                <option value="Buena">Buena</option>
                <option value="Regular">Regular</option>
                <option value="Deficiente">Deficiente</option>
            </select>
        </div>

        <hr class="my-3 text-muted">

        <div class="col-md-4">
        <label class="form-label fw-bold">¿Has estado internado o te han realizado alguna cirugía?</label>
        <select name="cir" id="cir" class="form-select">
            <option value="No">No</option>
            <option value="Si">Sí</option>
        </select>
    </div>
    <div class="col-md-8" id="div_cir_des" style="display: none;">
        <label class="form-label fw-bold">Describe:</label>
        <textarea name="cir_des" class="form-control" rows="2" maxlength="150" placeholder="Detalle intervenciones quirúrgicas previas..."></textarea>
    </div>

        <div class="col-md-12">
            <label class="form-label fw-bold">Describe tu padecimiento actual (signos, síntomas, tipo de dolor, fecha de inicio, duración, zona, etc.):</label>
            <textarea name="mot" class="form-control" rows="3" maxlength="500" placeholder="Motivo de la consulta dental actual..."></textarea>
        </div>

        <div class="col-md-12">
            <label class="form-label fw-bold">Interrogatorio por aparatos y sistemas (describe si padece algún signo o síntoma):</label>
            <textarea name="inte" class="form-control" rows="3" maxlength="500" placeholder="Sintomatología en otras áreas del cuerpo..."></textarea>
        </div>

        <div class="col-md-12">
            <label class="form-label fw-bold">Últimos exámenes de laboratorios y resultados:</label>
            <textarea name="lab" class="form-control" rows="2" maxlength="500" placeholder="Ej. Química sanguínea, tiempos de coagulación..."></textarea>
        </div>

        <hr class="my-4 text-muted">

        <div class="col-md-12">
            <h5 class="text-secondary mb-3 fw-bold"><i class="fa-solid fa-heart-pulse me-2"></i> Habitus Exterior (Signos Vitales)</h5>
            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center bg-white shadow-sm">
                    <thead class="table-light text-secondary small text-uppercase">
                        <tr>
                            <th style="width: 16%;">Peso (kg)</th>
                            <th style="width: 16%;">Estatura (cm)</th>
                            <th style="width: 16%;">Temperatura (°C)</th>
                            <th style="width: 18%;">Frecuencia cardíaca (lpm)</th>
                            <th style="width: 18%;">Frecuencia respiratoria (rpm)</th>
                            <th style="width: 16%;">Presión arterial (mmHg)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="text" name="pes" class="form-control text-center border-0" placeholder="e.g. 70" maxlength="4"></td>
                            <td><input type="text" name="est" class="form-control text-center border-0" placeholder="e.g. 170" maxlength="4"></td>
                            <td><input type="text" name="tem" class="form-control text-center border-0" placeholder="e.g. 36.5" maxlength="4"></td>
                            <td><input type="text" name="car" class="form-control text-center border-0" placeholder="e.g. 80" maxlength="4"></td>
                            <td><input type="text" name="res" class="form-control text-center border-0" placeholder="e.g. 16" maxlength="4"></td>
                            <td><input type="text" name="pre" class="form-control text-center border-0" placeholder="e.g. 120/80" maxlength="7"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <h4 class="text-primary mb-4"><i class="fa-solid fa-tooth me-2"></i> Antecedentes Personales Bucodentales</h4>
    
    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label fw-bold">¿Última vez que acudió a revisión odontológica?</label>
            <input type="text" name="ult_rev" class="form-control" maxlength="10" placeholder="Ej. Hace 6 meses, un año, ninguna vez">
        </div>
        <div class="col-md-4">
            <label class="form-label fw-bold">Motivo de la última visita a un consultorio dental:</label>
            <input type="text" name="mot_vis" class="form-control" maxlength="50" placeholder="Ej. Limpieza, dolor, extracción, no tuve">
        </div>

        <div class="col-md-3">
        <label class="form-label fw-bold">¿Utiliza auxiliares de limpieza bucal?</label>
        <select name="aux_lim" id="aux_lim" class="form-select">
            <option value="No">No</option>
            <option value="Si">Sí</option>
        </select>
    </div>
    <div class="col-md-4" id="div_aux_cua" style="display: none;">
        <label class="form-label fw-bold">¿Cuáles?</label>
        <input type="text" name="aux_cua" class="form-control" maxlength="50" placeholder="Ej. Hilo dental, enjuague">
    </div>

        <div class="col-md-4">
            <label class="form-label fw-bold">Frecuencia y tiempo que le dedicas al cepillado dental:</label>
            <input type="text" name="cep_fre" class="form-control" maxlength="25" placeholder="Ej. 3 veces al día, 2 min">
        </div>

        <hr class="my-3 text-muted">

        <div class="col-md-3">
    <label class="form-label fw-bold">¿Le han infiltrado anestesia local en boca?</label>
    <select name="ane_loc" id="ane_loc" class="form-select">
        <option value="No">No</option>
        <option value="Si">Sí</option>
    </select>
</div>

<div class="col-md-3" id="div_ane_com" style="display: none;">
    <label class="form-label fw-bold">¿Ha tenido mala experiencia o complicaciones con la anestesia local?</label>
    <select name="ane_com" id="ane_com" class="form-select">
        <option value="No">No</option>
        <option value="Si">Sí</option>
    </select>
</div>

<div class="col-md-4" id="div_ane_des" style="display: none;">
    <label class="form-label fw-bold">Describe en caso de Sí:</label>
    <input type="text" name="ane_des" class="form-control" maxlength="50" placeholder="Detalle de la complicación...">
</div>

        <div class="col-md-3">
        <label class="form-label fw-bold">¿Ha realizado algún remedio casero o naturista?</label>
        <select name="rem_cas" id="rem_cas" class="form-select">
            <option value="No">No</option>
            <option value="Si">Sí</option>
        </select>
    </div>
    <div class="col-md-6" id="div_rem_des" style="display: none;">
        <label class="form-label fw-bold">Describe el remedio casero o naturista utilizado:</label>
        <input type="text" name="rem_des" class="form-control" maxlength="30" placeholder="Especifique si aplicó algún remedio">
    </div>
        <div class="col-md-3">
        <label class="form-label fw-bold">¿Tiene dificultades o dolor al masticar los alimentos?</label>
        <select name="dol_mas" id="dol_mas" class="form-select">
            <option value="No">No</option>
            <option value="Si">Sí</option>
        </select>
    </div>
    <div class="col-md-5" id="div_dol_des" style="display: none;">
        <label class="form-label fw-bold">¿Describa las dificultades o dolor al masticar?</label>
        <input type="text" name="dol_des" class="form-control" maxlength="20" placeholder="Describe si presenta molestia...">
    </div>

    <div class="col-md-3">
        <label class="form-label fw-bold">¿Se ha percatado de sangrado o inflamación?</label>
        <select name="san_inf" id="san_inf" class="form-select">
            <option value="No">No</option>
            <option value="Si">Sí</option>
        </select>
    </div>
    <div class="col-md-6" id="div_san_des" style="display: none;">
        <label class="form-label fw-bold">Describe el sangrado, inflamación y/o dolor:</label>
        <input type="text" name="san_des" class="form-control" maxlength="20" placeholder="Especifique zona o síntomas">
    </div>

    <div class="col-md-3">
        <label class="form-label fw-bold">¿Padece úlceras bucales / fuegos?</label>
        <select name="ulc_buc" id="ulc_buc" class="form-select">
            <option value="No">No</option>
            <option value="Si">Sí</option>
        </select>
    </div>
    <div class="col-md-4" id="div_ulc_fre" style="display: none;">
        <label class="form-label fw-bold">Frecuencia:</label>
        <input type="text" name="ulc_fre" class="form-control" maxlength="20" placeholder="Ej. Cada mes, recurrente">
    </div>

    <div class="col-md-3">
        <label class="form-label fw-bold">¿Tiene algún hábito que realice consciente o inconscientemente?</label>
        <select name="hab_boc" id="hab_boc" class="form-select">
            <option value="No">No</option>
            <option value="Si">Sí</option>
        </select>
    </div>
    <div class="col-md-5" id="div_hab_cua" style="display: none;">
        <label class="form-label fw-bold">¿Cual o cuales?</label>
        <input type="text" name="hab_cua" class="form-control" maxlength="100" placeholder="Ej. Morderse las uñas, bruxismo...">
    </div>


<div style="display: none;">
        <div class="col-md-12">
            <label class="form-label fw-bold">Observaciones generales:</label> <!-- inhabilitado solo para dent o admin -->
            <textarea name="obs_buc" class="form-control" rows="2" maxlength="500" placeholder="Observaciones extras del paciente..."></textarea>
        </div>

        <hr class="my-4 text-muted">

        <div class="col-md-8">
            <div class="p-3 border rounded bg-light">
                <h5 class="text-secondary mb-3 fw-bold"><i class="fa-solid fa-angles-down me-2"></i> Articulación Temporomandibular (ATM)</h5>
                
                <div class="mb-2 row align-items-center">
                    <label class="col-sm-9 form-label m-0">¿Dificultad, limitación, fatiga y/o dolor para abrir y cerrar la boca?</label>
                    <div class="col-sm-3"><select name="atm_mov" class="form-select form-select-sm"><option value="No">No</option><option value="Si">Si</option></select></div>
                </div>
                <div class="mb-2 row align-items-center">
                    <label class="col-sm-9 form-label m-0">¿Dificultad, limitación y/o dolor al realizar movimientos de lateralidad?</label>
                    <div class="col-sm-3"><select name="atm_lat" class="form-select form-select-sm"><option value="No">No</option><option value="Si">Si</option></select></div>
                </div>
                <div class="mb-2 row align-items-center">
                    <label class="col-sm-9 form-label m-0">¿Chasquidos o crepitaciones al realizar movimientos de apertura, cierre o lateralidad?</label>
                    <div class="col-sm-3"><select name="atm_cha" class="form-select form-select-sm"><option value="No">No</option><option value="Si">Si</option></select></div>
                </div>
                <div class="mb-1 row align-items-center">
                    <label class="col-sm-9 form-label m-0">¿Te has percatado de desviación de tu mandíbula al movimiento de apertura y cierre?</label>
                    <div class="col-sm-3"><select name="atm_des" class="form-select form-select-sm"><option value="No">No</option><option value="Si">Si</option></select></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="p-3 border rounded bg-white h-100">
                <h5 class="text-secondary mb-3 fw-bold"><i class="fa-solid fa-grip-lines me-2"></i> Análisis de Oclusión</h5>
                <div class="mb-2">
                    <label class="form-label small fw-bold m-0">Clase Molar:</label>
                    <input type="text" name="ocl_mo" class="form-control form-control-sm" placeholder="Ej. Clase I, II, III">
                </div>
                <div class="mb-2">
                    <label class="form-label small fw-bold m-0">Clase Canina:</label>
                    <input type="text" name="ocl_ca" class="form-control form-control-sm" placeholder="Ej. Clase I">
                </div>
                <div class="mb-2">
                    <label class="form-label small fw-bold m-0">Overjet:</label>
                    <input type="text" name="ocl_ovj" class="form-control form-control-sm" placeholder="Ej. 2 mm">
                </div>
                <div>
                    <label class="form-label small fw-bold m-0">Overbite:</label>
                    <input type="text" name="ocl_ovb" class="form-control form-control-sm" placeholder="Ej. 30%">
                </div>
            </div>
        </div>

        <hr class="my-4 text-muted">

        <div class="col-md-12">
            <h5 class="text-secondary mb-2 fw-bold"><i class="fa-solid fa-circle-nodes me-2"></i> Tejidos Blandos y Duros</h5>
            <p class="text-muted small mb-2">Mediante la observación y palpación, registrar características en orden secuencial (ubicación, número de lesiones, forma, tamaño, color, consistencia, sintomatología, tiempo de evolución y tratamiento previo).</p>
            <textarea name="tej_b" class="form-control" rows="4" placeholder="Escriba aquí los hallazgos clínicos de la exploración de tejidos..."></textarea>
        </div>
    </div>
    </div>
    
                    <div class="col-md-12 mt-3">
                        <div class="form-group">
                            <label class="form-label fw-bold text-dark">Selecciona el Dentista:</label>
                            <select name="d_user" id="doctor-select" class="form-select" required>
                                <option value="">-- Seleccione un médico --</option>
                                @foreach($doctores as $doc)
                                <option
                                    value="{{ $doc->user }}"
                                    data-name="Dr(a). {{ $doc->nom }} {{ $doc->app }}"
                                    data-specialty="{{ $doc->esp ?: 'Sin especialidad registrada' }}"
                                    data-image="{{ $doc->img ? asset('storage/' . ltrim($doc->img, '/')) : '' }}">Dr(a). {{ $doc->nom }} {{ $doc->app }}</option>
                                @endforeach
                            </select>
                            <div class="helper-text">
                                <i class="fa-solid fa-circle-info text-info me-1"></i> Primero elige un dentista, luego haz clic en un día del calendario y selecciona una hora libre.
                            </div>
                        </div>
                        <div class="doctor-preview" id="doctor-preview">
                            <img id="doctor-preview-img" alt="Foto del dentista" src="">
                            <div class="doctor-preview__meta">
                                <div class="doctor-preview__name" id="doctor-preview-name"></div>
                                <div class="doctor-preview__specialty" id="doctor-preview-specialty"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-light" onclick="regresar()"><i class="fa-solid fa-arrow-left"></i> Atrás</button>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-2"></i> Guardar y Ver Agenda</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function habilitarInput() {
            var checkbox = document.getElementById('e21');
            var input = document.getElementById('esp');

            // Si el checkbox está marcado, quita el atributo disabled; si no, lo añade
            if (checkbox.checked) {
                input.removeAttribute('disabled');
            } else {
                input.setAttribute('disabled', 'disabled');
            }
        }

        function mostrarNuevo() {
            document.getElementById('selector-flujo').classList.add('hidden-section');
            document.getElementById('form-nuevo').classList.remove('hidden-section');
        }

        function mostrarRegistrado() {
            document.getElementById('selector-flujo').classList.add('hidden-section');
            document.getElementById('form-registrado').classList.remove('hidden-section');
        }

        function regresar() {
            document.getElementById('form-nuevo').classList.add('hidden-section');
            document.getElementById('form-registrado').classList.add('hidden-section');
            document.getElementById('selector-flujo').classList.remove('hidden-section');
        }

        document.addEventListener('DOMContentLoaded', function() {
            var doctorSelect = document.getElementById('doctor-select');
            var doctorPreview = document.getElementById('doctor-preview');
            var doctorPreviewImg = document.getElementById('doctor-preview-img');
            var doctorPreviewName = document.getElementById('doctor-preview-name');
            var doctorPreviewSpecialty = document.getElementById('doctor-preview-specialty');

            function hidePreview() {
                doctorPreview.classList.remove('is-visible');
                doctorPreviewImg.src = '';
                doctorPreviewImg.alt = 'Foto del dentista';
                doctorPreviewName.textContent = '';
                doctorPreviewSpecialty.textContent = '';
            }

            function updatePreview() {
                var option = doctorSelect.options[doctorSelect.selectedIndex];

                if (!doctorSelect.value || !option) {
                    hidePreview();
                    return;
                }

                var doctorName = option.getAttribute('data-name') || option.textContent.trim();
                var doctorSpecialty = option.getAttribute('data-specialty') || 'Sin especialidad registrada';
                var doctorImage = option.getAttribute('data-image') || '';

                doctorPreviewImg.src = doctorImage || 'https://via.placeholder.com/72?text=Dr';
                doctorPreviewImg.alt = doctorName;
                doctorPreviewName.textContent = doctorName;
                doctorPreviewSpecialty.textContent = 'Especialidad: ' + doctorSpecialty;
                doctorPreview.classList.add('is-visible');
            }

            doctorSelect.addEventListener('change', updatePreview);
            updatePreview();
        });


        document.addEventListener("DOMContentLoaded", function () {
    
    // ========================================================
    // 1. CONTROL DE SEXO Y BLOQUE FEMENINO (Ya funciona)
    // ========================================================
    const psex = document.getElementById("psex");
    const bloqueFemenino = document.getElementById("bloque_femenino");
    
    if (psex && bloqueFemenino) {
        psex.addEventListener("change", function() {
            if (this.value === "F") {
                bloqueFemenino.style.display = "flex";
            } else {
                bloqueFemenino.style.display = "none";
                document.getElementById("emb").value = "No";
                document.getElementById("lat").value = "No";
                document.getElementById("div_gestacion").style.display = "none";
                document.getElementById("div_bebe").style.display = "none";
            }
        });
    }

    // Sub-bloques de embarazo y lactancia
    const emb = document.getElementById("emb");
    if (emb) {
        emb.addEventListener("change", function() {
            document.getElementById("div_gestacion").style.display = this.value === "Si" ? "block" : "none";
        });
    }

    const lat = document.getElementById("lat");
    if (lat) {
        lat.addEventListener("change", function() {
            document.getElementById("div_bebe").style.display = this.value === "Si" ? "block" : "none";
        });
    }

    // ========================================================
    // 2. CONTROL DE CHECKBOXES DE CONSUMO (Ya funciona)
    // ========================================================
    const checkboxes = document.querySelectorAll(".check-consumo");
    const divFrecuencia = document.getElementById("div_frecuencia");
    checkboxes.forEach(chk => {
        chk.addEventListener("change", function() {
            // Si al menos uno está seleccionado, muestra frecuencia
            const algunoActivo = Array.from(checkboxes).some(c => c.checked);
            divFrecuencia.style.display = algunoActivo ? "block" : "none";
        });
    });

    // ========================================================
    // 3. CORRECCIÓN DEL RESTO DE LOS SELECTS (Dinamismo Directo)
    // ========================================================

    // Cirugías
    const cir = document.getElementById("cir");
    if (cir) {
        cir.addEventListener("change", function() {
            document.getElementById("div_cir_des").style.display = this.value === "Si" ? "block" : "none";
        });
    }

    // Auxiliares de Limpieza
    const auxLim = document.getElementById("aux_lim");
    if (auxLim) {
        auxLim.addEventListener("change", function() {
            document.getElementById("div_aux_cua").style.display = this.value === "Si" ? "block" : "none";
        });
    }

    // Anestesia local (Flujo en cadena corregido)
    const aneLoc = document.getElementById("ane_loc");
    const aneCom = document.getElementById("ane_com");
    
    if (aneLoc) {
        aneLoc.addEventListener("change", function() {
            if (this.value === "Si") {
                document.getElementById("div_ane_com").style.display = "block";
            } else {
                document.getElementById("div_ane_com").style.display = "none";
                document.getElementById("div_ane_des").style.display = "none";
                if (aneCom) aneCom.value = "No";
            }
        });
    }

    if (aneCom) {
        aneCom.addEventListener("change", function() {
            document.getElementById("div_ane_des").style.display = this.value === "Si" ? "block" : "none";
        });
    }

    // Remedio Casero
    const remCas = document.getElementById("rem_cas");
    if (remCas) {
        remCas.addEventListener("change", function() {
            document.getElementById("div_rem_des").style.display = this.value === "Si" ? "block" : "none";
        });
    }

    // Dolor al Masticar
    const dolMas = document.getElementById("dol_mas");
    if (dolMas) {
        dolMas.addEventListener("change", function() {
            document.getElementById("div_dol_des").style.display = this.value === "Si" ? "block" : "none";
        });
    }

    // Sangrado / Inflamación
    const sanInf = document.getElementById("san_inf");
    if (sanInf) {
        sanInf.addEventListener("change", function() {
            document.getElementById("div_san_des").style.display = this.value === "Si" ? "block" : "none";
        });
    }

    // Úlceras / Fuegos
    const utcBuc = document.getElementById("ulc_buc");
    if (utcBuc) {
        utcBuc.addEventListener("change", function() {
            document.getElementById("div_ulc_fre").style.display = this.value === "Si" ? "block" : "none";
        });
    }

    // Hábitos
    const habBoc = document.getElementById("hab_boc");
    if (habBoc) {
        habBoc.addEventListener("change", function() {
            document.getElementById("div_hab_cua").style.display = this.value === "Si" ? "block" : "none";
        });
    }

});
        
    </script>
</body>

</html>