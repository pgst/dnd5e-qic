<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            PL試験　提出確認
        </h2>
    </x-slot>

    <x-message :message="session('message')" />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @foreach ($userExams as $index => $userExam)
                    <p>{{ $userExam->examination->question_txt }}</p>

                    <div class="mb-6 pb-6">
                        <p class="font-bold">あなたの選択：<span></span></p>
                    </div>
                    @endforeach

                    <div class="mt-6 text-center">
                        <form method="POST" action="">
                            @csrf
                            
                            <input type="hidden" name="exam" value="{{ $itemsPerExam }}">
                            <x-secondary-button>戻る</x-secondary-button>
                            <x-primary-button>提出</x-primary-button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
