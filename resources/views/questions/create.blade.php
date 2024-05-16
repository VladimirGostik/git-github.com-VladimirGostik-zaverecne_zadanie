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


    <script>
    function addOption() {
    const optionsContainer = document.getElementById('multipleChoiceOptions');
    const optionDiv = document.createElement('div');
    optionDiv.className = 'mb-2 flex items-center';

    const newOption = document.createElement('input');
    newOption.type = 'text';
    newOption.name = 'options[' + optionsContainer.children.length + ']'; // Generate name with sequential index
    newOption.placeholder = 'Option ' + (optionsContainer.children.length);
    newOption.className = 'option-input shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline';

    const newCheckbox = document.createElement('input');
    newCheckbox.type = 'checkbox';
    newCheckbox.name = 'correct_options[' + optionsContainer.children.length + ']'; // Generate name with sequential index
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
    });

    function toggleOptionsField() {
        const questionType = document.getElementById('questionType').value;
        const optionsContainer = document.getElementById('multipleChoiceOptions');

        if (questionType === 'multiple_choice') {
            optionsContainer.style.display = 'block';
        } else {
            optionsContainer.style.display = 'none';
        }
    }
</script>


</head>

<body class="bg-gray-100">
    <nav class="bg-white px-6 py-4 shadow">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <a href="/" class="text-lg font-semibold text-blue-600">Pseudo Slido</a>
                <a href="{{ route('tutorial') }}" class="text-lg text-blue-600 hover:text-blue-700">Tutorial</a>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto bg-white p-6 mt-8 rounded-lg shadow">
        <h1 class="text-xl font-semibold mb-8">Creating a New Question</h1>
        <form action="{{ route('questions.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="questionText" class="block text-gray-700 text-sm font-bold mb-2">Question Text:</label>
                <input type="text" id="questionText" name="questionText"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    required>
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
                <select id="questionType" name="questionType" onchange="toggleOptionsField()"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <option value="">Select Question Type</option>
                    <option value="multiple_choice">Multiple Choice</option>
                    <option value="open_ended">Open-Ended</option>
                </select>
            </div>
            <div id="multipleChoiceOptions" class="mb-4" style="display: none;">
    <label class="block text-gray-700 text-sm font-bold mb-2">Choices:</label>
    <!-- Initialize with three inputs -->
    <div class="mb-2 flex items-center">
        <input type="text" placeholder="Option 1" name="options[]" class="option-input shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        <input type="checkbox" name="correct_options[]" class="ml-2">
        <label class="ml-1">Correct</label>
        <button type="button" class="delete-option ml-2 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Delete</button>
    </div>
    <div class="mb-2 flex items-center">
        <input type="text" placeholder="Option 2" name="options[]" class="option-input shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        <input type="checkbox" name="correct_options[]" class="ml-2">
        <label class="ml-1">Correct</label>
        <button type="button" class="delete-option ml-2 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Delete</button>
    </div>
    <div class="mb-2 flex items-center">
        <input type="text" placeholder="Option 3" name="options[]" class="option-input shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        <input type="checkbox" name="correct_options[]" class="ml-2">
        <label class="ml-1">Correct</label>
        <button type="button" class="delete-option ml-2 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Delete</button>
    </div>
</div>
<button type="button" onclick="addOption()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded focus:outline-none focus:shadow-outline">Add another option</button>
<div class="flex items-center justify-between mt-4">
    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Create Question</button>
</div>

        </form>
    </div>

</body>

</html>