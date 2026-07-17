<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>
        @yield('document-title', 'CoDental')
    </title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            font-size: 11px;
            line-height: 1.25;
            color: #000;
            padding: 1.25in .5in .75in;
        }

        /* HEADER FIJO */
        header {
            top: 0;
            left: 0;
            right: 0;
            position: fixed;
            {{--color: {$institutionalColor};--}}
             padding-top: .27in;
            padding-bottom: 5px;
            margin: .2in .5in .2in;
            border-bottom: 1px solid{{--{$institutionalColor}--}};
        }

        header .logo {
            display: inline;
            position: absolute;
            width: 80px;
            height: 80px;
            left: .1in;
            top: -0.1in;
            border-radius: 99999px;
        }

        header .header-title {
            display: inline;
            text-align: right;
            padding-bottom: 10px;
        }

        header .header-title h1 {
            font-size: 2em;
            font-weight: bold;
            font-family: "Times New Roman", Times, serif;
            margin: 0;
        }

        header .header-title h2 {
            font-size: 1.5em;
            font-weight: normal;
            margin: 0;
        }

        /* FOOTER FIJO */
        footer {
            position: fixed;
            bottom: .25in;
            /* Alineado con el padding */
            left: .5in;
            right: .5in;
            border-top: 1px solid{{--{$institutionalColor}--}};
            padding-top: 5px;
            font-size: 0.75em;
            text-align: center;
            /*color: {$institutionalColor};*/
        }

        .page_number:before {
            position: absolute;
            right: 0;
            content: "Foja " counter(page);
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.05;
            width: 80%;
            height: auto;
            z-index: -1;
        }
    </style>

    @yield('document-styles')
</head>

<body>
{{--<img src="{$documentLogo|noescape}" class="watermark" alt="watermark">--}}
<header>
    {{--    <img src="{$documentLogo|noescape}" class="logo" alt="Logo">--}}
    <div class="header-title">
        <h1>CoDental</h1>
    </div>
</header>

<footer>
    <p><span class="page_number"></span></p>
    <p>CoDental - Clínica Dental Especializada</p>
</footer>

<main class="container">
    @yield('document-content')
</main>


</body>

</html>