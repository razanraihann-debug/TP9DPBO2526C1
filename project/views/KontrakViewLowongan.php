<?php
/**
 * Kontrak View Lowongan (Strict Passive View).
 * View tidak mengenal entity, tidak melakukan loop, dan tidak menghitung agregat.
 * Semua data datang dari Presenter dalam bentuk string / array primitif yang siap tampil.
 */
interface KontrakViewLowongan {
    public function renderDaftar(string $tableRowsHtml, int $totalData): string;
    public function renderForm(string $title, string $action, array $formData = []): string;
}
?>
