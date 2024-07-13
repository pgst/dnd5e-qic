<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <i class="fa-solid fa-dice-d20"></i>5EPL試験　第{{ $userExamination->question_num }}問目（{{ $itemsPerExam }}問出題中）
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

                        <div>
                            <p class="font-bold">
                                <span>あなたの選択：</span>
                                <span id="your_choice">{{ $userExamination->selected_answer }}</span>
                            </p>
                            <div class="text-right mt-1">
                                <x-secondary-button onclick="setSelectedAnswer('はい')">
                                    はい
                                </x-secondary-button>
                                <x-secondary-button onclick="setSelectedAnswer('いいえ')">
                                    いいえ
                                </x-secondary-button>
                            </div>
                        </div>

                        <div class="mt-6 text-center">
                            <input type="hidden" name="selected_answer" id="selected_answer" value="{{ $userExamination->selected_answer }}">
                            <x-primary-button>回答</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    function setSelectedAnswer(answer) {
        document.getElementById('selected_answer').value = answer;
        document.getElementById('your_choice').textContent = answer;
    }
    </script>
</x-app-layout>
