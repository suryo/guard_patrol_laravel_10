<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TbPhaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [];
        $now = Carbon::now();

        for ($i = 1; $i <= 10; $i++) {
            $data[] = [
                'phaseId'            => 'PHASE-' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'schedule_group_uid' => 1, // ganti sesuai uid schedule_group yang valid
                'phaseDate'          => $now->toDateString(),
                'lastUpdated'        => $now,
                'phaseName'          => 'Phase ' . $i,
                'phaseOrder'         => $i,
            ];
        }

        DB::table('tb_phases')->insert($data);
    }
}
