<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TbGroupSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [];
        for ($h = 0; $h < 24; $h++) {
            $start = str_pad($h, 2, '0', STR_PAD_LEFT) . ':00';
            $end   = str_pad($h, 2, '0', STR_PAD_LEFT) . ':59';

            $rows[] = [
                'groupId'    => 'GRP-' . str_pad($h, 2, '0', STR_PAD_LEFT),
                'groupName'  => "Group [$start - $end]",
                'timeStart'  => $start,
                'timeEnd'    => $end,
                'note'       => null,
                'lastUpdated'=> now(),
            ];
        }

        DB::table('tb_groups')->insert($rows);
    }
}
