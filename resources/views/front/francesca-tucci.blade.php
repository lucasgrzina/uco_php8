@extends('layouts.front')
@section('css')
    @parent
	<!--link href="{asset('css/home.css')}" rel="stylesheet" /-->
@endsection
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
<section class="section-free-text  m-0" >
	
	<div class="container " >
		<div class="row">
			
			<div class="col-md-5 col-text">
				<div class="wrap-text fade_JS">
					<h2>{!! trans('front.paginas.francescaTucci.modulo1.titulo') !!}</h2>
					<p>{!! trans('front.paginas.francescaTucci.modulo1.subtitulo') !!}</p>
					
				</div>
			</div>
		</div>
	</div>
	</div>
</section>

<section class="section-full-image  m-0" >
	<div class="bg-content w-100 h-100 " style="background-image: url({{asset('img/francesca-tucci-1.jpg')}});">
	<div class="container " >
		<div class="row">
			
			<div class="col-md-5 col-text">
				<div class="wrap-text fade_JS">
					<h2>{!! trans('front.paginas.francescaTucci.modulo2.titulo') !!}</h2>
					<p>{!! trans('front.paginas.francescaTucci.modulo2.subtitulo') !!}</p>
					<a href="{{routeIdioma('colecciones.tucci')}}" class="btn-primary">{!! trans('front.paginas.francescaTucci.modulo2.btn') !!}</a>
				</div>
			</div>
		</div>
	</div>
	</div>
</section>

<section class="section-full-image  m-0 inverse" >
	<div class="bg-content w-100 h-100 " style="background-image: url({{asset('img/francesca-tucci-2.jpg')}});">
	<div class="container " >
		<div class="row">
			
			<div class="col-md-7 col-text"></div>
			<div class="col-md-5 col-text">
				<div class="wrap-text fade_JS">
					<h2>{!! trans('front.paginas.francescaTucci.modulo3.titulo') !!}</h2>
					<p>{!! trans('front.paginas.francescaTucci.modulo3.subtitulo') !!}</p>
				</div>
			</div>
		</div>
	</div>
	</div>
</section>
@endsection  