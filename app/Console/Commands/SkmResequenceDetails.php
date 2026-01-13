<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SkmResequenceDetails extends Command
{
    protected $signature = 'skm:resequence-details {--skm_id=}';
    protected $description = 'Mengisi / merapikan no_urut detail SKM agar selalu 1..n per skm_id';

    public function handle(): int
    {
        $skmId = $this->option('skm_id');

        DB::transaction(function () use ($skmId) {
            // Ambil daftar skm_id yang perlu di resequence
            $skmIds = DB::table('skm_details')
                ->when($skmId, fn($q) => $q->where('skm_id', $skmId))
                ->select('skm_id')
                ->distinct()
                ->pluck('skm_id');

            foreach ($skmIds as $id) {
                $rows = DB::table('skm_details')
                    ->where('skm_id', $id)
                    ->orderByRaw('COALESCE(no_urut, 999999999) asc') // yang null taruh belakang
                    ->orderBy('id', 'asc')
                    ->lockForUpdate()
                    ->get(['id']);

                $i = 1;
                foreach ($rows as $row) {
                    DB::table('skm_details')->where('id', $row->id)->update(['no_urut' => $i]);
                    $i++;
                }
            }
        });

        $this->info('Selesai resequence no_urut.');
        return self::SUCCESS;
    }
}
