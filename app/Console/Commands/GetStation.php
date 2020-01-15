<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;

class GetStation extends Command
{

    protected $signature = 'GetStation';

    protected $description = 'Command description';

    public function handle()
    {

        mb_language("Japanese");
        mb_internal_encoding("UTF-8");

        //稼働時にmoyoriekiテーブルを空にする
        $SQL = "delete from moyorieki;";
        DB::statement($SQL);

        $SQL = "alter table moyorieki auto_increment = 1;";
        DB::statement($SQL);

        //----------------------//
        $station = [];
        $_pref = [];
        $result = DB::table('station')->get(['station_cd' , 'station_name' , 'pref_cd']);
        foreach($result as $v){
            $result2 = DB::table('pref')->where('pref_code' , '=' , $v->pref_cd)->take(1)->get(['pref_name']);
            $station[$result2[0]->pref_name][trim($v->station_name) . "駅"] = trim($v->station_cd);

            $_pref[$result2[0]->pref_name] = $v->pref_cd;
        }
        //----------------------//

        $myKey = "AIzaSyD9PkTM1Pur3YzmO-v4VzS0r8ZZ0jRJTIU";

        $sql = "select id , lat , lng , pref from coworking order by id;";
        $result = DB::select($sql);

        $st_code = [];
        $st_count = [];
        foreach ($result as $k=>$v){

            $url = "http://map.simpleapi.net/stationapi?x=" . $v->lng . "&y=" . $v->lat;

            $contents= file_get_contents($url);
            if (is_null($contents)){continue;}

            $xmlObject = simplexml_load_string($contents);
            if (is_null($xmlObject)){continue;}

            $xmlArray = json_decode( json_encode( $xmlObject ), TRUE ) ;
            if (is_null($xmlObject)){continue;}

            for ($i=0 ; $i<30 ; $i++){
                if (isset($xmlArray['station'][$i]['name'])){
                    if (isset($station[$v->pref][trim($xmlArray['station'][$i]['name'])])){
                        $insert = [];
                        $insert['cowork_id'] = $v->id;
                        $insert['pref_code'] = $_pref[$v->pref];
                        $insert['pref_name'] = $v->pref;

                        $ary = [];
                        $ary[] = $station[$v->pref][trim($xmlArray['station'][$i]['name'])];
                        $ary[] = trim($xmlArray['station'][$i]['name']);
                        $ary[] = trim($xmlArray['station'][$i]['traveltime']);
                        $insert['station'] = implode("|" , $ary);

print_r($insert);

                        DB::table('moyorieki')->insert($insert);
                    }
                }
            }
        }
    }
}
