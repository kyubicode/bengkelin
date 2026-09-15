<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'booking_date' => 'date',
        'end_time' => 'datetime',
        'assigned_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // ===== RELASI =====

    public function services()
    {
        return $this->belongsToMany(Service::class, 'booking_service');
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function symptoms()
    {
        return $this->belongsToMany(ComplaintSymptom::class, 'booking_complaints')
                    ->withPivot('assessment_answer')
                    ->withTimestamps();
    }

    // ===== ANTRIAN =====

    public function getQueueNumberAttribute(): ?int
    {
        if (! in_array($this->status, ['confirmed', 'processing', 'completed'])) {
            return null;
        }

        return static::query()
            ->whereDate('booking_date', $this->booking_date)
            ->whereIn('status', ['confirmed', 'processing', 'completed'])
            ->where(function ($query) {
                $query->where('booking_time', '<', $this->booking_time)
                    ->orWhere(function ($query) {
                        $query->where('booking_time', $this->booking_time)
                            ->where('created_at', '<', $this->created_at);
                    });
            })
            ->count() + 1;
    }

    // ===== KETERSEDIAAN TEKNISI =====

    /**
     * Hitung waktu selesai dari tanggal + jam mulai + durasi (menit)
     */
    public static function calculateEndTime(string $date, string $time, int $durationMinutes): Carbon
    {
        return Carbon::parse("$date $time")->addMinutes($durationMinutes);
    }

    /**
     * Cek apakah teknisi kosong pada slot waktu tertentu.
     *
     * Logic overlap: dua rentang waktu bentrok jika
     * (mulai_baru < selesai_lama) DAN (selesai_baru > mulai_lama)
     */
    public static function isTechnicianAvailable(
        int $technicianId,
        string $date,
        string $startTime,
        int $durationMinutes,
        ?int $ignoreBookingId = null
    ): bool {
        $newStart = Carbon::parse("$date $startTime");
        $newEnd = $newStart->copy()->addMinutes($durationMinutes);

        $query = static::query()
            ->where('technician_id', $technicianId)
            ->whereDate('booking_date', $date)
            ->whereNotIn('status', ['cancelled'])
            // existing_start < new_end  →  booking_date+booking_time < newEnd
            ->whereRaw('TIMESTAMP(booking_date, booking_time) < ?', [$newEnd])
            ->where(function ($q) use ($newStart) {
                // existing_end > new_start
                // fallback ke booking_time + 60 menit kalau end_time masih null (data lama)
                $q->where('end_time', '>', $newStart)
                  ->orWhere(function ($q2) use ($newStart) {
                      $q2->whereNull('end_time')
                         ->whereRaw('TIMESTAMP(booking_date, booking_time) + INTERVAL 60 MINUTE > ?', [$newStart]);
                  });
            });

        if ($ignoreBookingId) {
            $query->where('id', '!=', $ignoreBookingId);
        }

        return ! $query->exists();
    }

    /**
     * Ambil semua teknisi yang kosong pada slot waktu tertentu.
     */
    public static function getAvailableTechnicians(
        string $date,
        string $startTime,
        int $durationMinutes,
        ?int $ignoreBookingId = null
    ) {
        return User::technicians()
            ->get()
            ->filter(fn ($tech) => static::isTechnicianAvailable(
                $tech->id, $date, $startTime, $durationMinutes, $ignoreBookingId
            ))
            ->values();
    }

        /**
     * Cek apakah teknisi tertentu sedang punya job lain yang statusnya 'processing'.
     * Dipakai untuk mencegah teknisi mengerjakan 2 booking sekaligus secara bersamaan.
     */
    public static function technicianHasActiveJob(int $technicianId, ?int $ignoreBookingId = null): bool
    {
        $query = static::query()
            ->where('technician_id', $technicianId)
            ->where('status', 'processing');

        if ($ignoreBookingId) {
            $query->where('id', '!=', $ignoreBookingId);
        }

        return $query->exists();
    }
}