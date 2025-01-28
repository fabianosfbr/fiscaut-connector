<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="https://cdn.tailwindcss.com"></script>

    <title>Fiscaut Connector</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=libre-barcode-39-text:400&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <style>
        /* Estilos gerais */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Libre Barcode 39 Text', display;
            background-color: #2D2D2D;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Estilos para "Z2Pay" */
        .title {
            font-size: 5rem;
            color: #FFFFFF;
            letter-spacing: 2px;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
    </style>
</head>

<body class="antialiased">
    <div class="title">Fiscaut-Connector</div>
</body>

</html>
