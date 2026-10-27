<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

Route::get('/', function () {
    $tasks = DB::table('tasks')
        ->orderBy('deadline')
        ->get();

    return view('tasks', compact('tasks'));
});

Route::post('/tasks', function (Request $request) {
    $data = $request->validate([
        'title' => 'required|string|max:255',
        'course' => 'required|string|max:100',
        'deadline' => 'required|date',
        'priority' => 'required|in:Rendah,Sedang,Tinggi',
    ]);

    DB::table('tasks')->insert([
        ...$data,
        'status' => 'Belum selesai',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return redirect('/')->with('success', 'Tugas berhasil ditambahkan!');
});

Route::post('/tasks/{id}/complete', function ($id) {
    DB::table('tasks')->where('id', $id)->update([
        'status' => 'Selesai',
        'updated_at' => now(),
    ]);

    return redirect('/');
});

Route::post('/tasks/{id}/delete', function ($id) {
    DB::table('tasks')->where('id', $id)->delete();

    return redirect('/');
});
