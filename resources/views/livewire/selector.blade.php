<div>
    <div>
        <p class="font-bold">
            <span>あなたの選択：</span>
            <span>{{ $userExamination->selected_answer }}</span>
        </p>
        <div class="text-right mt-1">
            <x-secondary-button wire:click="select('はい')">はい</x-secondary-button>
            <x-secondary-button wire:click="select('いいえ')">いいえ</x-secondary-button>
        </div>
    </div>

    <div class="mt-6 text-center">
        <input type="hidden" name="selected_answer"
            value="{{ $userExamination->selected_answer }}"
        >
        <x-primary-button>回答</x-primary-button>
    </div>
</div>
