<?php

use Illuminate\Database\Seeder;

class lineInputSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file = "/tmp/toyoda/station/line.csv";
        $content = file_get_contents($file);
        $ex_content = explode("\n" , mb_convert_encoding($content , "utf-8" , "sjis-win"));
        foreach($ex_content as $k=>$v){
            if (trim($v) == ""){continue;}
            if ($k==0){continue;}

            $insert = [];

            $ex_v = explode("," , trim($v));
            $insert['line_cd'] = trim($ex_v[0]);
            $insert['company_cd'] = trim($ex_v[1]);
            $insert['line_name'] = trim($ex_v[2]);
            $insert['line_name_k'] = trim($ex_v[3]);
            $insert['line_name_h'] = trim($ex_v[4]);
            $insert['line_color_c'] = trim($ex_v[5]);
            $insert['line_color_t'] = trim($ex_v[6]);
            $insert['line_type'] = trim($ex_v[7]);
            $insert['lon'] = trim($ex_v[8]);
            $insert['lat'] = trim($ex_v[9]);
            $insert['zoom'] = trim($ex_v[10]);
            $insert['e_status'] = trim($ex_v[11]);
            $insert['e_sort'] = trim($ex_v[12]);
            DB::table('line')->insert($insert);
        }
    }
}
