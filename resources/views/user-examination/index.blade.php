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
                    <div class="mb-5 pb-5">
                        <p>{{ $userExam->examination->question_txt }}</p>

                        <div>
                            <p class="font-bold">
                                <span>あなたの選択：</span>
                                <span>{{ $userExam->selected_answer }}</span>
                                <span>　</span>
                                <a href="{{ route('user-examination.edit', $userExam->id); }}">
                                    <x-secondary-button>再検討</x-secondary-button>
                                </a>
                            </p>
                        </div>
                    </div>
                    @endforeach

                    <div class="mt-6 text-center">
                        <form method="POST" action="{{ route('user-examination.result'); }}">
                            @csrf

                            <span>これらの選択でよろしければ、提出ボタンをクリックしてください。</span>
                            <x-primary-button>提出</x-primary-button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
