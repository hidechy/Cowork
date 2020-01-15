<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;

class GetCoworkingInfo extends Command
{

    protected $signature = 'GetCoworkingInfo';

    protected $description = 'Command description';

    public function handle()
    {
        //------------------------------//
        $num_line = "";
        $url = "https://co-co-po.com/?s=%E3%83%89%E3%83%AD%E3%83%83%E3%83%97%E3%82%A4%E3%83%B3";
        $content = file_get_contents($url);
        $ex_content = explode("\n" , $content);
        foreach ($ex_content as $v){
            if (preg_match("/件のコワーキングスペースがみつかりました/" , trim($v))){
                $num_line = trim(strip_tags($v));
                break;
            }
        }
        preg_match("/(.+)件の/" , $num_line , $m);
        $page_num = ceil(trim($m[1]) / 20);
        //------------------------------//

        $base_url = "https://co-co-po.com/page/{NUM}/?s=%E3%83%89%E3%83%AD%E3%83%83%E3%83%97%E3%82%A4%E3%83%B3";

        for ($i=1 ; $i<=$page_num ; $i++){
//if ($i>1){continue;}

            $url = strtr($base_url , ['{NUM}' => $i]);
            $content = file_get_contents($url);
            $ex_content = explode("\n" , $content);
            $str = "";
            foreach ($ex_content as $v){
                $str .= trim($v);
            }
            $ex_str = explode("|" , strtr($str , ['><' => '>|<']));

            $a = [];
            $b = 0;
            foreach($ex_str as $k=>$v){
                if (preg_match("/<div class=\"no-thumbitiran\">/" , trim($v))){$a[] = $k;}

                if (preg_match("/お探しの条件で/" , trim($v))){
                    $b = $k;
                    break;
                }
            }
            $a[] = $b;

            $data = [];
            for ($c=0 ; $c<count($a)-1 ; $c++){
                $data[$c] = ['name'=>'' , 'address'=>'' , 'price'=>'' , 'mark'=>'' , 'url'=>'' , 'pref'=>''];

                $f = "";
                $g = [];

                for ($d=$a[$c] ; $d<$a[$c+1] ; $d++){

                    if (preg_match("/href=\"https:\/\/co-co-po.com\/space/" , $ex_str[$d])){
                        $data[$c]['name'] = trim(strip_tags($ex_str[$d]));

                        $ex_name = explode("\"" , $ex_str[$d]);
                        $data[$c]['url'] = trim($ex_name[1]);
                    }

                    if (preg_match("/住所/" , $ex_str[$d])){
                        $__address = $ex_str[$d+1];
                        if (trim($data[$c]['address']) == ""){
                            $data[$c]['address'] = trim(strip_tags($__address));

                            $result = DB::table('pref')
                                ->where('pref_name' , 'like' , mb_substr($data[$c]['address'] , 0 , 2) . "%")
                                ->take(1)
                                ->get(['pref_name']);
                            if (isset($result[0])){
                                $data[$c]['pref'] = trim($result[0]->pref_name);
                            }
                        }
                    }

                    if (preg_match("/ドロップイン<\/th>/" , $ex_str[$d])){
//$data[$c]['price'] = trim(strip_tags($ex_str[$d+1]));
$f = $d;
                    }
                    if (preg_match("/<\/td>/" , $ex_str[$d])){
                        $g[] = $d;
                    }

                    if (preg_match("/ic_14\.jpg/" , $ex_str[$d])){
                        $data[$c]['mark'] = trim($ex_str[$d]);
                    }

                }

                $pri = [];
                if (trim($f) != "" and isset($g[0])){
                    $l = "";
                    rsort($g);
                    foreach($g as $gg){
                        if ($gg > $f){
                            $l = $gg;
                        }
                    }

                    for ($m=$f+1 ; $m<=$l ; $m++){
                        $pri[] = trim(strip_tags($ex_str[$m]));
                    }
                }
                $data[$c]['price'] = implode(" " , $pri);

            }

            $data2 = [];
            foreach ($data as $k=>$v){
//if ($k>0){break;}

                if (trim($v['mark']) == ""){continue;}
                unset($v['mark']);

                if (trim($v['pref']) == ""){continue;}

                if (preg_match("/閉店/" , $v['name'])){continue;}

                $__url = $v['url'];
                unset($v['url']);

                $data2[$k] = $v;

                $content2 = file_get_contents($__url);
                $ex_content2 = explode("\n" , $content2);
                $str2 = "";
                foreach ($ex_content2 as $v2){
                    $str2 .= trim($v2);
                }
                $ex_str2 = explode("|" , strtr($str2 , ['><' => '>|<']));

                $e1 = "";$e2 = "";$e3 = "";$e4 = "";$e5 = "";
                $e6 = [];

                $data2[$k]['url'] = "";
                $data2[$k]['time'] = "";
                $data2[$k]['yasumi'] = "";
                $data2[$k]['setsubi'] = "";
                $data2[$k]['tel'] = "";
                $data2[$k]['image'] = "";

                foreach($ex_str2 as $k2=>$v2){
                    if (preg_match("/<th>URL/" , $v2)){
                        $e1 = $k2;
                    }
                    if (preg_match("/<th.+営業時間/" , $v2)){
                        $e2 = $k2;
                    }
                    if (preg_match("/<th.+定休日/" , $v2)){
                        $e3 = $k2;
                    }
                    if (preg_match("/<th.+設備/" , $v2)){
                        $e4 = $k2;
                    }
                    if (preg_match("/<th.+電話番号/" , $v2)){
                        $e5 = $k2;
                    }

                    if (preg_match("/jpg.+alignnone/" , $v2)){
                        $e6[] = $k2;
                    }
                }

                if (isset($e6[0])){
                    $img = [];
                    foreach ($e6 as $ali){
                        if (!empty($ex_str[$ali])){
                            $ex_ali = explode(" " , $ex_str2[$ali]);
                            foreach ($ex_ali as $_ali){
                                if (preg_match("/src=\"(.+)\"/" , $_ali , $m)){
                                    if (isset($m[1])){
                                        if (preg_match("/no-image/" , trim($m[1]))){continue;}
                                        $img[] = trim($m[1]);
                                    }
                                }
                            }
                        }
                    }

                    $data2[$k]['image'] = implode(" | " , $img);
                }

                if (trim($e1) != ""){$data2[$k]['url'] = trim(strip_tags($ex_str2[$e1+2]));}
                if (trim($e2) != ""){$data2[$k]['time'] = trim(strip_tags($ex_str2[$e2+1]));}
                if (trim($e3) != ""){$data2[$k]['yasumi'] = trim(strip_tags($ex_str2[$e3+1]));}

                if (trim($e4) != ""){
                    $setsu = [];
                    $ex_setsubi = explode(" " , $ex_str2[$e4+2]);
                    foreach ($ex_setsubi as $ex_se){
                        preg_match("/alt=\"(.+)\">/" , $ex_se , $m);
                        if (isset($m[1])){
                            $setsu[] = trim($m[1]);
                        }
                    }
                    $data2[$k]['setsubi'] = (isset($setsu[0])) ? implode(" / " , $setsu) : "";
                }

                if (trim($e5) != ""){$data2[$k]['tel'] = trim(strip_tags($ex_str2[$e5+1]));}
            }
print_r($data2);

            foreach($data2 as $v){

                $result = DB::table('coworking')->where('url' , '=' , $v['url'])->get();
                if (isset($result[0])){continue;}

                $v['created_at'] = date("Y-m-d");
                $v['updated_at'] = date("Y-m-d");
                $v['ng'] = 0;

                DB::table('coworking')->insert($v);
            }
        }
    }
}
