<?php

use Illuminate\Database\Seeder;

class prefectureInputSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //--------------//
        $area = [];
        $file = "/tmp/toyoda/station/area.tsv";
        $content = file_get_contents($file);
        $ex_content = explode("\n" , mb_convert_encoding($content , "utf-8" , "sjis-win"));
        foreach($ex_content as $k=>$v){
            if (trim($v) == ""){continue;}
            if ($k==0){continue;}

            list($area_name , $pref_name) = explode("\t" , trim($v));

            $area[trim($pref_name)] = trim($area_name);
        }
        //--------------//

        $file = "/tmp/toyoda/station/pref.csv";
        $content = file_get_contents($file);
        $ex_content = explode("\n" , mb_convert_encoding($content , "utf-8" , "sjis-win"));
        foreach($ex_content as $k=>$v){
            if (trim($v) == ""){continue;}
            if ($k==0){continue;}
            list($pref_cd , $pref_name) = explode("," , trim($v));

            if (!isset($area[$pref_name])){continue;}

            $update = [];
            $update['pref_code'] = trim($pref_cd);
            $update['area'] = $area[$pref_name];

            DB::table('pref')->where('pref_name' , '=' , trim($pref_name))->update($update);
        }
    }
}
