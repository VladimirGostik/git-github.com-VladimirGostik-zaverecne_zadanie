<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Question</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editing Question') }}
        </h2>
    </x-slot>

    <script>
    function addOption() {
        const optionsContainer = document.getElementById('multipleChoiceOptions');
        const optionIndex = optionsContainer.children.length;
        const optionDiv = document.createElement('div');
        optionDiv.className = 'mb-2 flex items-center';

        const newOption = document.createElement('input');
        newOption.type = 'text';
        newOption.name = `options[${optionIndex - 1}]`;
        newOption.placeholder = `Option ${optionIndex}`;
        newOption.className = 'option-input shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline';

        const newCheckbox = document.createElement('input');
        newCheckbox.type = 'checkbox';
        newCheckbox.name = `correct_options[${optionIndex}]`;
        newCheckbox.className = 'ml-2';

        const newLabel = document.createElement('label');
        newLabel.textContent = 'Correct';
        newLabel.className = 'ml-1';

        const deleteButton = document.createElement('button');
        deleteButton.type = 'button';
        deleteButton.textContent = 'Delete';
        deleteButton.className = 'delete-option ml-2 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline';
        deleteButton.addEventListener('click', function () {
            optionsContainer.removeChild(optionDiv);
        });

        optionDiv.appendChild(newOption);
        optionDiv.appendChild(newCheckbox);
        optionDiv.appendChild(newLabel);
        optionDiv.appendChild(deleteButton);
        optionsContainer.appendChild(optionDiv);
    }

    function generateEmptyOptions() {
        const optionsContainer = document.getElementById('multipleChoiceOptions');
        const currentOptions = optionsContainer.querySelectorAll('.option-input');

        // Only generate empty options if there are no options displayed
        if (currentOptions.length === 0) {
            for (let i = 0; i < 3; i++) { // Generate three empty option fields
                addOption();
            }
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const initialOptions = document.querySelectorAll('.delete-option');
        initialOptions.forEach(button => {
            button.addEventListener('click', function () {
                const parentDiv = this.parentNode;
                parentDiv.parentNode.removeChild(parentDiv);
            });
        });

        toggleOptionsField(); // Initial call to show/hide options based on the current type
    });

    function toggleOptionsField() {
        const questionType = document.getElementById('type').value;
        const optionsContainer = document.getElementById('multipleChoiceOptions');
        const addOptionButton = document.getElementById('addOptionButton');
        const multipleChoiceSettings = document.getElementById('multipleChoiceSettings');
        const openEndedSettings = document.getElementById('openEndedSettings');

        if (questionType === 'multiple_choice') {
            optionsContainer.style.display = 'block';
            addOptionButton.style.display = 'inline-block';
            multipleChoiceSettings.style.display = 'block';
            openEndedSettings.style.display = 'none';
            generateEmptyOptions(); // Automatically generate empty options if none are displayed
        } else {
            optionsContainer.style.display = 'none';
            addOptionButton.style.display = 'none';
            multipleChoiceSettings.style.display = 'none';
            openEndedSettings.style.display = 'block';
        }
    }
</script>

    <div class="max-w-4xl mx-auto bg-white p-6 mt-8 rounded-lg shadow">
        <form action="{{ route('questions.update', $question->id) }}" method="POST">
            @csrf
            @method('PATCH')
             <!-- Select User -->
            @if(Auth::user()->isAdmin())
            <div class="mb-4">
                <label for="user" class="block text-gray-700 text-sm font-bold mb-2">Select Publisher:</label>
                <select id="user" name="user" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ old('user', $question->creator_id) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            @else
            <input type="hidden" name="user" value="{{ Auth::id() }}">
            @endif
            <div class="mb-4">
                <label for="question" class="block text-gray-700 text-sm font-bold mb-2">Question Text:</label>
                <input type="text" id="question" name="question" value="{{ old('question', $question->question) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="mb-4">
                <label for="subject" class="block text-gray-700 text-sm font-bold mb-2">Subject:</label>
                <input type="text" id="subject" name="subject" value="{{ old('subject', $question->subject) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="flex mb-4 -mx-2">
                <div class="w-1/2 px-2">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Start Date and Time:</label>
                    <input type="date" value="{{ old('start_date', \Carbon\Carbon::parse($question->startdate)->format('Y-m-d')) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" name="start_date" required>
                    <input type="time" value="{{ old('start_time', \Carbon\Carbon::parse($question->starttime)->format('H:i')) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="start_time" required>
                </div>
                <div class="w-1/2 px-2">
                    <label class="block text-gray-700 text-sm font-bold mb-2">End Date and Time:</label>
                    <input type="date" value="{{ old('end_date', \Carbon\Carbon::parse($question->enddate)->format('Y-m-d')) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" name="end_date" required>
                    <input type="time" value="{{ old('end_time', \Carbon\Carbon::parse($question->endtime)->format('H:i')) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="end_time" required>
                </div>
            </div>
            <div class="mb-4">
                <label for="type" class="block text-gray-700 text-sm font-bold mb-2">Question Type:</label>
                <select id="type" name="type" onchange="toggleOptionsField()" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <option value="">Select Question Type</option>
                    <option value="multiple_choice" {{ old('type', $question->type) == 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                    <option value="open_ended" {{ old('type', $question->type) == 'open_ended' ? 'selected' : '' }}>Open-Ended</option>
                </select>
            </div>
            <div id="multipleChoiceSettings" class="mb-4" value="{{ old('type', $question->type) == 'multiple_choice' ? 'display: block;' : 'display: none;' }}">
                <label for="multipleChoiceSelection" class="block text-gray-700 text-sm font-bold mb-2">Multiple Choice Selection:</label>
                <select id="multipleChoiceSelection" name="multipleChoiceSelection" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="single" {{ old('multipleChoiceSelection', $question->multiple_answer ? 'multiple' : 'single') == 'single' ? 'selected' : '' }}>Single</option>
                    <option value="multiple" {{ old('multipleChoiceSelection', $question->multiple_answer ? 'multiple' : 'single') == 'multiple' ? 'selected' : '' }}>Multiple</option>
                </select>
            </div>
            <div id="openEndedSettings" class="mb-4" value="{{ old('type', $question->type) == 'open_ended' ? 'display: block;' : 'display: none;' }}">
                <label for="openEndedDisplay" class="block text-gray-700 text-sm font-bold mb-2">Open-Ended Display:</label>
                <select id="openEndedDisplay" name="openEndedDisplay" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="list" {{ old('openEndedDisplay', $question->open_ended_display) == 'list' ? 'selected' : '' }}>List</option>
                    <option value="word_cloud" {{ old('openEndedDisplay', $question->open_ended_display) == 'word_cloud' ? 'selected' : '' }}>Word Cloud</option>
                </select>
            </div>
            <div id="multipleChoiceOptions" class="mb-4" value="{{ old('type', $question->type) == 'multiple_choice' ? 'display: block;' : 'display: none;' }}">
                <label class="block text-gray-700 text-sm font-bold mb-2">Choices:</label>
                @foreach ($multipleChoiceAnswers as $index => $answer)
                    <div class="mb-2 flex items-center">
                        <input type="text" name="options[{{ $index }}]" value="{{ $answer->answer }}" placeholder="Option {{ $index + 1 }}" class="option-input shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <input type="checkbox" name="correct_options[{{ $index }}]" class="ml-2" {{ $answer->is_correct ? 'checked' : '' }}>
                        <label class="ml-1">Correct</label>
                        <button type="button" class="delete-option ml-2 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Delete</button>
                    </div>
                @endforeach
            </div>
            <button id="addOptionButton" type="button" onclick="addOption()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded focus:outline-none focus:shadow-outline">Add another option</button>
            <div class="flex items-center justify-between mt-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Update Question</button>
            </div>
        </form>
    </div>
</x-app-layout>
</body>
</html>
