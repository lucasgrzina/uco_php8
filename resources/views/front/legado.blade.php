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

    @php
        $template = 0;
    @endphp
    @foreach ($data['items'] as $item)
        @if ($template == 0)
            <section class="section-legado bg-white">
                <div class="container">
                    <div class="row ">
                        <div class="col-lg-5 center-v fade_JS">
                            <div class="wrap-text ">
                                @if (isset($item->titulo) && $item->titulo && $item->titulo != '&nbsp;')
                                    <h2>{!!$item->titulo !!}</h2>
                                @endif
                                @if (isset($item->cuerpo) && $item->cuerpo)
                                    @if (substr($item->cuerpo,0,3) !== '<p>')
                                        <p>{!! $item->cuerpo !!}</p>
                                    @else
                                        {!! $item->cuerpo !!}
                                    @endif
                                @endif
                                @if (isset($item->boton_titulo) && $item->boton_titulo)
                                    <a href="{{$item->boton_url}}" class="btn-primary inverse">{{$item->boton_titulo}}</a>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-1  fade_JS"></div>
                        <div class="col-lg-6 col-img fade_JS">
                            @if ($item->foto)
                                <img src="{{$item->foto_url}}" class="img-fluid">
                            @endif
                        </div>
                    </div>
                </div>
            </section>
        @elseif ($template == 1)
            <section class="section-2columns-image bg-gris-claro">
                <div class="container-fluid">
                    <div class="row ">
                        <div class="col-md-6 p-0 col-img">
                            @if ($item->foto)
                                <img src="{{$item->foto_url}}">
                            @endif
                        </div>
                        <div class="col-md-6 col-text">
                            <div class="wrap-text">
                                @if (isset($item->titulo) && $item->titulo && $item->titulo != '&nbsp;')
                                    <h2>{!!$item->titulo !!}</h2>
                                @endif
                                @if (isset($item->cuerpo) && $item->cuerpo)
                                    @if (substr($item->cuerpo,0,3) !== '<p>')
                                        <p>{!! $item->cuerpo !!}</p>
                                    @else
                                        {!! $item->cuerpo !!}
                                    @endif
                                @endif
                                @if (isset($item->boton_titulo) && $item->boton_titulo)
                                    <a href="{{$item->boton_url}}" class="btn-primary inverse">{{$item->boton_titulo}}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @else
            <section class="section-2columns-image bg-white">
                <div class="container-fluid">
                    <div class="row ">
                        <div class="col-md-6 col-text col-text-end">
                            <div class="wrap-text wrap-text-end">
                                @if (isset($item->titulo) && $item->titulo && $item->titulo != '&nbsp;')
                                    <h2>{!!$item->titulo !!}</h2>
                                @endif
                                @if (isset($item->cuerpo) && $item->cuerpo)
                                    @if (substr($item->cuerpo,0,3) !== '<p>')
                                        <p>{!! $item->cuerpo !!}</p>
                                    @else
                                        {!! $item->cuerpo !!}
                                    @endif
                                @endif
                                @if (isset($item->boton_titulo) && $item->boton_titulo)
                                    <a href="{{$item->boton_url}}" class="btn-primary inverse">{{$item->boton_titulo}}</a>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 p-0 col-img">
                            @if ($item->foto)
                                <img src="{{$item->foto_url}}">
                            @endif
                        </div>

                    </div>
                </div>
            </section>
            <!--section class="section-descubrir bg-white">
                <div class="container">
                    <div class="row ">
                        <div class="col-md-6">
                            <div class="wrap-text">
                                @if (isset($item->titulo) && $item->titulo && $item->titulo != '&nbsp;')
                                    <h2>{!!$item->titulo !!}</h2>
                                @endif
                                @if (isset($item->cuerpo) && $item->cuerpo)
                                    @if (substr($item->cuerpo,0,3) !== '<p>')
                                        <p>{!! $item->cuerpo !!}</p>
                                    @else
                                        {!! $item->cuerpo !!}
                                    @endif
                                @endif
                                @if (isset($item->boton_titulo) && $item->boton_titulo)
                                    <a href="{{$item->boton_url}}" class="btn-primary inverse">{{$item->boton_titulo}}</a>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="img2">
                                @if ($item->foto)
                                    <img src="{{$item->foto_url}}">
                                @endif
                            </div>
                        </div>
                    </div>
            </section-->
        @endif
        @php
            if ($template === 2) {
                $template = 0;
            } else {
                $template++;
            }
        @endphp

    @endforeach

@endsection
