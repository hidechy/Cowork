<!doctype html>
<html lang="ja">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Coworking</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

		<style>
		ul, li{margin: 0; padding: 0; border: 0; outline: 0; font-size: 100%; font-weight: normal; font-style: normal; vertical-align: baseline; background: transparent;}
		li {padding: 3px 5px;}
		ol, ul {list-style: none;}

		a {text-decoration: none;}

		.card {position: relative;}
		.card .menu__second-level {
		position: absolute;
		top: 136px;
		width: 100%;
		background: #e9ecef;
		border: 1px solid #cccccc;
		-webkit-transition: .2s ease;transition: .2s ease;
		visibility: hidden;
		opacity: 0;
		}
		.card:hover > .menu__second-level {top: 136px; visibility: visible; opacity: 1; z-index: 50;}

		.areaimg {cursor: pointer;}
		
		.topimage {height: 600px; background: url(img/cowork_cover.jpg); background-size:  cover;}
		</style>

		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css" integrity="sha256-UzFD2WYH2U1dQpKDjjZK72VtPeWP50NoJjd26rnAdUI=" crossorigin="anonymous" />

	</head>

	<body>

		<header>
			<div class="container">

				<div>
					<i class="fas fa-pencil-alt"></i>    Coworking space infomation
				</div>

				<p class="lead text-muted">全国のコワーキングスペースを検索することができます。</p>
			</div>
		</header>

		<section class="topimage"></section>

		<div class="navbar navbar-dark bg-dark shadow-sm">
			<div class="container d-flex justify-content-between">
				<br>
			</div>
		</div>

		<main role="main">
			<div class="album py-5 bg-light">
				<div class="container mb-5">
					<div class="row">

						@foreach ($area2 as $area_en=>$pref)
							<div class="col-md-3">
								<div class="card mb-3 shadow-sm">
									<img src="./img/area_{{ $area_en }}.jpg" class="img-fluid areaimg">

									<ul class="menu__second-level">
									@foreach ($pref['pref'] as $prefval)
									@php
									list($prefcode , $prefname) = explode("|" , $prefval);
									$url = "/pref/" . $prefcode;
									@endphp
									<li>
									<a href="{{ url($url) }}">
									{{ $prefname }}
									</a>
									</li>
									@endforeach
									</ul>
								</div>
							</div>
						@endforeach

					</div>
				</div>
			</div>
			
			<hr>

		</main>

		<footer class="text-muted">
			<div class="container">
				<br>
			</div>
		</footer>

	</body>
</html>
