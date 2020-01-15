<?php

namespace App\Http\Controllers\Cowork;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;

class CoworkController extends Controller
{



    public function test(){

        $data = [];

        $result = DB::table('pref')->orderBy('id')->get(['pref_name' , 'city_name']);
        foreach ($result as $v){
            $result2 = DB::table('coworking')
                ->where('address' , 'like' , ($v->pref_name . $v->city_name . '%'))
                ->where('ng' , '=' , '0')
                ->orderBy('id')
                ->get();
            foreach($result2 as $v2){

                $ary = [
                    'name' => '' , 'address' => '' , 'price' => '' , 'url' => '' , 'time' => '' ,
                    'yasumi' => '' , 'setsubi' => '' , 'tel' => '' , 'image' => '' , 'lat' => '' , 
                    'lng' => '' , 
                    'station' => []
                ];
    
                $result3 = DB::table('moyorieki')->where('cowork_id' , '=' , $v2->id)->orderBy('id')->get();
                $sta = [];
                foreach($result3 as $v3){
                    $sta[] = $v3->station;
                }
    
                if (empty($sta)){continue;}
    
                $ary['name'] = $v2->name;
                $ary['address'] = $v2->address;
                $ary['price'] = $v2->price;
                $ary['url'] = $v2->url;
                $ary['time'] = $v2->time;
                $ary['yasumi'] = $v2->yasumi;
                $ary['setsubi'] = $v2->setsubi;
                $ary['tel'] = $v2->tel;
                $ary['image'] = $v2->image;
                $ary['lat'] = $v2->lat;
                $ary['lng'] = $v2->lng;
                $ary['station'] = $sta;
    
                $data[$v2->pref][] = $ary;

            }
        }

print_r($data);

    }


    
    public function index(){

        //---------------//
        $area = [];
        $area2 = [];

        $ary = ['北海道地方' => 'hokkaidou' , '東北地方' => 'touhoku' , '関東地方' => 'kantou' , '中部地方' => 'chuubu' , '近畿地方' => 'kinki' , '中国地方' => 'chuugoku' , '四国地方' => 'shikoku' , '九州地方' => 'kyuushuu'];

        $sql = "select area from pref group by area order by id;";
        $result = DB::select($sql);
        foreach($result as $v){
            $area[] = trim($v->area);

            $pref = [];
            $sql2 = "select pref_code , pref_name from pref where area = '" . $v->area . "' group by pref_code , pref_name order by id;";
            $result2 = DB::select($sql2);
            foreach($result2 as $v2){
                $pref[] = trim($v2->pref_code) . "|" . trim($v2->pref_name);
            }

            $area_en = $ary[$v->area];
            $area2[$area_en]['pref'] = $pref;
        }
        //---------------//

        $appUrl = "http://" . $_SERVER['HTTP_HOST'] . "/Cowork";

        return view('cowork.index')
            ->with('area' , $area)
            ->with('area2' , $area2)
            ->with('appUrl' , $appUrl);
    }



    public function pref($pref){

        $result = DB::table('pref')->where('pref_code' , '=' , $pref)->take(1)->get(['pref_name']);

        $line = $this->getline($pref);
        $city = $this->getcity($pref);

        return view('cowork.pref')
            ->with('pref_name' , $result[0]->pref_name)
            ->with('city' , $city)
            ->with('line' , $line);
    }



    public function getline($pref){

        $line_tmp = [];
        $exists_station_tmp = [];

        $sql_base_select_moyorieki = " select cowork_id , station from moyorieki where pref_code = '{PREF}'; ";
        $result = DB::select(strtr($sql_base_select_moyorieki , ['{PREF}' => $pref]));

        foreach($result as $v){

            list($stationcode , $stationname , $walk) = explode("|" , $v->station);

            $exists_station_tmp[trim($stationcode)] = "";

            $result2 = DB::table('station')->where('station_cd' , '=' , $stationcode)->get(['line_cd']);
            $line_tmp[trim($result2[0]->line_cd)] = "";
        }
        $line = array_keys($line_tmp);
        sort($line);

        $exists_station = array_keys($exists_station_tmp);
        sort($exists_station);

        $line_station = [];
        foreach($line as $li){
            $result3 = DB::table('station')->where('line_cd' , '=' , $li)->orderBy('id')->get(['station_cd' , 'station_name']);

            $sta = [];
            foreach($result3 as $v3){
                $ary = [];
                $ary['station_code'] = $v3->station_cd;
                $ary['station_name'] = $v3->station_name;
                $ary['station_exists'] = (in_array($v3->station_cd , $exists_station)) ? 1 : 0;
                $sta[] = implode("|" , $ary);
            }

            $result4 = DB::table('line')->where('line_cd' , '=' , $li)->get(['line_name']);
            $line_station[$li] = ['linecode' => $li , 'linename' => $result4[0]->line_name , 'station' => $sta];
        }

        return $line_station;
    }



    public function getcity($pref){

        $prefcity = [];
        $result = DB::table('pref')->where('pref_code' , '=' , $pref)->orderBy('id')->get(['pref_name' , 'city_name']);
        foreach($result as $k=>$v){
            $prefcity[$k]['pref'] = $v->pref_name;
            $prefcity[$k]['city'] = $v->city_name;
        }

        $city = [];
        foreach($prefcity as $v){
            $result = DB::table('coworking')
                ->where('address' , 'like' , ($v['pref'].$v['city']."%"))
                ->where('ng' , '=' , '0')
                ->get(['id']);
            if (isset($result[0])){
                $city[] = $v['city'];
            }
        }

        return $city;
    }



    private $getColumn_coworking = [
        'name' , 'address' , 'price' , 'url' , 'time' , 
        'yasumi' , 'setsubi' , 'tel' , 'image' , 'id' , 
        'lat' , 'lng'
    ];



    public function city($city){

        $result = DB::table('coworking')
            ->where('address' , 'like' , ($city . "%"))
            ->where('ng' , '=' , '0')
            ->orderBy('address')
            ->get($this->getColumn_coworking);

        $data = $this->makedata($result);

        return view('cowork.datadisp')
            ->with('data' , $data);
    }



    public function station($station){

        $sql_base_select_coworking = "
            select " . implode(" , " , $this->getColumn_coworking) . " from coworking 
            where id in (select cowork_id from moyorieki where station like '{STATION}%') 
            and ng = '0' 
            order by address;";

        $result = DB::select(strtr($sql_base_select_coworking , ['{STATION}' => $station]));

        $data = $this->makedata($result);

        return view('cowork.datadisp')
            ->with('data' , $data);
    }



    private function makedata($result){

        $data = [];
        foreach($result as $v){
            $data[$v->id]['name'] = $v->name;
            $data[$v->id]['address'] = $v->address;
            $data[$v->id]['price'] = $v->price;
            $data[$v->id]['url'] = $v->url;
            $data[$v->id]['time'] = $v->time;
            $data[$v->id]['yasumi'] = $v->yasumi;
            $data[$v->id]['tel'] = $v->tel;
            $data[$v->id]['lat'] = $v->lat;
            $data[$v->id]['lng'] = $v->lng;

            $ex_setsubi = explode("/" , $v->setsubi);
            $setsubi = [];
            foreach($ex_setsubi as $v2){
                $setsubi[] = trim($v2);
            }
            $data[$v->id]['setsubi'] = $setsubi;

            if (trim($v->image) != ""){
                $ex_image = explode("|" , $v->image);
                $image = [];
                foreach($ex_image as $v2){
                    $image[] = trim($v2);
                }
                $data[$v->id]['image'] = $image;
            }else{
                $data[$v->id]['image'][0] = "/Cowork/img/Noimage_image.png";
            }

            $result2 = DB::table('moyorieki')->where('cowork_id' , '=' , $v->id)->get(['station']);
            $station = [];
            foreach($result2 as $v2){
                $station[] = trim($v2->station);
            }
            $data[$v->id]['station'] = $station;
        }

        return $data;
    }






}
