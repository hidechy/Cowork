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

        <div class="row">
          @foreach ($data as $v)
          <div class="col-3">
          <div class="card" style="width:15rem;">
          <img class="card-img-top" src="{{ $v['image'][0] }}" alt="Card image cap">
          <div class="card-body">
          <h5 class="card-title">{{ $v['name'] }}</h5>
          <h6 class="card-subtitle text-muted">{{ $v['address'] }}</h6>
          </div>
          </div>
          </div>
          @endforeach
        </div>
      </div>

    </main>

    <footer class="text-muted">
      <div class="container">
        <br>
      </div>
    </footer>

  </body>
</html>
