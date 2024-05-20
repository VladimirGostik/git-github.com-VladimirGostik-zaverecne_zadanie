<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $question->subject }} - Results</title>
    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet">
    <!-- Tailwind CSS Link -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .option-box {
            display: inline-block;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
            cursor: pointer;
            padding: 10px 20px;
            border: 1px solid #e2e8f0; /* Tailwind Gray 300 */
            border-radius: 8px;
            margin-bottom: 10px;
            text-align: center;
            position: relative;
        }

        .result-bar {
            height: 8px;
            background-color: #cbd5e1; /* Tailwind Gray 400 */
            border-radius: 6px;
            overflow: hidden;
            margin-top: 5px;
        }

        .result-bar-correct-fill {
            height: 100%;
            background-color: #16a34a; /* Tailwind Green 600 */
            border-radius: 4px;
        }
        
        .result-bar-incorrect-fill {
            height: 100%;
            background-color: #dc2626; /* Tailwind Red 600 */
            border-radius: 4px;
        }

        .correct-option {
            background-color: #c6f6d5; /* Tailwind Green 100 */
            border-color: #16a34a; /* Tailwind Green 600 */
        }

        .incorrect-option {
            background-color: #fed7d7; /* Tailwind Red 100 */
            border-color: #dc2626; /* Tailwind Red 500 */
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="flex items-center justify-center h-screen">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-2xl">
            <h1 class="text-2xl font-bold mb-4">{{ $question->question }}</h1>
            <h2 class="text-xl font-bold mb-4">Results</h2>

            <!-- Display results for open-ended questions -->
            @if ($question->type === 'open_ended')
                @if ($question->open_ended_display === 'list')
                    <ul class="list-disc list-inside">
                        @foreach ($answers as $answer)
                            <li>{{ $answer->answer }}</li>
                        @endforeach
                    </ul>
                    <button onclick="location.href='/'" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mt-4">
                        Back to Home
                    </button>
                @elseif ($question->open_ended_display === 'word_cloud')
                    <div class="flex flex-wrap">
                        @php
                            // Define the maximum font size
                            $maxFontSize = 48; // Maximum font size in pixels
                            $minFontSize = 14; // Minimum font size in pixels
                            
                            // Count the frequency of each answer
                            $answerCounts = $answers->countBy('answer');

                            // Find the maximum count
                            $maxCount = $answerCounts->max();

                            // Calculate the font size coefficient
                            $fontSizeCoefficient = $maxFontSize / $maxCount;
                        @endphp
                        @foreach ($answerCounts as $answer => $count)
                            @php
                                // Calculate the font size for each answer
                                $fontSize = max($minFontSize, $count * $fontSizeCoefficient);
                            @endphp
                            <span class="m-2" style="font-size: {{ $fontSize }}px;">{{ $answer }}</span>
                        @endforeach
                        
                    </div>
                    <button onclick="location.href='/'" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mt-4">
                        Back to Home
                    </button>
                @endif
            @endif

            <!-- Display results for multiple-choice questions -->
            @if ($question->type === 'multiple_choice')
                <div class="mt-4">
                    @foreach ($question->multipleChoiceAnswers as $answer)
                        <div class="option-box mb-2 
                                    @if($answer->is_correct) correct-option @else incorrect-option @endif">
                            <div class="option-text">{{ $answer->answer }}</div>
                            @php
                                $totalVotes = $question->multipleChoiceAnswers->sum('counter');
                                $percentage = $totalVotes ? ($answer->counter / $totalVotes) * 100 : 0;
                            @endphp
                            <div class="result-bar">
                                <div class="h-full 
                                    @if($answer->is_correct) result-bar-correct-fill @else result-bar-incorrect-fill @endif" style="width: {{ $percentage }}%;"></div>
                            </div>
                        </div>
                    @endforeach
                    <button onclick="location.href='/'" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mt-4">
                        Back to Home
                    </button>
                </div>
            @endif
        </div>
    </div>
</body>

</html>
