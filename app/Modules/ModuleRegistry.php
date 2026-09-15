<?php

namespace App\Modules;

class ModuleRegistry
{
    /**
     * @var array<string, array{label: string, class: string}>
     */
    protected array $modules = [];

    /**
     * Mendaftarkan modul ke registry.
     */
    public function register(string $key, string $label, string $moduleClass): void
    {
        $this->modules[$key] = [
            'label' => $label,
            'class' => $moduleClass,
        ];
    }

    /**
     * Mengambil instance modul berdasarkan key.
     */
    public function get(string $key): ?ModuleInterface
    {
        if (isset($this->modules[$key])) {
            return app($this->modules[$key]['class']);
        }

        return null;
    }

    /**
     * Mendapatkan opsi modul untuk dropdown Select di Filament Admin.
     */
    public function getSelectOptions(): array
    {
        $options = [
            null => 'Halaman Statis (Tanpa Modul)',
        ];

        foreach ($this->modules as $key => $module) {
            $options[$key] = $module['label'];
        }

        return $options;
    }
}