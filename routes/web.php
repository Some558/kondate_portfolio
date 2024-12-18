<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MenuOptionsController;
use App\Http\Controllers\UserMenuController;
use App\Http\Controllers\UserDishesController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;

// ルートページ
Route::get('/', function () {
    return view('welcome');
});

// ユーザー画面
Route::prefix('user')->name('user.')->middleware('auth')->group(function() {
    // 献立一覧ページ表示
    Route::get('index/all', [UserMenuController::class, 'indexall'])->name('indexall');
    // 週間献立ページ表示
    Route::get('index', [UserMenuController::class, 'index'])->name('index');
    // 曜日の毎献立保存
    Route::post('menu/keep', [UserMenuController::class, 'keep'])->name('menu.keep');
    // 曜日毎献立ランダム表示
    Route::post('menu/randomkeep', [UserMenuController::class, 'randomkeep'])->name('menu.randomkeep');
    // 献立候補登録ページ表示⇨マイ献立追加ページに変更予定
    Route::get('dishes', [UserDishesController::class, 'index'])->name('dishes');
    // ユーザー毎献立候補新規登録
    Route::post('dishes', [UserDishesController::class, 'store'])->name('dishes.store');
    // ユーザー毎献立まとめて保存
    Route::post('dishes/bulkstore', [UserDishesController::class, 'bulkStore'])->name('dishes.bulkStore');
    // ユーザー毎献立候補削除
    Route::delete('dishes/{userMenuId}', [UserDishesController::class, 'destroy'])->name('dishes.destroy');
    // ユーザー毎献立まとめて削除
    Route::post('dishes/bulkdelete', [UserDishesController::class, 'bulkDelete'])->name('dishes.bulkDelete');
});

Route::get('/dashboard', function () {
    return redirect()->route('admin.top');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// 管理画面
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    // 管理画面トップページ
    Route::get('top', [MenuOptionsController::class,'top'])->name('top');

    // 献立管理
    Route::prefix('menu_options')->name('menu_options.')->group(function(){
        // 献立新規登録画面
        Route::get('create' , [MenuOptionsController::class, 'create'])->name('create');
        // 献立新規登録処理
        Route::post('store' , [MenuOptionsController::class, 'store'])->name('store');
        // 献立編集画面
        Route::get('{menu_optionId}/edit',[MenuOptionsController::class, 'edit'])->name('edit');
        // 献立更新処理
        Route::post('{menu_optionId}/update',[MenuOptionsController::class, 'update'])->name('update');
        // 献立削除処理
        Route::post('{menu_optionId}/destroy',[MenuOptionsController::class, 'destroy'])->name('destroy');
    });
});
