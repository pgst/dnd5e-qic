<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('D&D第5版PL試験') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p>　プレイヤーとしてのルール理解度を確認するため、「<a class="link text-blue-500 hover:underline" target="_blank" href="https://wit-awscms-witweb.s3-ap-northeast-1.amazonaws.com/hjcardgamer/5eRPG_Core_20240315.pdf">フィフスエディションRPG</a>」をもとに、「はい」or「いいえ」で回答できる問題を用意しました。</p>
                    <p>　現在のところ、問題文は全{{ $count }}問です。その中から{{ $itemsPerExam }}問がランダムに出題されます。{{ $passingScore }}問以上の正解で合格です。</p>
                    <div class="text-right">
                        <form method="post" action="{{ route('user-examination.store') }}">
                            @csrf
                            
                            <input type="hidden" name="exam" value="{{ $itemsPerExam }}">
                            <input type="hidden" name="score" value="{{ $passingScore }}">
                            <x-primary-button>開始</x-primary-button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
