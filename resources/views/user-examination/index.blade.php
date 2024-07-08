<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            PL試験　第{{ $page }}問目
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @foreach ($userExams as $userExam)
                    <p>{{ $userExam->examination->question_txt }}</p>
                    <div class="text-right mt-1">
                        <x-secondary-button>はい</x-secondary-button>
                        <x-secondary-button>いいえ</x-secondary-button>
                    </div>
                    @endforeach

                    <div class="mt-6">{{ $userExams->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
