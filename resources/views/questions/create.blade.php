<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Creating a new question') }}
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
            newOption.name = `options[${optionIndex}]`;
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

        document.addEventListener('DOMContentLoaded', () => {
            const initialOptions = document.querySelectorAll('.delete-option');
            initialOptions.forEach(button => {
                button.addEventListener('click', function () {
                    const parentDiv = this.parentNode;
                    parentDiv.parentNode.removeChild(parentDiv);
                });
            });
            toggleOptionsField();
        });

        function toggleOptionsField() {
            const questionType = document.getElementById('questionType').value;
            const addOptionButton = document.getElementById('addOptionButton');
            const multipleChoiceOptions = document.getElementById('multipleChoiceOptions');
            const multipleChoiceSettings = document.getElementById('multipleChoiceSettings');
            const openEndedSettings = document.getElementById('openEndedSettings');

            if (questionType === 'multiple_choice') {
                multipleChoiceOptions.style.display = 'block';
                multipleChoiceSettings.style.display = 'block';
                openEndedSettings.style.display = 'none';
                addOptionButton.style.display = 'block';
            } else if (questionType === 'open_ended') {
                multipleChoiceOptions.style.display = 'none';
                multipleChoiceSettings.style.display = 'none';
                openEndedSettings.style.display = 'block';
                addOptionButton.style.display = 'none';
            } else {
                multipleChoiceOptions.style.display = 'none';
                multipleChoiceSettings.style.display = 'none';
                openEndedSettings.style.display = 'none';
                addOptionButton.style.display = 'none';
            }
        }
    </script>

    <div class="max-w-4xl mx-auto bg-white p-6 mt-8 rounded-lg shadow">
        <form action="{{ route('questions.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="questionText" class="block text-gray-700 text-sm font-bold mb-2">Question Text:</label>
                <input type="text" id="questionText" name="questionText" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="mb-4">
                <label for="subject" class="block text-gray-700 text-sm font-bold mb-2">Subject:</label>
                <input type="text" id="subject" name="subject" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="flex mb-4 -mx-2">
                <div class="w-1/2 px-2">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Start Date and Time:</label>
                    <input required="required" type="date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" name="start_date">
                    <input required="required" type="time" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="start_time">
                </div>
                <div class="w-1/2 px-2">
                    <label class="block text-gray-700 text-sm font-bold mb-2">End Date and Time:</label>
                    <input required="required" type="date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" name="end_date">
                    <input required="required" type="time" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="end_time">
                </div>
            </div>
            <div class="mb-4">
                <label for="questionType" class="block text-gray-700 text-sm font-bold mb-2">Question Type:</label>
                <select id="questionType" name="questionType" onchange="toggleOptionsField()" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <option value="">Select Question Type</option>
                    <option value="multiple_choice">Multiple Choice</option>
                    <option value="open_ended">Open-Ended</option>
                </select>
            </div>          
            <!-- Multiple Choice Settings -->
            <div id="multipleChoiceSettings" class="mb-4" style="display: none;">
                <label for="multipleChoiceSelection" class="block text-gray-700 text-sm font-bold mb-2">Allow Multiple Selections:</label>
                <select id="multipleChoiceSelection" name="multipleChoiceSelection" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="single">Single Answer</option>
                    <option value="multiple">Multiple Answers</option>
                </select>
            </div>
            <!-- Open-Ended Settings -->
            <div id="openEndedSettings" class="mb-4" style="display: none;">
                <label for="openEndedDisplay" class="block text-gray-700 text-sm font-bold mb-2">Display As:</label>
                <select id="openEndedDisplay" name="openEndedDisplay" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="list">List</option>
                    <option value="word_cloud">Word Cloud</option>
                </select>
            </div>
            <!-- Multiple Choice Options -->
            <div id="multipleChoiceOptions" class="mb-4" style="display: none;">
                <label class="block text-gray-700 text-sm font-bold mb-2">Choices:</label>
                <div class="mb-2 flex items-center">
                    <input type="text" placeholder="Option 1" name="options[0]" class="option-input shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <input type="checkbox" name="correct_options[0]" class="ml-2">
                    <label class="ml-1">Correct</label>
                    <button type="button" class="delete-option ml-2 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Delete</button>
                </div>
                <div class="mb-2 flex items-center">
                    <input type="text" placeholder="Option 2" name="options[1]" class="option-input shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <input type="checkbox" name="correct_options[1]" class="ml-2">
                    <label class="ml-1">Correct</label>
                    <button type="button" class="delete-option ml-2 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Delete</button>
                </div>
                <div class="mb-2 flex items-center">
                    <input type="text" placeholder="Option 3" name="options[2]" class="option-input shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <input type="checkbox" name="correct_options[2]" class="ml-2">
                    <label class="ml-1">Correct</label>
                    <button type="button" class="delete-option ml-2 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Delete</button>
                </div>
            </div>
            <button id="addOptionButton" type="button" onclick="addOption()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded focus:outline-none focus:shadow-outline">Add another option</button>
            <div class="flex items-center justify-between mt-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Create Question</button>
            </div>
        </form>
    </div>
</x-app-layout>
