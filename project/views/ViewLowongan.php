<?php
require_once __DIR__ . '/KontrakViewLowongan.php';

class ViewLowongan implements KontrakViewLowongan {
    private string $skinPath;
    private string $formPath;

    public function __construct(string $skinPath = '', string $formPath = '') {
        $this->skinPath = $skinPath ?: __DIR__ . '/../template/skin_lowongan.html';
        $this->formPath = $formPath ?: __DIR__ . '/../template/form_lowongan.html';
    }

    public function renderDaftar(string $tableRowsHtml, int $totalData): string {
        $template = file_get_contents($this->skinPath);

        $tokens = [
            '{{TABLE_ROWS}}' => $tableRowsHtml,
            '{{TOTAL_DATA}}' => (string)$totalData,
        ];

        return str_replace(array_keys($tokens), array_values($tokens), $template);
    }

    public function renderForm(string $title, string $action, array $formData = []): string {
        $template = file_get_contents($this->formPath);

        // Opsi <select> status sudah dirakit Presenter (string HTML), View hanya menempelkan.
        $tokens = [
            '{{FORM_TITLE}}'    => htmlspecialchars($title),
            '{{ACTION}}'        => htmlspecialchars($action),
            '{{ID}}'            => htmlspecialchars((string)($formData['id'] ?? '')),
            '{{JUDUL}}'         => htmlspecialchars($formData['judul'] ?? ''),
            '{{DEPARTEMEN}}'    => htmlspecialchars($formData['departemen'] ?? ''),
            '{{LOKASI}}'        => htmlspecialchars($formData['lokasi'] ?? ''),
            '{{KUOTA}}'         => htmlspecialchars((string)($formData['kuota'] ?? 1)),
            '{{STATUS_OPTIONS}}' => $formData['statusOptions'] ?? '',
        ];

        return str_replace(array_keys($tokens), array_values($tokens), $template);
    }
}
?>
