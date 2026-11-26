<?php
require_once __DIR__ . '/KontrakPresenterLowongan.php';
require_once __DIR__ . '/../models/KontrakModelLowongan.php';
require_once __DIR__ . '/../views/KontrakViewLowongan.php';

class PresenterLowongan implements KontrakPresenterLowongan {
    private const STATUS_VALID = ['Dibuka', 'Ditutup'];

    private KontrakModelLowongan $model;
    private KontrakViewLowongan $view;

    public function __construct(KontrakModelLowongan $model, KontrakViewLowongan $view) {
        $this->model = $model;
        $this->view = $view;
    }

    /** Merakit 1 baris HTML tabel lowongan. */
    private function buildRowHtml(int $no, Lowongan $lowongan): string {
        $id = (int)$lowongan->getId();
        $judul = htmlspecialchars($lowongan->getJudul());
        $departemen = htmlspecialchars($lowongan->getDepartemen());
        $lokasi = htmlspecialchars($lowongan->getLokasi());
        $kuota = number_format($lowongan->getKuota()) . ' orang';
        $status = $lowongan->getStatus();
        $badge = $status === 'Dibuka' ? 'badge-open' : 'badge-closed';
        $statusHtml = "<span class='badge {$badge}'>" . htmlspecialchars($status) . "</span>";

        return "<tr>
            <td class='col-id'>{$no}</td>
            <td><strong>{$judul}</strong></td>
            <td>{$departemen}</td>
            <td>{$lokasi}</td>
            <td>{$kuota}</td>
            <td>{$statusHtml}</td>
            <td class='col-actions'>
                <a href='index.php?module=lowongan&screen=edit&id={$id}' class='btn btn-edit'>Edit</a>
                <form method='post' action='index.php' style='display:inline;' onsubmit=\"return confirm('Hapus lowongan ini?')\">
                    <input type='hidden' name='module' value='lowongan'>
                    <input type='hidden' name='action' value='hapus'>
                    <input type='hidden' name='id' value='{$id}'>
                    <button type='submit' class='btn btn-delete'>Hapus</button>
                </form>
            </td>
        </tr>";
    }

    /** Merakit <option> status; opsi yang sama dengan $dipilih diberi atribut selected. */
    private function buildStatusOptions(string $dipilih): string {
        $html = '';
        foreach (self::STATUS_VALID as $status) {
            $selected = ($status === $dipilih) ? ' selected' : '';
            $html .= "<option value='{$status}'{$selected}>{$status}</option>";
        }
        return $html;
    }

    public function tampilkanLowongan(): string {
        $listLowongan = $this->model->getAllLowongan();
        $total = count($listLowongan);

        $rowsHtml = '';
        if (empty($listLowongan)) {
            $rowsHtml = '<tr><td colspan="7" style="text-align:center;color:#6b7280;padding:24px;">Belum ada data lowongan.</td></tr>';
        } else {
            $no = 1;
            foreach ($listLowongan as $lowongan) {
                $rowsHtml .= $this->buildRowHtml($no, $lowongan);
                $no++;
            }
        }

        return $this->view->renderDaftar($rowsHtml, $total);
    }

    public function tampilkanFormLowongan(?int $id = null): string {
        if ($id !== null) {
            $lowongan = $this->model->getLowonganById($id);
            if ($lowongan === null) {
                header("Location: index.php?module=lowongan");
                exit();
            }

            $formData = [
                'id'            => $lowongan->getId(),
                'judul'         => $lowongan->getJudul(),
                'departemen'    => $lowongan->getDepartemen(),
                'lokasi'        => $lowongan->getLokasi(),
                'kuota'         => $lowongan->getKuota(),
                'statusOptions' => $this->buildStatusOptions($lowongan->getStatus()),
            ];
            return $this->view->renderForm("Ubah Data Lowongan", "ubah", $formData);
        }

        return $this->view->renderForm("Tambah Lowongan Baru", "tambah", [
            'statusOptions' => $this->buildStatusOptions('Dibuka'),
        ]);
    }

    public function tambahLowongan(string $judul, string $departemen, string $lokasi, int $kuota, string $status): void {
        $this->model->addLowongan(trim($judul), trim($departemen), trim($lokasi), max(1, $kuota), $this->normalisasiStatus($status));
        header("Location: index.php?module=lowongan");
        exit();
    }

    public function ubahLowongan(int $id, string $judul, string $departemen, string $lokasi, int $kuota, string $status): void {
        $this->model->updateLowongan($id, trim($judul), trim($departemen), trim($lokasi), max(1, $kuota), $this->normalisasiStatus($status));
        header("Location: index.php?module=lowongan");
        exit();
    }

    public function hapusLowongan(int $id): void {
        $this->model->deleteLowongan($id);
        header("Location: index.php?module=lowongan");
        exit();
    }

    /** Status di luar daftar valid dipaksa menjadi 'Dibuka'. */
    private function normalisasiStatus(string $status): string {
        return in_array($status, self::STATUS_VALID, true) ? $status : 'Dibuka';
    }
}
?>
