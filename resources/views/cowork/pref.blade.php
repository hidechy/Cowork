<!doctype html>
<html lang="ja">
  <head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Coworking</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

  <style>
  .city {border: 1px solid #cccccc;}
  .bg_exists {background: #ffff99;}
  .color_not_exists {color: #999999;}
  </style>

  </head>

  <body>
    <header>
      <div class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container d-flex justify-content-between">
          <br>
        </div>
      </div>
    </header>

    <main role="main">

      <div class="container mb-5">

        <div class="row mt-5">
            <h2>{{ $pref_name }}</h2>
        </div>

        <div class="row mt-5">
          <h3>地域で探す</h3>
        </div>

        <div class="row">
          @foreach ($city as $_city)
            <div class="col-md-2 p-2 m-2 city bg_exists">
            @php $url = "/city/" . $pref_name . $_city @endphp
            <a href="{{ url($url) }}">{{ $_city }}</a>
            </div>
          @endforeach
        </div>

        <div class="row mt-5">
            <h3>路線で探す</h3>
        </div>

        @foreach ($line as $v)
        <div class="my-2">{{ $v['linename'] }}</div>
        <div class="row">
        @foreach ($v['station'] as $_sta)
        @php list($stationcode , $stationname , $flag) = explode("|" , $_sta); $url = "/station/" . $stationcode; @endphp
        <div class="col-md-2 p-2 m-2 city @php if ($flag == 1){echo "bg_exists";}else{echo "color_not_exists";} @endphp">
          @if ($flag == 1) <a href="{{ url($url) }}"> @endif
            {{ $stationname }}
          @if ($flag == 1) </a> @endif
        </div>
        @endforeach
        </div>
        @endforeach

      </div>

    </main>

    <footer class="text-muted">
      <div class="container">
        <br>
      </div>
    </footer>

  </body>
</html>
