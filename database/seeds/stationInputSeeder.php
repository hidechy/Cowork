<?php

use Illuminate\Database\Seeder;

class stationInputSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file = "/tmp/toyoda/station/station.csv";
        $content = file_get_contents($file);
        $ex_content = explode("\n" , mb_convert_encoding($content , "utf-8" , "sjis-win"));
        foreach($ex_content as $k=>$v){
            if (trim($v) == ""){continue;}
            if ($k==0){continue;}

            $insert = [];

            $ex_v = explode("," , trim($v));

            $insert['station_cd'] = trim($ex_v[0]);
            $insert['station_g_cd'] = trim($ex_v[1]);
            $insert['station_name'] = trim($ex_v[2]);
            $insert['station_name_k'] = trim($ex_v[3]);
            $insert['station_name_r'] = trim($ex_v[4]);

            $insert['line_cd'] = trim($ex_v[5]);
            $insert['pref_cd'] = trim($ex_v[6]);
            $insert['post'] = trim($ex_v[7]);
            $insert['add'] = trim($ex_v[8]);
            $insert['lon'] = trim($ex_v[9]);

            $insert['lat'] = trim($ex_v[10]);
            $insert['open_ymd'] = trim($ex_v[11]);
            $insert['close_ymd'] = trim($ex_v[12]);
            $insert['e_status'] = trim($ex_v[13]);
            $insert['e_sort'] = trim($ex_v[14]);

            DB::table('station')->insert($insert);
        }
    }
}
