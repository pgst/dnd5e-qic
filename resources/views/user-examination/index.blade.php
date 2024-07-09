<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            PL試験　第{{ $page }}問目
        </h2>
    </x-slot>

    <x-message :message="session('message')" />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @foreach ($userExams as $index => $userExam)
                    <p>{{ $userExam->examination->question_txt }}</p>
                    
                    <div
                        x-data="{ selectedOption: '' }"
                        x-init="() => {
                            const pageKey = 'selectedOption-' + '{{ $page }}';  // 現在のページ番号をキーに追加
                            selectedOption = localStorage.getItem(pageKey) || '';
                            if (selectedOption === 'yes') {
                                $refs.yesButton.focus();
                            } else if (selectedOption === 'no') {
                                $refs.noButton.focus();
                            }
                        }"
                    >
                        <div class="text-right mt-1">
                            <x-secondary-button x-ref="yesButton"
                                @click="localStorage.setItem('selectedOption-' + '{{ $page }}', 'yes');
                                selectedOption = 'yes';"
                            >はい</x-secondary-button>
                            
                            <x-secondary-button x-ref="noButton"
                                @click="localStorage.setItem('selectedOption-' + '{{ $page }}', 'no');
                                selectedOption = 'no';"
                            >いいえ</x-secondary-button>
                        </div>
                    </div>
                    @endforeach

                    <div class="mt-6">{{ $userExams->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
