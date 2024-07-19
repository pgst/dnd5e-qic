<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <i class="fa-solid fa-dice-d20 me-2 hover:animate-spin"></i>
            5ePL試験　第{{ $userExamination->question_num }}問目（{{ $itemsPerExam }}問出題中）
        </h2>
    </x-slot>

    <x-message :message="session('message')" />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p>{{ $userExamination->examination->question_txt }}</p>

                    <form method="POST"
                        action="{{ route('user-examination.update', $userExamination->id) }}"
                    >
                        @csrf
                        @method('patch')

                        @livewire('selector', ['userExamination' => $userExamination])
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
