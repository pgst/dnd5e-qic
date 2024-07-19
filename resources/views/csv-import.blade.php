

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <i class="fa-solid fa-dice-d20 me-2 hover:animate-spin"></i>
            CSVファイルインポート
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="/import-csv" method="post" enctype="multipart/form-data">
                        @csrf
                        
                        <p class="mb-5">
                            <input type="file" name="file">
                        </p>
                        <x-primary-button type="submit">インポート</x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
