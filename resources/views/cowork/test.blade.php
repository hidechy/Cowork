<?php
$ex_phpself = explode("/" , $_SERVER['PHP_SELF']);
array_pop($ex_phpself);
$public_path = implode("/" , $ex_phpself);
?>

<!doctype html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>coworkingリスト</title>

    <style>
    * {font-size: 12px;}
    .pref {background: #ccccff; font-weight: bold;}
    .list_table {margin-bottom: 30px;}
    .list_table td {border: 1px solid #cccccc;}
    </style>

    <script src="{{ $public_path }}/js/jquery-3.3.1.min.js"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}" />

  </head>
  <body>

    <form method="POST" action="{{ url('/hide') }}" id="form_cowork_hide">
    {{ csrf_field() }}

    @foreach ($data as $pref=>$v)
        <div class="pref">{{ $pref }}</div>

        <?php
        print_r($data2[$pref]);
        ?>

        <table class="list_table" border="0" cellspacing="2" cellpadding="2">
        @foreach ($v as $v2)
            <tr>
                <td><input type="checkbox" name="hide[{{ $v2['id'] }}]" value="1"></td>
                <td>{{ $v2['price'] }}</td>
                <td>{{ $v2['address'] }}
                <div>
                @for ($i=1 ; $i<=3 ; $i++)
                {{ $v2['station_' . $i] }}
                 | 
                @endfor
                </div>
                </td>
                <td><a href="{{ $v2['url'] }}" target="_blank">{{ $v2['name'] }}</a></td>
            </tr>
        @endforeach
        </table>
    @endforeach
    </form>

    <button id="btn_hide">表示しない</button>
    

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <script>
    $("#btn_hide").click(function (){
        $("#form_cowork_hide").submit();
    });
    </script>

  </body>
</html>
