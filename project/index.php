<?php
require_once __DIR__ . '/models/TabelKandidat.php';
require_once __DIR__ . '/views/ViewKandidat.php';
require_once __DIR__ . '/presenters/PresenterKandidat.php';

require_once __DIR__ . '/models/TabelLowongan.php';
require_once __DIR__ . '/views/ViewLowongan.php';
require_once __DIR__ . '/presenters/PresenterLowongan.php';

// Pemilih modul: ?module=lowongan (GET) atau input hidden "module" (POST). Default: kandidat.
$modul = $_REQUEST['module'] ?? 'kandidat';

if ($modul === 'lowongan') {
    $model = new TabelLowongan('localhost', 'mvp_kandidat_db', 'root', '');
    $view = new ViewLowongan();
    $presenter = new PresenterLowongan($model, $view);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? '';
        if ($action === 'tambah') {
            $presenter->tambahLowongan(
                $_POST['judul'] ?? '',
                $_POST['departemen'] ?? '',
                $_POST['lokasi'] ?? '',
                (int)($_POST['kuota'] ?? 1),
                $_POST['status'] ?? 'Dibuka'
            );
        } elseif ($action === 'ubah') {
            $presenter->ubahLowongan(
                (int)($_POST['id'] ?? 0),
                $_POST['judul'] ?? '',
                $_POST['departemen'] ?? '',
                $_POST['lokasi'] ?? '',
                (int)($_POST['kuota'] ?? 1),
                $_POST['status'] ?? 'Dibuka'
            );
        } elseif ($action === 'hapus') {
            $presenter->hapusLowongan((int)($_POST['id'] ?? 0));
        }
        header("Location: index.php?module=lowongan");
        exit();
    } else {
        $screen = $_GET['screen'] ?? 'list';
        if ($screen === 'add') {
            echo $presenter->tampilkanFormLowongan();
        } elseif ($screen === 'edit' && isset($_GET['id'])) {
            echo $presenter->tampilkanFormLowongan((int)$_GET['id']);
        } else {
            echo $presenter->tampilkanLowongan();
        }
    }
} else {
    $model = new TabelKandidat('localhost', 'mvp_kandidat_db', 'root', '');
    $view = new ViewKandidat();
    $presenter = new PresenterKandidat($model, $view);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? '';
        if ($action === 'tambah') {
            $presenter->tambahKandidat(
                $_POST['nama'] ?? '',
                $_POST['posisi'] ?? '',
                $_POST['domisili'] ?? '',
                (int)($_POST['skorTes'] ?? 0),
                (int)($_POST['pengalamanTahun'] ?? 0)
            );
        } elseif ($action === 'ubah') {
            $presenter->ubahKandidat(
                (int)($_POST['id'] ?? 0),
                $_POST['nama'] ?? '',
                $_POST['posisi'] ?? '',
                $_POST['domisili'] ?? '',
                (int)($_POST['skorTes'] ?? 0),
                (int)($_POST['pengalamanTahun'] ?? 0)
            );
        } elseif ($action === 'hapus') {
            $presenter->hapusKandidat((int)($_POST['id'] ?? 0));
        }
        header("Location: index.php");
        exit();
    } else {
        $screen = $_GET['screen'] ?? 'list';
        if ($screen === 'add') {
            echo $presenter->tampilkanFormKandidat();
        } elseif ($screen === 'edit' && isset($_GET['id'])) {
            echo $presenter->tampilkanFormKandidat((int)$_GET['id']);
        } else {
            echo $presenter->tampilkanKandidat();
        }
    }
}
?>
