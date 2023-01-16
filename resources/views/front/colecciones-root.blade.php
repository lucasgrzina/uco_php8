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
<section class="section-full-image  m-0 inverse" >
	<div class="bg-content w-100 h-100 " style="background-image: url({{asset('img/vino-2.jpg')}});">
	<div class="container " >
		<div class="row">
			
			<div class="col-md-7 col-text"></div>
			<div class="col-md-5 col-text">
				<div class="wrap-text fade_JS">
					<h2>{!! trans('front.paginas.colecciones.root.francescaTucci.titulo') !!}</h2>
					<p>{!! trans('front.paginas.colecciones.root.francescaTucci.subtitulo') !!}</p>
					<a href="{{routeIdioma('colecciones.tucci')}}" class="btn-primary btn-marron-claro" style="float: right;">{!! trans('front.paginas.colecciones.root.francescaTucci.btn') !!}</a>
				</div>
			</div>
		</div>
	</div>
	</div>
</section>

<section class="section-full-image  m-0" >
	<div class="bg-content w-100 h-100 " style="background-image: url({{asset('img/vino-3.jpg')}});">
	<div class="container " >
		<div class="row">
			
			<div class="col-md-5 col-text">
				<div class="wrap-text fade_JS">
					<h2>{!! trans('front.paginas.colecciones.root.interwine.titulo') !!}</h2>
					<p>{!! trans('front.paginas.colecciones.root.interwine.subtitulo') !!}</p>
					<a href="{{routeIdioma('colecciones.interwine')}}" class="btn-primary btn-marron">{!! trans('front.paginas.colecciones.root.interwine.btn') !!}</a>
				</div>
			</div>
		</div>
	</div>
	</div>
</section>

<section class="section-almacenamiento  m-0" >
	<div class="container">
		<div class="col-12"><h2>{!! trans('front.paginas.colecciones.root.almacenamiento.titulo') !!}</h2></div>
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