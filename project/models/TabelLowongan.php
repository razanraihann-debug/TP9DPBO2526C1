<?php
require_once __DIR__ . '/DB.php';
require_once __DIR__ . '/KontrakModelLowongan.php';
require_once __DIR__ . '/Lowongan.php';

class TabelLowongan extends DB implements KontrakModelLowongan {

    public function __construct(
        string $host = "localhost",
        string $db_name = "mvp_kandidat_db",
        string $username = "root",
        string $password = ""
    ) {
        parent::__construct($host, $db_name, $username, $password);
    }

    private function mapRowToLowongan(array $row): Lowongan {
        return new Lowongan(
            (int)$row['id'],
            $row['judul'],
            $row['departemen'],
            $row['lokasi'],
            (int)$row['kuota'],
            $row['status']
        );
    }

    public function getAllLowongan(): array {
        $this->executeQuery("SELECT * FROM lowongan ORDER BY id ASC");
        $rows = $this->getAllResult();

        $list = [];
        foreach ($rows as $row) {
            $list[] = $this->mapRowToLowongan($row);
        }
        return $list;
    }

    public function getLowonganById(int $id): ?Lowongan {
        $this->executeQuery("SELECT * FROM lowongan WHERE id = :id LIMIT 1", [':id' => $id]);
        $rows = $this->getAllResult();
        if (empty($rows)) {
            return null;
        }
        return $this->mapRowToLowongan($rows[0]);
    }

    public function addLowongan(string $judul, string $departemen, string $lokasi, int $kuota, string $status): void {
        $query = "INSERT INTO lowongan (judul, departemen, lokasi, kuota, status)
                  VALUES (:judul, :departemen, :lokasi, :kuota, :status)";
        $this->executeQuery($query, [
            ':judul'      => $judul,
            ':departemen' => $departemen,
            ':lokasi'     => $lokasi,
            ':kuota'      => $kuota,
            ':status'     => $status,
        ]);
    }

    public function updateLowongan(int $id, string $judul, string $departemen, string $lokasi, int $kuota, string $status): void {
        $query = "UPDATE lowongan
                  SET judul = :judul, departemen = :departemen, lokasi = :lokasi,
                      kuota = :kuota, status = :status
                  WHERE id = :id";
        $this->executeQuery($query, [
            ':judul'      => $judul,
            ':departemen' => $departemen,
            ':lokasi'     => $lokasi,
            ':kuota'      => $kuota,
            ':status'     => $status,
            ':id'         => $id,
        ]);
    }

    public function deleteLowongan(int $id): void {
        $this->executeQuery("DELETE FROM lowongan WHERE id = :id", [':id' => $id]);
    }
}
?>
