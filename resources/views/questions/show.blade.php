<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $question->subject }}</title>
    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet">
    <!-- Tailwind CSS Link -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .selected-option {
            background-color: #4299e1; /* Tailwind Blue 500 */
            color: white;
        }

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

        .option-box:hover {
            background-color: #edf2f7; /* Tailwind Gray 100 */
        }

        .option-box input[type="checkbox"],
        .option-box input[type="radio"] {
            display: none;
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

        .selected-option.correct-option {
            background-color: #68d391; /* Tailwind Green 400 */
            border-color: #16a34a; /* Tailwind Green 500 */
        }

        .selected-option.incorrect-option {
            background-color: #fc8181; /* Tailwind Red 400 */
            border-color: #dc2626; /* Tailwind Red 500 */
        }

        .checkmark,
        .crossmark {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            font-size: 1.5em;
            font-weight: bold;
        }

        .checkmark {
            color: #16a34a; /* Tailwind Green 500 */
        }

        .crossmark {
            color: #dc2626; /* Tailwind Red 500 */
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="flex items-center justify-center h-screen">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-2xl">
            <h1 class="text-2xl font-bold mb-4">{{ $question->question }}</h1>
            
            @if ($question->type === 'open_ended')
                <form id="openEndedForm" action="{{ route('questions.storeFreeResponseAnswer') }}" method="POST">
                    @csrf
                    <input type="hidden" name="question_id" value="{{ $question->id }}">
                    <div class="mb-4">
                        <textarea name="answer" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Write your short answer" required></textarea>
                    </div>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Submit
                    </button>
                </form>
            @elseif ($question->type === 'multiple_choice')
                <form id="multipleChoiceForm" action="{{ route('questions.storeMultipleChoiceAnswer') }}" method="POST">
                    @csrf
                    <input type="hidden" name="question_id" value="{{ $question->id }}">
                    <div id="optionsContainer">
                        @foreach ($multipleChoiceAnswers as $answer)
                            <label class="option-box">
                                <input type="{{ $question->multiple_answer ? 'checkbox' : 'radio' }}" name="selected_options[]" value="{{ $answer->id }}">
                                {{ $answer->answer }}
                            </label>
                        @endforeach
                    </div>
                    <button id="submitButton" type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Submit
                    </button>
                </form>
                <div id="resultsContainer" class="mt-6 hidden">
                    <h2 class="text-xl font-bold mb-4">Results</h2>
                    <div id="results"></div>
                    <button onclick="location.href='/'" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mt-4">
                        Back to Home
                    </button>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('multipleChoiceForm');
    const inputs = form.querySelectorAll('input[type="checkbox"], input[type="radio"]');
    const multipleAnswer = {{ $question->multiple_answer ? 'true' : 'false' }};
    const optionsContainer = document.getElementById('optionsContainer');
    const resultsContainer = document.getElementById('resultsContainer');
    const resultsDiv = document.getElementById('results');
    const submitButton = document.getElementById('submitButton');

    inputs.forEach(input => {
        input.addEventListener('change', function () {
            const label = this.parentElement;

            if (multipleAnswer) {
                if (this.checked) {
                    label.classList.toggle('selected-option');
                } else {
                    label.classList.remove('selected-option');
                }
            } else {
                inputs.forEach(i => i.parentElement.classList.remove('selected-option'));
                if (this.checked) {
                    label.classList.add('selected-option');
                }
            }
        });
    });

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const selectedOptions = [];
        inputs.forEach(input => {
            if (input.checked) {
                selectedOptions.push(parseInt(input.value));
            }
        });

        const formData = new FormData(form);
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
        .then(response => response.json())
        .then(data => {
            optionsContainer.classList.add('hidden');
            resultsContainer.classList.remove('hidden');
            submitButton.classList.add('hidden');

            let totalVotes = data.totalVotes;
            let userSelections = data.userSelections;

            data.answers.forEach(answer => {
                const percentage = totalVotes ? (answer.counter / totalVotes) * 100 : 0;
                const isSelected = userSelections.includes(String(answer.id));
                const isCorrect = answer.is_correct;

                const optionDiv = document.createElement('div');
                optionDiv.classList.add('option-box', 'mb-2', 'relative');
                // console.log(isSelected, isCorrect);
                if (isSelected) {
                    optionDiv.classList.add('selected-option');
                    if (isCorrect) {
                        const checkmark = document.createElement('span');
                        checkmark.classList.add('checkmark');
                        checkmark.textContent = 'Well done ✓';
                        optionDiv.appendChild(checkmark);
                    } else {
                        const crossmark = document.createElement('span');
                        crossmark.classList.add('crossmark');
                        crossmark.textContent = 'Wrong ✗';
                        optionDiv.appendChild(crossmark);
                    }
                }
                if (isCorrect) {
                    optionDiv.classList.add('correct-option');
                } else {
                    optionDiv.classList.add('incorrect-option');
                }

                const textSpan = document.createElement('span');
                textSpan.textContent = answer.answer;

                const percentageDiv = document.createElement('div');
                percentageDiv.classList.add('result-bar');
                const percentageFill = document.createElement('div');

                if (isCorrect) {
                    percentageFill.classList.add('result-bar-correct-fill');
                } else {
                    percentageFill.classList.add('result-bar-incorrect-fill');
                }

                // percentageFill.classList.add('result-bar-fill');
                percentageFill.style.width = `${percentage}%`;
                percentageDiv.appendChild(percentageFill);

                optionDiv.appendChild(textSpan);
                optionDiv.appendChild(percentageDiv);
                resultsDiv.appendChild(optionDiv);
            });
        })
        .catch(error => console.error('Error:', error));
    });
});

    </script>
</body>

</html>

