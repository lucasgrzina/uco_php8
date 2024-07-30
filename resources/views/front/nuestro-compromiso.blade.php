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

			<div class="col-lg-6 center-v fade_JS">
				<div class="wrap-text ">
					<h2>{!! trans('front.paginas.nuestroCompromiso.modulo1.titulo') !!}</h2>
					<p>{!! trans('front.paginas.nuestroCompromiso.modulo1.subtitulo') !!}</p>
				</div>
			</div>

		</div>

	</div>
</section>


<section class="section-almacenamiento nuestra-gente bg-white  m-0" id="calidad">
	<div class="container-fluid">


        <div class="col-12">
			<div class="grid-items slider-gente">
				@foreach ($data['items'] as $key=>$item)
				<div class="item">
					<img class="img-background" src="{{isset($item['imagen_interna']) && $item['imagen_interna'] ? asset($item['imagen_interna']) : asset($item['imagen'])}}">

					<div class="text v-desktop">
                        <h2>{!! $item['titulo'] !!}</h2>
						<p>{!! $item['subtitulo'] !!}</p>
					</div>
					<div class="text v-mobile">
                        <h2>{!! $item['titulo'] !!}</h2>
						<p style="margin-bottom: 10px;">
                            {!! $item['subtituloCorto'] !!}
                        </p>
                        <a href="#" style="white-space: nowrap;text-decoration:none;" class="open-modal" data-bs-toggle="modal" data-bs-target="#modal_slide_{{$key}}">Ver más</a>

					</div>



				</div>
				@endforeach

			</div>
		</div>
		@foreach ($data['items'] as $key=>$item)
		<!-- Modal -->
		<div class="modal modal-generico fade" id="modal_slide_{{$key}}" tabindex="-1" aria-labelledby="slide_modal_label_{{$key}}" aria-hidden="true">
		  <div class="modal-dialog  modal-dialog-scrollable modal-fullscreen-md-down">
		    <div class="modal-content modal-slide" style="background-color: #fafaf9;">
		      <div class="modal-header">
		        <h1 class="modal-title fs-5" id="slide_modal_label_{{$key}}">{!! $item['titulo'] !!}</h1>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">
		        <p>{!! $item['subtitulo'] !!}</p>
		      </div>
		      <div class="modal-footer d-none">
		        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
		      </div>
		    </div>
		  </div>
		</div>
		@endforeach
		</div>
	</div>
</section>

<section class="certificaciones-section bg-white" id="certificaciones">
	<div class="container">
		<div class="row">
			<div class="col-12 mb-item">
				<div class="row">
					<div class="col-md-12">
						<h2 class="text-center">{!! trans('front.paginas.nuestroCompromiso.certificaciones.titulo') !!}</h2>

					</div>
					<!--div class="col-md-3"></div-->
					<div class="col-md-10 col-lg-10 mx-auto">
						<p class="subtitulo text-center" style="color: #030303;">{!! trans('front.paginas.nuestroCompromiso.certificaciones.subtitulo') !!}</p>
					</div>
				</div>
			</div>
		</div>
		<div class="row slide-only-mobile">
            @foreach (trans('front.paginas.nuestroCompromiso.certificaciones.items') as $item)
			<div class="col-md-4 mb-item">
				<img class="icon d-flex m-auto mb-3" src="{{asset($item['icono'])}}">
				<h3 class="text-titulo">{{str_replace('argeninta','ArgenInta',ucfirst(strtolower($item['titulo'])))}}</h3>
				<p class="text-subtitulo"  style="color: #030303;">{{$item['subtitulo']}}</p>
			</div>
            @endforeach
		</div>
	</div>
</section>
@endsection
