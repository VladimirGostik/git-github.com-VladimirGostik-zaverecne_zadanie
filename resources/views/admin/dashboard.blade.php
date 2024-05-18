<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
        <table id="questions-table" class="table table-striped" style="width:100%">
                        <thead class="table-dark">
                            <tr>
                                <th>Creator</th>
                                <th>Question</th>
                                <th>Type</th>
                                <th>Active</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($questions as $question) <!-- Loop through $allQuestions -->
                            <tr>
                                <td>{{ $creatorNames[$question->id] }}</td> <!-- Display creator's name -->
                                <td>{{ $question->question }}</td>
                                <td>{{ $question->type === 'open_ended' ? 'Short answer' : 'Multiple choice' }}</td>
                                <td>{{ $question->active ? 'Yes' : 'No' }}</td>
                                <td>
                                    <a href="{{ route('questions.edit', $question->id) }}" class="btn btn-primary">Edit</a>
                                </td>
                                <td>
                                    <form action="{{ route('questions.destroy', $question->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6 d-flex justify-content-center">  <button onclick="CreateQuestionPage()" class="py-2 px-4 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg shadow-md">Add Question</button>
                    </div>
                </div>
                
        </div>
    </div>
</x-app-layout>
<script>
    $(document).ready(function() {
        $('#questions-table').DataTable();
    });
    function CreateQuestionPage() {
        window.location.href = "{{ route('questions.create') }}";
    }
</script>
