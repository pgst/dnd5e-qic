<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            5EPL試験　合否結果
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="mb-3">採点の結果…</h2>
                    <p>{{ $name }}さんは<span class="text-danger fw-bold">{{ $score }}</span>問の正解でした。</p>
                    @if ($result == '合格')
                    <p>おめでとうございます！合格です！！</p>
                    @else
                    <p>残念ながら不合格です…</p>
                    @endif
                </div>

                <div class="mb-6 text-center">
                    <a href="{{ route('user-examination.create'); }}">
                        <x-primary-button>完了</x-primary-button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
