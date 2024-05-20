<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pseudo Slido</title>
    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet">
    <!-- Tailwind CSS Link -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <nav class="bg-white px-6 py-4 shadow">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="text-lg font-semibold text-blue-600">Pseudo Slido</div>
                <a href="{{ route('tutorial') }}" class="text-lg text-blue-600 hover:text-blue-700">Tutorial</a>
            </div>
            <div>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="text-blue-500 hover:text-blue-700 transition-colors px-3 py-2 rounded">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}"
                            class="text-blue-500 hover:text-blue-700 transition-colors px-3 py-2 rounded">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="text-blue-500 hover:text-blue-700 transition-colors px-3 py-2 rounded">Register</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>
    <div class="flex items-center justify-center h-screen">
        <div class="text-center">
            <h1 class="text-4xl font-bold mb-4">Pseudo Slido</h1>
            <p>Welcome to your interactive Q&A platform.</p>

            <!-- Form for entering the question code -->
            <form id="codeForm" class="mt-8" onsubmit="redirectToQuestion(event)">
                @csrf
                <div class="mb-4">
                    <input type="text" name="code" id="code" placeholder="Enter Question Code" 
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Submit
                </button>
            </form>
        </div>
    </div>

    <script>
        function redirectToQuestion(event) {
            event.preventDefault();
            const code = document.getElementById('code').value;
            window.location.href = `/${code}`;
        }
    </script>
</body>

</html>
