<x-user-layout>
    {{-- successセッションがあった場合は、成功と表示される --}}
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-2 mb-3 text-xs" role="alert">
            <p class="font-bold">成功</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif
        {{-- errorセッションがあった場合は、エラーと表示される --}}
    @if (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-2 mb-3 text-xs" role="alert">
            <p class="font-bold">エラー</p>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <div class="container mx-auto px-4 py-6">
        <!-- 献立候補を手動で追加するセクション -->
        <div class="mb-4">
            <h1 class="text-2xl font-bold text-gray-800">献立候補を入力し手動で追加する</h1>
        </div>
        {{-- 手動で献立を入力して登録する --}}
        <form method="POST" action="{{ route('admin.menu_options.store') }}">
            @csrf
            <div class="lg:w-1/2 md:w-2/3 mx-auto">
                <div class="flex flex-wrap -m-2">
                    @for ($i = 0; $i < 4; $i++)
                        <div class="p-2 w-full md:w-1/2">
                            <div class="relative">
                                <label for="name_{{ $i }}" class="leading-7 text-sm text-gray-600">献立名</label>
                                <input type="text" id="name_{{ $i }}" name="names[]" placeholder="例: カレーライス" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-yellow-500 focus:bg-white focus:ring-2 focus:ring-yellow-200 text-base outline-none text-gray-700 py-2 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            </div>
                        </div>
                        <div class="p-2 w-full md:w-1/2">
                            <div class="relative">
                                <label for="type_{{ $i }}" class="leading-7 text-sm text-gray-600">献立タイプ</label>
                                <select id="type_{{ $i }}" name="types[]" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-yellow-500 focus:bg-white focus:ring-2 focus:ring-yellow-200 text-base outline-none text-gray-700 py-2 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                    <option value="main">メイン</option>
                                    <option value="sub">サブ</option>
                                    <!-- 必要に応じて他のオプションを追加 -->
                                </select>
                            </div>
                        </div>
                    @endfor
                    {{-- 献立候補追加ボタン --}}
                    <div class="p-6 w-full">
                        <button type="submit" class="flex mx-auto text-white bg-green-500 border-0 py-3 px-10 focus:outline-none hover:bg-green-600 rounded-lg text-lg transition duration-300 ease-in-out transform hover:scale-105">
                            献立候補を追加する
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="container mx-auto px-4 py-6">
        <!-- 献立候補を選択するセクション -->
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold text-gray-800">献立候補からマイ献立を選択してください</h1>
            <p>チェックボックスにチェックを入れた後、下にスクロールして保存して下さい。</p>
            <a href="{{ route('user.index') }}" class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-3 rounded-full transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                週間献立画面に戻る
            </a>
        </div>

        <form method="POST" action="{{ route('user.dishes.bulkStore') }}">
            @csrf

            @php
                // 日本語のあいうえお順にソートするための関数を定義
                function sortByJapanese($collection) {
                    return $collection->sort(function($a, $b) {
                        $collator = new \Collator('ja_JP');
                        return $collator->compare($a->dish_name, $b->dish_name);
                    });
                }

                // メインメニューをソート
                $main_menu_options = sortByJapanese($menu_options->where('dish_type', 'main'));

                // サブメニューをソート
                $sub_menu_options = sortByJapanese($menu_options->where('dish_type', 'sub'));
            @endphp

            <!-- メインメニュー -->
            <div class="mb-4">
                <h2 class="text-lg font-semibold mb-2 text-gray-700">メインメニュー</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                    @foreach ($main_menu_options as $menu_option)
                        <div class="border rounded-lg p-4 flex flex-col items-start">
                            <label class="inline-flex items-center">
                                <!-- チェックボックスのチェックを外す -->
                                <input type="checkbox" name="menu_option_ids[]" value="{{ $menu_option->id }}" class="form-checkbox h-5 w-5 text-blue-600">
                                <span class="ml-2 text-gray-700">{{ $menu_option->dish_name }}</span>
                            </label>
                            @if(in_array($menu_option->id, $userDishIds))
                                <span class="text-sm text-green-600 mt-1">保存済み</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- サブメニュー -->
            <div class="mb-4">
                <h2 class="text-lg font-semibold mb-2 text-gray-700">サブメニュー</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                    @foreach ($sub_menu_options as $menu_option)
                        <div class="border rounded-lg p-4 flex flex-col items-start">
                            <label class="inline-flex items-center">
                                <!-- チェックボックスのチェックを外す -->
                                <input type="checkbox" name="menu_option_ids[]" value="{{ $menu_option->id }}" class="form-checkbox h-5 w-5 text-blue-600">
                                <span class="ml-2 text-gray-700">{{ $menu_option->dish_name }}</span>
                            </label>
                            @if(in_array($menu_option->id, $userDishIds))
                                <span class="text-sm text-green-600 mt-1">保存済み</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- 保存ボタン -->
            <div class="text-center mt-6 flex justify-center space-x-4">
                <button type="submit" formaction="{{ route('user.dishes.bulkStore') }}" class="text-white bg-green-500 hover:bg-green-600 py-2 px-8 rounded-lg text-lg">
                    選択したメニューをマイ献立に保存
                </button>
            <!-- 削除ボタン -->
                <button type="submit" formaction="{{ route('user.dishes.bulkDelete') }}" class="text-white bg-red-500 hover:bg-red-600 py-2 px-8 rounded-lg text-lg">
                    選択したメニューをマイ献立から削除
                </button>
            </div>
        </form>
    </div>
</x-user-layout>
