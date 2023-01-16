@extends('layouts.front')
@section('css')
    @parent
	<!--link href="{asset('css/home.css')}" rel="stylesheet" /-->
@endsection
@section('scripts')
    @parent
    <script type="text/javascript">
        var _data = {!! json_encode($data) !!};
        
        _methods.verNotaCompleta = function() {
            this.novedades.notaCompleta = true;
        }

        this._mounted.push(function(_this) {
        });
    </script>
@endsection
@section('content')
@include('front.modules.module-full-slider',['items' => $data['slides']])
<section class="section-novedades big">
	<div class="container">
		<div class="row ">
			<div class="col-12 ">
				<div class="grid-novedades only-1">	
					<div class="item item-1">
						<img src="{{$data['novedades']['actual']->foto_url}}">
						<div class="info">
							<span class="date">{{formatoFechaNota($data['novedades']['actual']->fecha)}}</span>
							<h3>{{$data['novedades']['actual']->titulo}}</h3>
							<p>{{$data['novedades']['actual']->bajada}}</p>
                            {!! $data['novedades']['actual']->cuerpo !!}
							
						</div>
					</div>
				</div>
			</div>
            <!--if (count($data['novedades']['destacados']) > 0)
			<div class="col-lg-3">
				<div class="sidebar">
					<h4>Te puede interesar</h4>

					<div class="grid-novedades sidebar ">
                        @foreach ($data['novedades']['destacados'] as $item)
                            <a href="{{routeIdioma('novedades',[$item->id])}}" class="item item-1">
                                <img src="{{$item->foto_url}}">
                                <div class="info">
                                    <span class="date">{{formatoFechaNota($item->fecha)}}</span>
                                    <h3>{{$item->titulo}}</h3>
                                    <p>{{$item->bajada}}</p>
                                </div>
                            </a>                            
                        @endforeach
					</div>
				</div>
			</div>                
            endif-->

			
		</div>
        @if (count($data['novedades']['items']) > 0)
		<div class="row mt-5">
			<div class="col-12">
				<h4>{{trans('front.paginas.novedades.masRecientes')}}</h4>
				<div class="content-arrows" id="content_arrows_noticias"></div>
				<div id="slider_noticias" class="slider-noticias">
                    @foreach ($data['novedades']['items'] as $item)
                        <div>
                            <a href="{{routeIdioma('novedades',[$item->id])}}" class="item item-1">
                                <img src="{{$item->foto_url}}">
                                <div class="info">
                                    <span class="date">{{formatoFechaNota($item->fecha)}}</span>
                                    <h3>{{$item->titulo}}</h3>
                                </div>
                            </a>                            
                        </div>
                    @endforeach                    


					
						<!--a href="#" class="item item-1">
							<img src="assets/img/novedad-1.jpg">
							<div class="info">
								<span class="date">15.AGO</span>
								<h3>Lorem ipsum dolor sit amet</h3>
							</div>
						</a>
					</div>

					<div>
						<a href="#" class="item item-1">
							<img src="assets/img/novedad-1.jpg">
							<div class="info">
								<span class="date">15.AGO</span>
								<h3>Lorem ipsum dolor sit amet</h3>
							</div>
						</a>
					</div>

					<div>
						<a href="#" class="item item-1">
							<img src="assets/img/novedad-1.jpg">
							<div class="info">
								<span class="date">15.AGO</span>
								<h3>Lorem ipsum dolor sit amet</h3>
							</div>
						</a>
					</div>

					<div>
						<a href="#" class="item item-1">
							<img src="assets/img/novedad-1.jpg">
							<div class="info">
								<span class="date">15.AGO</span>
								<h3>Lorem ipsum dolor sit amet</h3>
							</div>
						</a>
					</div>

					<div>
						<a href="#" class="item item-1">
							<img src="assets/img/novedad-1.jpg">
							<div class="info">
								<span class="date">15.AGO</span>
								<h3>Lorem ipsum dolor sit amet</h3>
							</div>
						</a>
					</div>

					<div>
						<a href="#" class="item item-1">
							<img src="assets/img/novedad-1.jpg">
							<div class="info">
								<span class="date">15.AGO</span>
								<h3>Lorem ipsum dolor sit amet</h3>
							</div>
						</a>
					</div>

					<div>
						<a href="#" class="item item-1">
							<img src="assets/img/novedad-1.jpg">
							<div class="info">
								<span class="date">15.AGO</span>
								<h3>Lorem ipsum dolor sit amet</h3>
							</div>
						</a>
					</div-->
				</div>
			</div>
		</div>
        @endif
	</div>
</section>
@endsection  