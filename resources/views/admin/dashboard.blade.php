<script defer src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script defer src="https://cdn.datatables.net/2.0.2/js/dataTables.js"></script>
<script defer src="https://cdn.datatables.net/2.0.2/js/dataTables.bootstrap5.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script> 
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
                                <th>Subject</th>
                                <th>Question</th>
                                <th>Type</th>
                                <th>Active</th>
                                <th>Created At</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($questions as $question) <!-- Loop through $allQuestions -->
                            <tr>
                                <td>{{ $creatorNames[$question->id] }}</td> <!-- Display creator's name -->
                                <td>{{ $question->subject }}</td>
                                <td>{{ $question->question }}</td>
                                <td>{{ $question->type === 'open_ended' ? 'Short answer' : 'Multiple choice' }}</td>
                                <td>{{ $question->active ? 'Yes' : 'No' }}</td>
                                <td>{{ date('Y-m-d', strtotime($question->created_at)) }}</td>
                                <td>
                                    <a href="{{ route('questions.edit', $question->id) }}" class="btn btn-primary">Edit</a>
                                </td>
                                <td>
                                    <form action="{{ route('questions.destroy', $question->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this question?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6 d-flex justify-content-center">
                        <button onclick="CreateQuestionPage()" class="py-2 px-4 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg shadow-md">Add Question</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
    $(document).ready(function() {
        new DataTable('#questions-table', {
            pageLength: 25,
            initComplete: function () {
                this.api()
                    .columns([1, 5])
                    .every(function () {
                        let column = this;

                        // Create select element
                        let select = document.createElement('select');
                        select.add(new Option(''));
                        column.footer().replaceChildren(select);

                        // Apply listener for user change in value
                        select.addEventListener('change', function () {
                            column
                                .search(select.value, {exact: true})
                                .draw();
                        });

                        // Add list of options
                        column
                            .data()
                            .unique()
                            .sort()
                            .each(function (d, j) {
                                select.add(new Option(d));
                            });
                    });
            }
        });
    });

    function CreateQuestionPage() {
        window.location.href = "{{ route('questions.create') }}";
    }
</script>
