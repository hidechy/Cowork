<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;

class GetLatLng extends Command
{

    protected $signature = 'GetLatLng';

    protected $description = 'Command description';

    public function handle()
    {

        mb_language("Japanese");
        mb_internal_encoding("UTF-8");

        //稼働時にnullにして再取得する
        $SQL = "update coworking set lat=null,lng=null;";
        DB::statement($SQL);

        $myKey = "AIzaSyD9PkTM1Pur3YzmO-v4VzS0r8ZZ0jRJTIU";

        $sql = "select id , address from coworking where (lat is NULL or lng is NULL) order by id;";
        $result = DB::select($sql);

        foreach ($result as $k=>$v){

            $ex_address = explode(" " , $v->address);
            $address = trim($ex_address[0]);
            $address = urlencode($address);

            $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . $address . "+CA&key=" . $myKey ;

            $contents= file_get_contents($url);
            if (is_null($contents)){continue;}

            $jsonData = json_decode($contents,true);
            if (!isset($jsonData["results"][0])){continue;}

            $lat = $jsonData["results"][0]["geometry"]["location"]["lat"];
            $lng = $jsonData["results"][0]["geometry"]["location"]["lng"];

            $update = [];
            $update['lat'] = $lat;
            $update['lng'] = $lng;

echo $address;
echo "\n";
echo $lat;
echo "\n";
echo $lng;
echo "\n";echo "\n";echo "\n";echo "\n";

            DB::table('coworking')->where('id' , '=' , $v->id)->update($update);
        }
    }
}
