@if (isset($items) && count($items) > 0)
<section class="module-full-slider">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 px-0">
                <div class="slider">
                    @foreach ($items as $item)
                        @if ((isset($item->video) && $item->video) || (isset($item->video_mobile) && $item->video_mobile))
                            <div class="slide {{isset($item->slider_class) && $item->slider_class ? $item->slider_class : ''}}" style="
                                --background-desktop: url('{{assetComodin($item->imagen_desktop_url)}}');
                                --background-mobile: url('{{isset($item->imagen_mobile) && $item->imagen_mobile ? assetComodin($item->imagen_mobile_url) : assetComodin($item->imagen_desktop_url)}}')"
                                >
                        @else
                            <div class="slide {{isset($item->slider_class) && $item->slider_class ? $item->slider_class : ''}}" style="
                                --background-desktop: url('{{assetComodin($item->imagen_desktop_url)}}');
                                --background-mobile: url('{{isset($item->imagen_mobile) && $item->imagen_mobile ? assetComodin($item->imagen_mobile_url) : assetComodin($item->imagen_desktop_url)}}')"
                                >
                        @endif
                                @if((isset($item->boton_titulo) && $item->boton_titulo) || (isset($item->titulo) && $item->titulo) || (isset($item->subtitulo) && $item->subtitulo))
                                <div class="container" style="position: relative; z-index: 2;">
                                    @if(isset($item->titulo) && $item->titulo)
                                        @if (strEmpiezaCon($item->titulo,'_trans.'))
                                            <h2>{!! trans(str_replace('_trans.','',$item->titulo)) !!}</h2>
                                        @else
                                            <h2>{!! $item->titulo !!}</h2>
                                        @endif

                                    @endif
                                    @if(isset($item->subtitulo) && $item->subtitulo)
                                        @if (strEmpiezaCon($item->subtitulo,'_trans.'))
                                            <p>{!! trans(str_replace('_trans.','',$item->subtitulo)) !!}</p>
                                        @else
                                            <p>{!! $item->subtitulo !!}</p>
                                        @endif
                                    @endif
                                    @if(isset($item->boton_titulo) && $item->boton_titulo)
                                        <a href="{{$item->boton_url}}" class="btn-primary">{{$item->boton_titulo}}</a>
                                    @endif

                                </div>
                                @endif
                                @if (isset($item->video) && $item->video)
                                    <video class="{{isset($item->video_mobile) && $item->video_mobile ? 'd-none d-sm-block' : ''}}" loop autoplay muted style="position: absolute; z-index: -1;" playsinline>
                                        <source  src="{{$item->video_url}}" type="video/mp4">
                                    </video>
                                @endif
                                @if (isset($item->video_mobile) && $item->video_mobile)
                                    <video class="{{isset($item->imagen_mobile) && $item->imagen_mobile ? 'd-block d-sm-none' : ''}}" loop autoplay muted style="position: absolute; z-index: -1;" playsinline>
                                        <source  src="{{$item->video_mobile_url}}" type="video/mp4">
                                    </video>
                                @endif
                            </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endif
