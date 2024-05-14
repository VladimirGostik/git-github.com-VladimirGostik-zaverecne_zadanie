<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutorial Page</title>
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
                <a href="/" class="text-lg text-blue-600 hover:text-blue-700">Home</a>
            </div>
            <div>
                <!-- Authentication Links or other navigation links -->
                <a href="/login" class="text-blue-500 hover:text-blue-700 transition-colors px-3 py-2 rounded">Log
                    in</a>
                <a href="/register"
                    class="text-blue-500 hover:text-blue-700 transition-colors px-3 py-2 rounded">Register</a>
            </div>
        </div>
    </nav>
    <div class="flex items-center justify-center min-h-screen">
        <div class="text-center">
            
                <h1 class="text-4xl font-bold mb-4">{{__('tutorial.head')}}</h1>
                <p class="text-xl mb-8">{{__('tutorial.text')}}</p>
            
            <div class="text-center">
                <a href="/export-pdf"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-300 ease-in-out">
                    Export as PDF
                </a>
            </div>
        </div>

    </div>

</body>

</html>