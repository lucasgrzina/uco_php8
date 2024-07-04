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
            if (_this.goToSeccion) {
                $('html, body').animate({
                    scrollTop: $("#"+_this.goToSeccion).offset().top
                });
            }
        });
    </script>
@endsection
@section('content')
@include('front.modules.module-full-slider',['items' => $data['slides']])
<section class="nuestro-compromiso-1 bg-white">
	<div class="container">
		<div class="row ">
			<div class="col-lg-6 fade_JS">
				<img src="{{asset('img/nuestro-compromiso-1.jpg')}}" class="img-fluid">
			</div>
			<div class="col-lg-1 center-v fade_JS"></div>
			<div class="col-lg-5 center-v fade_JS">
				<div class="wrap-text ">
					<h2>{!! trans('front.paginas.nuestroCompromiso.modulo1.titulo') !!}</h2>
					<p>{!! trans('front.paginas.nuestroCompromiso.modulo1.subtitulo') !!}</p>
				</div>
			</div>

		</div>

	</div>
</section>

<section class="slider-fullscreen-section bg-white" id="calidad">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12 p-0">
				<div class="content-arrows" id="content_arrows"></div>
				<div class="slider-fullscreen slider">
                    @foreach ($data['items'] as $item)
					<div>
						<div class="content-image">
							<img class="image" src="{{isset($item['imagen_interna']) && $item['imagen_interna'] ? asset($item['imagen_interna']) : asset($item['imagen'])}}">
						</div>

						<div class="content-text bg-gris-claro">
							<h2>{!! $item['titulo'] !!}</h2>
							<p>{!! $item['subtitulo'] !!}</p>
						</div>
					</div>
                    @endforeach

				</div>
			</div>
		</div>
	</div>
</section>

<section class="certificaciones-section bg-white" id="certificaciones">
	<div class="container">
		<div class="row">
			<div class="col-12 mb-item">
				<div class="row">
					<div class="col-md-12">
						<h2>{!! trans('front.paginas.nuestroCompromiso.certificaciones.titulo') !!}</h2>

					</div>
					<div class="col-md-6">
						<p class="subtitulo" style="color: #030303;">{!! trans('front.paginas.nuestroCompromiso.certificaciones.subtitulo') !!}</p>
					</div>
				</div>
			</div>
            @foreach (trans('front.paginas.nuestroCompromiso.certificaciones.items') as $item)
			<div class="col-md-4 mb-item">
				<img class="icon" src="{{asset($item['icono'])}}">
				<h3>{{str_replace('argeninta','ArgenInta',ucfirst(strtolower($item['titulo'])))}}</h3>
				<p  style="color: #030303;">{{$item['subtitulo']}}</p>
			</div>
            @endforeach
		</div>
	</div>
</section>
@endsection
