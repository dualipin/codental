@extends('layouts.main')
@section('content')
    <!-- NAVBAR -->
    <nav class="navbar bg-base-100 shadow-lg sticky top-0 z-50">
        <div class="flex-1">
            <a href="#" class="btn btn-ghost text-xl text-primary font-display">
                <i class="fa-solid fa-tooth"></i> CoDentaL
            </a>
        </div>
        <div class="flex-none">
            <a href="{{ route('login') }}" class="btn btn-outline btn-primary">
                <i class="fa-solid fa-user-lock"></i> Iniciar Sesión
            </a>
        </div>
    </nav>

    <!-- CAROUSEL -->
    <div class="p-10">
        <div class="carousel w-full h-120 bg-base-200 rounded-box overflow-hidden">
            <div id="slide1" class="carousel-item w-full relative">
                <div class="hero" style="background-image: url('{{ asset('img/sonrisa.jpg') }}')">
                    <div class="hero-overlay"></div>
                    <div class="hero-content text-neutral-content text-center">
                        <div class="max-w-md">
                            <h1 class="mb-5 text-6xl font-bold font-display">Excelencia Dental</h1>
                            <p class="mb-5 text-lg">
                                Tu sonrisa es nuestra prioridad número uno.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="absolute left-5 right-5 top-1/2 flex -translate-y-1/2 transform justify-between">
                    <a href="#slide1" class="btn btn-circle btn-sm btn-primary">❮</a>
                    <a href="#slide2" class="btn btn-circle btn-sm btn-primary">❯</a>
                </div>
            </div>

            <div id="slide2" class="carousel-item w-full relative">
                <div class="hero" style="background-image: url('{{ asset('img/tecn.jpg') }}')">
                    <div class="hero-overlay bg-opacity-40"></div>
                    <div class="hero-content text-center text-neutral-content">
                        <div class="max-w-md">
                            <h1 class="mb-5 text-6xl font-bold font-display">
                                Tecnología de Punta
                            </h1>
                            <p class="mb-5 text-lg">
                                Equipos modernos para tratamientos sin dolor.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="absolute left-5 right-5 top-1/2 flex -translate-y-1/2 transform justify-between">
                    <a href="#slide1" class="btn btn-circle btn-sm btn-primary">❮</a>
                    <a href="#slide1" class="btn btn-circle btn-sm btn-primary">❯</a>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA AGENDAR CITA -->
    <section class="hero bg-secondary text-secondary-content py-20 ">
        <div class="hero-content text-center">
            <div class="max-w-xl">
                <h2 class="text-5xl font-bold mb-6 font-display">¿Listo para
                    transformar tu sonrisa?</h2>
                <p class="text-lg mb-8">Reserva tu espacio hoy mismo con nuestros especialistas.</p>
                <a href="{{ route('agenda.identificacion') }}" class="btn btn-lg btn-primary gap-2">
                    <i class="fa-solid fa-calendar-check"></i> Agendar Cita Ahora
                </a>
            </div>
        </div>
    </section>

    <!-- SECCIÓN QUIÉNES SOMOS -->
    <section class="py-20 px-4 bg-base-100">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-6xl font-bold mb-6 font-display">Quiénes Somos</h2>
                <div class="divider divider-primary w-24 mx-auto"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- MISIÓN -->
                <div class="card bg-base-200 shadow-lg hover:shadow-2xl transition-shadow">
                    <div class="card-body text-center">
                        <div class="mx-auto mb-4">
                            <div class="badge badge-primary badge-lg p-4">
                                <i class="bi bi-flag text-2xl text-primary-content"></i>
                            </div>
                        </div>
                        <h3 class="card-title justify-center text-2xl mb-4 text-primary">Misión</h3>
                        <p class="text-base-content text-opacity-80 text-sm leading-relaxed">Brindar servicios
                            odontológicos de alta calidad, enfocados en la prevención, diagnóstico y tratamiento de
                            la
                            salud bucodental, mediante técnicas actualizadas, atención personalizada y un equipo de
                            profesionales comprometido con el bienestar, la seguridad y la satisfacción de nuestros
                            pacientes.</p>
                    </div>
                </div>

                <!-- VISIÓN -->
                <div class="card bg-base-200 shadow-lg hover:shadow-2xl transition-shadow">
                    <div class="card-body text-center">
                        <div class="mx-auto mb-4">
                            <div class="badge badge-primary badge-lg p-4">
                                <i class="bi bi-eye text-2xl text-primary-content"></i>
                            </div>
                        </div>
                        <h3 class="card-title justify-center text-2xl mb-4 text-primary">Visión</h3>
                        <p class="text-base-content text-opacity-80 text-sm leading-relaxed">Ser un consultorio
                            dental
                            reconocido por su profesionalismo, innovación y excelencia en el servicio, destacándonos
                            como una referencia en el cuidado bucal, contribuyendo a mejorar la calidad de vida de
                            nuestros pacientes a través de sonrisas saludables y funcionales.</p>
                    </div>
                </div>

                <!-- VALORES -->
                <div class="card bg-base-200 shadow-lg hover:shadow-2xl transition-shadow">
                    <div class="card-body text-center">
                        <div class="mx-auto mb-4">
                            <div class="badge badge-primary badge-lg p-4">
                                <i class="bi bi-gem text-2xl text-primary-content"></i>
                            </div>
                        </div>
                        <h3 class="card-title justify-center text-2xl mb-4 text-primary">Valores</h3>
                        <p class="text-base-content text-opacity-80 text-sm leading-relaxed">El consultorio de
                            estudios
                            odontológicos se guía por valores centrados en la honestidad la responsabilidad y el
                            respeto, ofreciendo un trato humano y profesional a cada paciente; por la
                            responsabilidad en
                            el manejo seguro y confidencial de la información del paciente. Nos comprometemos con la
                            calidad, la prevención y la mejora continua, apoyándonos en el trabajo en equipo y el
                            uso
                            responsable de la tecnología para cuidar la salud bucal de nuestra comunidad.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer bg-neutral text-neutral-content py-8 text-center justify-center">
        <div class="w-full text-center">
            <p>&copy; {{ date('Y') }} CoDentaL. Todos los derechos reservados.</p>
        </div>
    </footer>
@endsection