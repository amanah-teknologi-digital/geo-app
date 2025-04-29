<?php

namespace App\Rules;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Log;

class CekHariDalamRange implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    protected $tglJadwal; // Field gabungan

    public function __construct($tglJadwal)
    {
        $this->tglJadwal = $tglJadwal;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Pecah tgl_jadwal jadi 2 tanggal
        $parts = explode(' s/d ', $this->tglJadwal);

        if (count($parts) != 2) {
            $fail('Format tanggal jadwal tidak valid.');
            return;
        }

        try {
            $mulai = Carbon::createFromFormat('d-m-Y', trim($parts[0]));
            $selesai = Carbon::createFromFormat('d-m-Y', trim($parts[1]));
        } catch (\Exception $e) {
            $fail('Format tanggal tidak valid.');
            return;
        }

        $period = CarbonPeriod::create($mulai, $selesai);

        foreach ($period as $date) {
            Log::info('Cek tanggal: ' . $date->format('d-m-Y') . ' | Hari ke-' . $date->format('N'));
            if ((int) $date->dayOfWeek === ((int) $value - 1)) {
                return; // Valid
            }
        }

        $fail('Hari yang dipilih tidak ditemukan dalam rentang tanggal.');
    }
}
