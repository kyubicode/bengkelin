<?php

namespace App\Support;

class NavigationPermissions
{
    /**
     * File ini digenerate otomatis oleh:
     *   php artisan nav-permissions:generate
     *
     * Label boleh diedit manual, tapi jangan ubah key-nya kalau sudah
     * dipakai di data role yang ada di database.
     */
    public static function all(): array
    {
        return [
            'assignments' => 'Assignment',
            'bookings' => 'Booking',
            'complaint_symptoms' => 'Complaint Symptom',
            'galleries' => 'Gallery',
            'navigations' => 'Navigation',
            'pages' => 'Page',
            'roles' => 'Role',
            'services' => 'Service',
            'technician_tasks' => 'Technician Task',
            'users' => 'User',
            'vehicle_types' => 'Vehicle Type',
        ];
    }
}