<?php
/**
 * Entity Lowongan (lowongan pekerjaan yang dibuka HRD).
 */
class Lowongan {
    private ?int $id;
    private string $judul;
    private string $departemen;
    private string $lokasi;
    private int $kuota;
    private string $status; // 'Dibuka' | 'Ditutup'

    public function __construct(
        ?int $id,
        string $judul,
        string $departemen,
        string $lokasi,
        int $kuota,
        string $status
    ) {
        $this->id = $id;
        $this->judul = $judul;
        $this->departemen = $departemen;
        $this->lokasi = $lokasi;
        $this->kuota = $kuota;
        $this->status = $status;
    }

    public function getId(): ?int { return $this->id; }
    public function getJudul(): string { return $this->judul; }
    public function getDepartemen(): string { return $this->departemen; }
    public function getLokasi(): string { return $this->lokasi; }
    public function getKuota(): int { return $this->kuota; }
    public function getStatus(): string { return $this->status; }

    public function setJudul(string $judul): void { $this->judul = $judul; }
    public function setDepartemen(string $departemen): void { $this->departemen = $departemen; }
    public function setLokasi(string $lokasi): void { $this->lokasi = $lokasi; }
    public function setKuota(int $kuota): void { $this->kuota = $kuota; }
    public function setStatus(string $status): void { $this->status = $status; }
}
?>
