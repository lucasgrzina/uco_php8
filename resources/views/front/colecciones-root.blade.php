@extends('layouts.front')
@section('scripts')
    @parent
    <script type="text/javascript">
        var _data = {!! json_encode($data) !!};


        this._mounted.push(function(_this) {

        });
    </script>
@endsection

@section('content')
@include('front.modules.module-full-slider',['items' => $data['slides']])
<section class="section-full-image  my-2 inverse" >
	<div class="bg-content w-100 h-100 " style="background-image: url({{asset('img/header-shop-magiadeuconotable.jpg')}});">
	<div class="container " >
		<div class="row">

			<div class="col-md-7 col-text"></div>
			<div class="col-md-5 col-text">
				<div class="wrap-text fade_JS">
					<h2>{!! trans('front.paginas.colecciones.root.magiaDeUcoNotable.titulo') !!}</h2>
					<p>{!! trans('front.paginas.colecciones.root.magiaDeUcoNotable.subtitulo') !!}</p>
					<a href="{{routeIdioma('colecciones.magiaUcoNotable')}}" class="btn-primary btn-marron-claro" style="float: right;">{!! trans('front.paginas.colecciones.root.magiaDeUcoNotable.btn') !!}</a>
				</div>
			</div>
		</div>
	</div>
	</div>
</section>

<section class="section-full-image  m-0" >
	<div class="bg-content w-100 h-100 " style="background-image: url({{asset('img/header-shop-magiadeuco.jpg')}});background-position-x: right;">
	<div class="container " >
		<div class="row">

			<div class="col-md-5 col-text">
				<div class="wrap-text fade_JS">
					<h2 class="d-none d-md-block">{!! trans('front.paginas.colecciones.root.magiaDeUco.titulo') !!}</h2>
					<p class="d-none d-md-block">{!! trans('front.paginas.colecciones.root.magiaDeUco.subtitulo') !!}</p>
					<a href="{{routeIdioma('colecciones.magiaUco')}}" class="btn-primary btn-marron">{!! trans('front.paginas.colecciones.root.magiaDeUco.btn') !!}</a>
				</div>
			</div>
		</div>
	</div>
	</div>
</section>

<section class="section-almacenamiento bg-white  m-0" >
	<div class="container">
		<div class="col-12"><h2>{!! trans('front.paginas.colecciones.root.almacenamiento.titulo') !!}</h2></div>
        <div class="col-12 text-center">
            <p>{!! trans('front.paginas.colecciones.root.almacenamiento.subtitulo') !!}</p>
        </div>		
		<div class="col-12">
			<div class="grid-items slider">
				@foreach (trans('front.paginas.colecciones.root.almacenamiento.items') as $i => $item)
				<div class="item">
					<div class="text">
						<h5>{!! trans('front.paginas.colecciones.root.almacenamiento.items.'.$i.'.titulo') !!}</h5>
						<p>{!! trans('front.paginas.colecciones.root.almacenamiento.items.'.$i.'.subtitulo') !!}</p>
					</div>
					<img class="img-background" src="{{asset('img/almacenamiento-'. ($i).'.jpg')}}">
				</div>
				@endforeach

			</div>
		</div>
	</div>


</section>

@endsection
