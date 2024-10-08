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
<!-- CONTENT -->
@include('front.modules.module-full-slider',['items' => $data['slides']])

<section class="section-legado">
	<div class="container">
		<div class="row ">
			<div class="col-lg-7 col-img ">
				<img src="{{asset('img/legado.jpg')}}?1" class="img-fluid">
			</div>
			<div class="col-lg-5 center-v ">
				<div class="wrap-text fade_JS">
					<h2>{!! trans('front.paginas.home.legado.titulo') !!}</h2>
					<p>{!! trans('front.paginas.home.legado.subtitulo') !!}</p>
					<a href="{{routeIdioma('legado')}}" class="btn-primary">{!! trans('front.paginas.home.legado.btn') !!}</a>
				</div>
			</div>
		</div>

	</div>
</section>

<section class="section-gallery">
	<div class="container-fluid">
		<div class="row ">
			<div class="col-lg-5 col-img ">
				<div class="wrap-text fade_JS">
					<h2>{!! trans('front.paginas.home.compromiso.titulo') !!}</h2>
					<p>{!! trans('front.paginas.home.compromiso.subtitulo') !!}</p>
					<div class="content-arrows" id="content_arrows">

					</div>
				</div>
			</div>
			<div class="col-lg-7 content-slider " style="padding-right: 0;">
				<div class="slider">
                    @php
                        $i = 1;
                        //$imgSlider = 'certificaciones.jpg';
                        $imgSlider = isset($data['nuestroCompromiso']['certificaciones']) ? 'uploads/nuestro_compromiso/' .$data['nuestroCompromiso']['certificaciones'] : 'img/calidad.jpg';
                    @endphp
                    @foreach (trans('front.paginas.home.compromiso.slider') as $key => $item)
                        <div>
                            <div class="wrap-slide">
                                <a href="{{routeIdioma('nuestroCompromiso',[$key])}}">
                                    <div class="image"><img src="{{asset($imgSlider)}}"></div>
                                    <div class="text">{!! trans('front.paginas.home.compromiso.slider.'.$key) !!}</div>
                                </a>
                            </div>
                        </div>
                        @php
                            $i = $i + 1;
                            switch ($i) {
                                case 2:
                                    $imgSlider = isset($data['nuestroCompromiso']['calidad']) ? 'uploads/nuestro_compromiso/' .$data['nuestroCompromiso']['calidad'] : 'img/calidad.jpg';
                                    break;
                                case 3:
                                    $imgSlider = isset($data['nuestroCompromiso']['viticula']) ? 'uploads/nuestro_compromiso/' .$data['nuestroCompromiso']['viticula'] : 'img/regen.jpg';
                                    //$imgSlider = 'regen.jpg';
                                    break;
                                case 4:
                                    $imgSlider = isset($data['nuestroCompromiso']['gente']) ? 'uploads/nuestro_compromiso/' .$data['nuestroCompromiso']['gente'] : 'img/regen.jpg';
                                    //$imgSlider = 'nuestra-gente.jpg';
                                    break;
                            }
                        @endphp
                    @endforeach

					<!--div>
						<div class="wrap-slide">
							<a href="{{routeIdioma('nuestroCompromiso',['calidad'])}}/#calidad">
								<div class="image"><img src="{{asset('img/calidad.jpg')}}"></div>
								<div class="text">{!! trans('front.paginas.home.compromiso.slider.2') !!}</div>
							</a>
						</div>
					</div>

					<div>
						<div class="wrap-slide">
							<a href="{{routeIdioma('nuestroCompromiso',['viticultura'])}}/#calidad">
								<div class="image"><img src="{{asset('img/regen.jpg')}}"></div>
								<div class="text">{!! trans('front.paginas.home.compromiso.slider.3') !!}</div>
							</a>
						</div>
					</div>

					<div>
						<div class="wrap-slide">
							<a href="{{routeIdioma('nuestroCompromiso',['nuestra-gente'])}}/#calidad">
								<div class="image"><img src="{{asset('img/nuestra-gente.jpg')}}"></div>
								<div class="text">{!! trans('front.paginas.home.compromiso.slider.4') !!}</div>
							</a>
						</div>
					</div-->
				</div>
			</div>
		</div>

	</div>
</section>

<section class="section-colecciones pb-0">
	<div class="container ">
		<div class="row">
			<div class="col-md-12 mx-auto col-img ">
				<!--img src="{{asset('img/colecciones.jpg')}}" class="img-fluid " /-->
                <div class="wrap-text fade_JS">
                    <h2 style="color: #fff;">{!! trans('front.paginas.home.colecciones.titulo') !!}</h2>
                    <p style="color: #fff;">{!! trans('front.paginas.home.colecciones.subtitulo') !!}</p>
                    <a href="{{routeIdioma('colecciones')}}" class="btn-primary">{!! trans('front.paginas.home.colecciones.btn') !!}</a>
				</div>
			</div>
			<!--div class="col-md-5  bg-green col-text">
				<div class="wrap-text fade_JS">
                    <h2>{!! trans('front.paginas.home.colecciones.titulo') !!}</h2>
                    <p>{!! trans('front.paginas.home.colecciones.subtitulo') !!}</p>
                    <a href="{{routeIdioma('colecciones')}}" class="btn-primary">{!! trans('front.paginas.home.colecciones.btn') !!}</a>
				</div>
			</div-->
		</div>
	</div>
</section>

<!--section class="section-full-image m-0" >
	<div class="bg-content w-100 h-100 " style="background-image: url({{asset('img/full-image.jpg')}});">
	<div class="container " >
		<div class="row">

			<div class="col-md-5 col-text">
				<div class="wrap-text fade_JS">
					<h2>{!! trans('front.paginas.home.tucci.titulo') !!}</h2>
					<p>{!! trans('front.paginas.home.tucci.subtitulo') !!}</p>
					<a href="{{routeIdioma('francescaTucci')}}" class="btn-primary">{!! trans('front.paginas.home.tucci.btn1') !!}</a>
					<a href="{{routeIdioma('colecciones.tucci')}}" class="btn-primary">{!! trans('front.paginas.home.tucci.btn2') !!}</a>
				</div>
			</div>
		</div>
	</div>
	</div>
</section-->

@if (isset($data['novedades']) && count($data['novedades']) > 0)
	<section class="section-novedades home" >
		<div class="container">
			<div class="row">
                <div class="col-lg-12">
                    <div class="wrap-text fade_JS">
                            <h2 class="text-center text-uppercase">{!! trans('front.paginas.home.novedades.titulo') !!}</h2>

                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="grid-novedades">
                        @if(isset($data['novedades']['SD']))
                            @foreach (array_slice($data['novedades']['SD'],0,2) as $i => $item)
                                <a href="{{routeIdioma('novedades')}}" class="item item-{{($i+1)}}">
                                    <img src="{{$item['foto_url']}}">
                                    <div class="info fade_JS">
                                        <span class="date">{{$item['fecha_corta']}}</span>
                                        <h3>{{$item['titulo']}}</h3>
                                        <p style="text-decoration: none!important;">{!!$item['bajada']!!}</p>
                                    </div>
                                </a>
                            @endforeach
                        @endif
                        @if(isset($data['novedades']['SI']))
                            @foreach (array_slice($data['novedades']['SI'],0,4) as $i => $item)
                                <a href="{{routeIdioma('novedades',[$item['id']])}}" class="item item-1">
                                    <img src="{{$item['foto_url']}}">
                                    <div class="info fade_JS">
                                        <span class="date">{{$item['fecha_corta']}}</span>
                                        <h3>{{$item['titulo']}}</h3>
                                        <p>{!!$item['bajada']!!}</p>
                                    </div>
                                </a>
                            @endforeach
                        @endif

                    </div>

                </div>

                <div class="col-lg-12">
                    <div class="wrap-text fade_JS">

                            <a href="{{routeIdioma('novedades')}}" class="btn-primary m-auto d-table">{!! trans('front.paginas.home.novedades.btn') !!}</a>
                    </div>
                </div>

		    </div>
		</div>
	</section>
@endif

<!-- EDN CONTENT -->
@endsection
