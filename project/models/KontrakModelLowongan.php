<?php
require_once __DIR__ . '/Lowongan.php';

/**
 * Kontrak Model untuk modul Lowongan.
 * Presenter hanya boleh bergantung pada interface ini, bukan pada TabelLowongan.
 */
interface KontrakModelLowongan {
    /**
     * @return Lowongan[]
     */
    public function getAllLowongan(): array;

    public function getLowonganById(int $id): ?Lowongan;

    public function addLowongan(string $judul, string $departemen, string $lokasi, int $kuota, string $status): void;
    public function updateLowongan(int $id, string $judul, string $departemen, string $lokasi, int $kuota, string $status): void;
    public function deleteLowongan(int $id): void;
}
?>
