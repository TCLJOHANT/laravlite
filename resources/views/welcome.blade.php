<!DOCTYPE html>
<html lang="en" class="h-full bg-white dark:bg-gray-900" x-data="{ darkMode: false }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Larav Lite</title>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        body {
            margin: 0;
            font-family: "Instrument Sans", sans-serif;
            background-color: #ffffff;
            color: #111827;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            flex-direction: column;
            text-align: center;
            transition: background-color 0.3s, color 0.3s;
        }

        body.dark {
            background-color: #1f2937;
            color: #f9fafb;
        }

        h1 {
            font-size: 3rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        p {
            font-size: 1.25rem;
            color: #6b7280;
        }

        .button {
            margin-top: 1.5rem;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            font-weight: 500;
            color: #fff;
            background-color: #6366f1;
            border-radius: 0.5rem;
            text-decoration: none;
            transition: background-color 0.2s;
        }

        .button:hover {
            background-color: #4f46e5;
        }

        body.dark .button {
            background-color: #818cf8;
        }

        body.dark .button:hover {
            background-color: #6366f1;
        }

        footer {
            margin-top: 3rem;
            font-size: 0.875rem;
            color: #9ca3af;
        }

        body.dark footer {
            color: #d1d5db;
        }

        .toggle {
            position: fixed;
            top: 1rem;
            right: 1rem;
            background-color: #e5e7eb;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            cursor: pointer;
            font-weight: 500;
        }

        body.dark .toggle {
            background-color: #374151;
            color: #f9fafb;
        }
    </style>
</head>

<body :class="{ 'dark': darkMode }">

    <div class="toggle" @click="darkMode = !darkMode">
        <span x-text="darkMode ? 'Modo Claro' : 'Modo Oscuro'"></span>
    </div>

    <h1 x-text="'LaravLite'"></h1>
    <p x-text="'Bienvenido al microframework ligero y modular '"></p>

    <a href="https://laravel.com/docs/12.x" class="button">Ver documentación</a>

    <footer>
        &copy; {{ date('Y') }} LaravLite. Todos los derechos reservados.</span>.
    </footer>

</body>

</html>
