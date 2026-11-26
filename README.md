# JANJI
Saya Razan Raihan Malik dengan NIM 2508838 mengerjakan Tugas Praktikum 9 pada Mata Kuliah Desain dan Pemrograman Berorientasi Objek (DPBO) untuk keberkahan-Nya maka saya tidak melakukan kecurangan seperti yang telah dispesifikasikan. Aamiin

# Desain Program

## Struktur Folder
```
project/
├── index.php                          # Front controller: pilih modul + routing GET/POST
├── models/
│   ├── DB.php                         # Koneksi PDO + executeQuery (prepared statement)
│   ├── Kandidat.php                   # Entity kandidat
│   ├── KontrakModel.php               # Interface model kandidat
│   ├── TabelKandidat.php              # Model kandidat (query)
│   ├── Lowongan.php                   # Entity lowongan            (BARU)
│   ├── KontrakModelLowongan.php       # Interface model lowongan   (BARU)
│   └── TabelLowongan.php              # Model lowongan (query)     (BARU)
├── presenters/
│   ├── KontrakPresenter.php / PresenterKandidat.php
│   └── KontrakPresenterLowongan.php / PresenterLowongan.php      (BARU)
├── views/
│   ├── KontrakView.php / ViewKandidat.php
│   └── KontrakViewLowongan.php / ViewLowongan.php                (BARU)
└── template/
    ├── skin.html / form.html                    # Template kandidat
    └── skin_lowongan.html / form_lowongan.html  # Template lowongan (BARU)
```

### Skema Database
```
kandidat (id PK AI, nama, posisi, domisili, skorTes, pengalamanTahun)
lowongan (id PK AI, judul, departemen, lokasi, kuota, status ENUM('Dibuka','Ditutup'))
```
Kedua tabel berdiri sendiri (tanpa foreign key).

## Penerapan MVP — Strict Passive View
- **Model** (`TabelKandidat`, `TabelLowongan`) satu-satunya lapisan yang menyentuh database; semua query memakai *prepared statement* (named parameter) lewat `DB::executeQuery()`. Hasil query dipetakan ke objek **entity** (`Kandidat`, `Lowongan`).
- **Presenter** menerima `KontrakModel*` dan `KontrakView*` (bergantung pada **interface**, bukan class konkret). Presenter mengambil entity dari Model, melakukan `foreach`/`count`, merakit potongan HTML baris tabel dan opsi `<select>` (sudah di-`htmlspecialchars`), lalu menyerahkannya ke View sebagai **string/array primitif**.
- **View** (`ViewKandidat`, `ViewLowongan`) pasif: hanya membaca file template dan mengganti token `{{...}}` dengan `str_replace`. Di folder `views/` **tidak ada** `require_once` Model/Entity, **tidak ada** `foreach`, dan **tidak ada** `count()` — hanya `require_once` ke kontrak View-nya sendiri.
- **Kontrak**: `KontrakModel`, `KontrakView`, `KontrakPresenter` (kandidat) serta `KontrakModelLowongan`, `KontrakViewLowongan`, `KontrakPresenterLowongan` (lowongan).

### Diagram Alur
```
Browser ──request──▶ index.php ──pilih modul (?module=) & action──▶ Presenter
                                                                      │  ▲
                                                 KontrakModel*        ▼  │ entity
                                                                    Model ──PDO──▶ MySQL
                                                                      │
                                              string HTML / array     ▼
Browser ◀────────── HTML ────────── View (isi token template) ◀── Presenter
```

# Alur Program

**Routing di `index.php`.** Modul ditentukan oleh `$_REQUEST['module']` (default `kandidat`, atau `lowongan`). Setelah itu:
- **POST** → baca `$_POST['action']`:
  - `tambah` → `tambah{Kandidat|Lowongan}()`
  - `ubah` → `ubah{...}()`
  - `hapus` → `hapus{...}()`
  Presenter memanggil Model, lalu redirect (`header("Location: ...")`) ke halaman daftar (pola *Post/Redirect/Get* agar refresh tidak mengirim ulang form).
- **GET** → baca `?screen=`: `add` → form kosong, `edit&id=N` → form terisi data, selain itu → daftar.

**Create.** Klik *+ Tambah* → `screen=add` → Presenter memanggil `View::renderForm(..., "tambah", [])` → submit POST `action=tambah` → `Presenter::tambah...()` → `Model::add...()` (`INSERT` prepared) → redirect ke daftar.

**Read.** `Presenter::tampilkan...()` → `Model::getAll...()` → loop entity menjadi baris HTML → `View::renderDaftar($rowsHtml, $total)`.

**Update.** Klik *Edit* → `screen=edit&id=N` → Presenter memanggil `Model::get...ById()`, mengubah entity menjadi array `formData` (prefill; untuk lowongan juga opsi status dengan `selected`) → submit POST `action=ubah` → `Model::update...()` (`UPDATE ... WHERE id = :id`) → redirect.

**Delete.** Tombol *Hapus* berupa form POST kecil (`action=hapus`, `id`) dengan konfirmasi JavaScript → `Model::delete...()` (`DELETE ... WHERE id = :id`) → redirect.

**Validasi sisi Presenter.** Input di-`trim`; kandidat: skor dibatasi 0–100 dan pengalaman minimal 0; lowongan: kuota minimal 1 dan status di luar `Dibuka`/`Ditutup` dipaksa menjadi `Dibuka`. Output selalu di-escape dengan `htmlspecialchars` (anti-XSS), dan query memakai prepared statement (anti-SQL injection).

## Yang Dikerjakan pada Tugas
| Berkas | Isi |
|---|---|
| `models/TabelKandidat.php` | `addKandidat()`, `updateKandidat()`, `deleteKandidat()` dengan prepared statement |
| `presenters/PresenterKandidat.php` | `tambahKandidat()`, `ubahKandidat()`, `hapusKandidat()` |
| `index.php` | Penanganan POST `tambah` / `ubah` / `hapus` (kandidat dan lowongan) |
| Modul **lowongan** | Tabel `lowongan` + Entity, 3 Kontrak, Model, View, Presenter, 2 template, CRUD penuh |
| `mvp_db.sql` | Ditambah `CREATE TABLE lowongan` + data awal |

# Dokumentasi
[![YouTube](https://img.shields.io/badge/YouTube-FF0000?style=for-the-badge&logo=youtube&logoColor=white)](https://youtu.be/CJjBG8ejl94)

klik untuk melihat video