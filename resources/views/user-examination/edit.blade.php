<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            PL試験　第{{ $userExamination->question_num }}問目（{{ $itemsPerExam }}問出題中）
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

                        <div
                            x-data="{
                                selectedOption: '',
                                init() {
                                    const pageKey = 'selectedOption-' + '{{ $userExamination->question_num }}';
                                    this.selectedOption = localStorage.getItem(pageKey) || '';
                                },
                                updateSelectedOption(option) {
                                    const pageKey = 'selectedOption-' + '{{ $userExamination->question_num }}';
                                    localStorage.setItem(pageKey, option);
                                    this.selectedOption = option;
                                }
                            }"
                            x-init="init()"
                        >
                            <p class="font-bold">あなたの選択：<span x-text="selectedOption === 'yes' ? 'はい' : selectedOption === 'no' ? 'いいえ' : ''"></span></p>
                            <div class="text-right mt-1">
                                <x-secondary-button @click="updateSelectedOption('yes')">
                                    はい
                                </x-secondary-button>

                                <x-secondary-button @click="updateSelectedOption('no')">
                                    いいえ
                                </x-secondary-button>
                            </div>
                        </div>

                        <div class="mt-6 text-center"
                            x-data="{
                                checkLocalStorage() {
                                    let allSelected = true;
                                    for (let i = 1; i <= {{ $itemsPerExam }}; i++) {
                                        if (localStorage.getItem('selectedOption-' + i) === null) {
                                            allSelected = false;
                                            break;
                                        }
                                    }
                                    if (allSelected) {
                                        this.$refs.completedInput.value = 'true';
                                    }
                                }
                            }"
                            x-init="checkLocalStorage()"
                        >
                            <input type="hidden" name="completed" value="false" x-ref="completedInput">
                            <x-primary-button @click="checkLocalStorage()">回答</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
