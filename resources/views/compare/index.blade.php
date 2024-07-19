<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <i class="fa-solid fa-dice-d20 me-2 hover:animate-spin"></i>
            D&D第5版PL試験答え合わせ
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mt-4">{{ $userExams->links() }}</div>

                    <div class="w-full flex justify-center mb-3 font-bold">
                        <i class="fa-regular fa-bell me-2"></i>
                        {{ $userExams->first()->challenge_num }}回目の受験結果
                        <i class="fa-regular fa-bell ms-2"></i>
                    </div>

                    @foreach ($userExams as $index => $userExam)
                    <div class="mb-5 pb-5">
                        <p>
                            第{{ $userExam->question_num }}問
                            @if ($userExam->selected_answer == $userExam->examination->correct_answer)
                            <i class="ms-2 fa-solid fa-check"></i>
                            @endif
                        </p>
                        <p>{{ $userExam->examination->question_txt }}</p>

                        <div>
                            @if ($userExam->selected_answer == $userExam->examination->correct_answer)
                            <p class="font-bold">
                            @else
                            <p class="font-bold text-red-500">
                            @endif
                                <span>あなたの選択：</span>
                                <span>{{ $userExam->selected_answer }}</span>
                                <span>　</span>
                                <span>正しい選択：</span>
                                <span>{{ $userExam->examination->correct_answer }}</span>
                            </p>
                        </div>
                    </div>
                    @endforeach

                    <div class="mb-4">{{ $userExams->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
