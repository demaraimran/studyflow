<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudyFlow - Tugas Kuliah</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f3f6fc;
            color: #1e293b;
        }
        header {
            background: #1d4ed8;
            color: white;
            padding: 24px 7%;
        }
        header h1 { margin: 0 0 6px; }
        header p { margin: 0; }
        main { max-width: 1000px; margin: 30px auto; padding: 0 18px; }
        .summary {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
            margin-bottom: 22px;
        }
        .card, .panel {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 3px 12px #0f172a0b;
        }
        .number { font-size: 28px; font-weight: bold; color: #1d4ed8; }
        .panel { margin-bottom: 20px; }
        input, select, button {
            padding: 11px;
            border: 1px solid #cbd5e1;
            border-radius: 7px;
            font-size: 14px;
        }
        input, select { width: 100%; margin: 6px 0 12px; }
        label { font-size: 14px; font-weight: bold; }
        button {
            background: #1d4ed8;
            color: white;
            border: none;
            cursor: pointer;
        }
        .task {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            border-bottom: 1px solid #e2e8f0;
            padding: 15px 0;
        }
        .task:last-child { border-bottom: none; }
        .muted { color: #64748b; font-size: 14px; }
        .priority { font-size: 12px; font-weight: bold; }
        .actions { display: flex; gap: 8px; flex-wrap: wrap; }
        .delete { background: #dc2626; }
        .success { background: #dcfce7; color: #166534; padding: 12px; border-radius: 7px; }
        @media (max-width: 600px) {
            .summary { grid-template-columns: 1fr; }
            .task { align-items: flex-start; flex-direction: column; }
        }
    </style>
</head>
<body>
<header>
    <h1>StudyFlow</h1>
    <p>Kelola tugas kuliahmu dengan lebih teratur.</p>
</header>

<main>
    @if (session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    <section class="summary">
        <div class="card">
            <div class="muted">Total Tugas</div>
            <div class="number">{{ $tasks->count() }}</div>
        </div>
        <div class="card">
            <div class="muted">Belum Selesai</div>
            <div class="number">{{ $tasks->where('status', 'Belum selesai')->count() }}</div>
        </div>
        <div class="card">
            <div class="muted">Selesai</div>
            <div class="number">{{ $tasks->where('status', 'Selesai')->count() }}</div>
        </div>
    </section>

    <section class="panel">
        <h2>Tambah Tugas</h2>
        <form action="/tasks" method="POST">
            @csrf
            <label>Nama Tugas</label>
            <input name="title" required maxlength="255" placeholder="Contoh: Laporan praktikum">

            <label>Mata Kuliah</label>
            <input name="course" required maxlength="100" placeholder="Contoh: Komputasi Awan">

            <label>Deadline</label>
            <input type="date" name="deadline" required>

            <label>Prioritas</label>
            <select name="priority" required>
                <option value="Rendah">Rendah</option>
                <option value="Sedang" selected>Sedang</option>
                <option value="Tinggi">Tinggi</option>
            </select>

            <button type="submit">+ Tambah Tugas</button>
        </form>
    </section>

    <section class="panel">
        <h2>Daftar Tugas</h2>

        @forelse ($tasks as $task)
            <div class="task">
                <div>
                    <strong>{{ $task->title }}</strong>
                    <div class="muted">{{ $task->course }} · Deadline: {{ $task->deadline }}</div>
                    <div class="priority">Prioritas: {{ $task->priority }}</div>
                    <div class="muted">Status: {{ $task->status }}</div>
                </div>

                <div class="actions">
                    @if ($task->status !== 'Selesai')
                        <form action="/tasks/{{ $task->id }}/complete" method="POST">
                            @csrf
                            <button type="submit">Selesaikan</button>
                        </form>
                    @endif

                    <form action="/tasks/{{ $task->id }}/delete" method="POST"
                          onsubmit="return confirm('Hapus tugas ini?')">
                        @csrf
                        <button type="submit" class="delete">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="muted">Belum ada tugas. Tambahkan tugas pertamamu di atas!</p>
        @endforelse
    </section>
</main>
</body>
</html>
