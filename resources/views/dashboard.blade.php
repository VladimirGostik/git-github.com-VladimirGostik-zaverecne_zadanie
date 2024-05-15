<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-black leading-tight">
            {{ __('Dashboard for Managing Questions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Správa otázok -->
            <div class="bg-white white:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-black">
                    <h3 class="font-semibold text-lg">Manage Your Questions</h3>
                    <a href="{{ route('questions.create') }}" class="text-blue-500">Create New Question</a> 
                    <br>
                    <a href="" class="text-blue-500">View All Questions</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
