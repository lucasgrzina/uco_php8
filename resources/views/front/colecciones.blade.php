@extends('layouts.front')
@section('scripts')
    @parent
    <script type="text/javascript">
        var _data = {!! json_encode($data) !!};
        console.debug(_data.carrito);
        _methods.mostrarCantidad = function (item) {
            var stock = item.stock;
            return 18;
            if (stock > 0 && stock <= 18) {
                return stock;
            } else if (stock > 18) {
                return 18;
            } else {
                return 0;
            }

        }

        _methods.cambiarAniada = function (vinoId, aniadaId) {
            console.debug(vinoId,aniadaId);

            this.aniadaActual = _.find(this.actual.aniadas,{id: aniadaId});
            this.carrito.item.id = aniadaId;
            this.cambiarCantidad(0);
        }

        _methods.cambiarCantidad = function (cantidad) {

            Vue.set(this.carrito.item,'cantidad',cantidad);
            $('#span-cantidad').html(cantidad);
        }

        _methods.alCambiarCantidad = function(item, cantidad) {
            var stock = item.stock;
            var mensaje = "";
            if (cantidad > 0 && cantidad > stock) {
                if (cantidad > stock && stock == 10) {
                    mensaje = "{!! trans('front.paginas.colecciones.interna.ultUnidades') !!}";
                } else {
                    mensaje = "{!! trans('front.paginas.colecciones.interna.sinStock') !!}";
                }

            }

            if (mensaje) {
                alert2(mensaje);
                return false;
            }

            this.cambiarCantidad(cantidad);
        };

        this._mounted.push(function(_this) {
            var access = document.getElementById("section-colecciones");
            access.scrollIntoView();
            if (_this.aniadaActual) {
                _this.cambiarAniada(_this.actual.id, _this.aniadaActual.id);
            }

        });
    </script>
@endsection
@php
    $actual = $data['actual'];
@endphp
@section('content')
@include('front.modules.module-full-slider',['items' => $data['slides']])
<section class="section-colecciones {{$dataSection}} interna bg-gris-claro"  id="section-colecciones">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="navbar navbar-expand navbar-colecciones">
                    <div class="container-fluid">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navColecciones" aria-controls="navColecciones" aria-expanded="false" aria-label="Toggle navigation">
                            {{ trans('front.paginas.colecciones.parcelas') }} +
                        </button>
                        <div class="collapse navbar-collapse" id="navColecciones">
                            <ul class="navbar-nav">
                                @foreach ($data['vinos'] as $item)
                                    <li class="nav-item">
                                        <a class="nav-link {{$data['actual']->id == $item->id ? 'active' : ''}}" aria-current="page" href="{{routeIdioma('colecciones.'.$data['routePrefix'],[$item->id,\Str::slug($item->titulo)])}}">{{$item->titulo}}</a>
                                    </li>

                                @endforeach
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
            @if ($actual)

            <div class="col-12">
                <div class="producto">
                    <div class="wrapper">
                        <div class="producto-item">
                            <div class="image"><img src="{{$actual->imagen_url}}" /></div>
                            <div class="info">
                                <div class="titulo">
                                    <h1>{{$actual->titulo}}</h1>

                                    <div class="dropdown">
                                        <span class="aniadas">{{trans('front.paginas.colecciones.interna.aniadas')}}</span>
                                        <button class="btn btn-dropdown dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span v-if="aniadaActual">(% aniadaActual.anio %)</span>
                                            <span v-else>{{trans('front.paginas.colecciones.interna.aniadas')}}</span>


                                            <i class="arrow-down"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-dark">
                                            <li><a v-for="aniada in actual.aniadas" class="dropdown-item" href="javascript:void(0)" @click="cambiarAniada(actual.id,aniada.id)">(% aniada.anio %)</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="descripcion"><p>(% aniadaActual ? aniadaActual.descripcion : '' %)</p></div>
                                <div class="links-producto" v-if="aniadaActual">

                                    <a :href="aniadaActual.ficha_url" target="_blank" class="btn btn-brown" v-if="aniadaActual.ficha">{{trans('front.paginas.colecciones.interna.fichaTecnica')}}</a>
                                    <div v-if="actual.vendible" class="btn btn-brown total">
                                        (% locale == 'es' ? 'AR$ ' + aniadaActual.precio_pesos : 'AR$ ' + aniadaActual.precio_pesos %)
                                    </div>
                                    <div v-if="actual.vendible" class="dropdown">
                                        <button class="btn btn-dropdown dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span id="span-cantidad">0</span>
                                            <i class="arrow-down"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-dark" >
                                            <li v-for="cant in mostrarCantidad(aniadaActual)">
                                                <a class="dropdown-item" href="javascript:void(0);" @click="alCambiarCantidad(aniadaActual,cant)">(% cant %)</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="bajada">
                                    <template  v-if="actual.vendible && aniadaActual">
                                        <p v-if="aniadaActual.stock <= 10 && aniadaActual.stock > 0" class="destacado mb-3" style="font-weight: bolder;">
                                            {!! trans('front.paginas.colecciones.interna.ultUnidades') !!}
                                        </p>
                                        <p v-if="aniadaActual.stock < 1" class="destacado mb-3" style="font-weight: bolder;">
                                            {!! trans('front.paginas.colecciones.interna.sinStock') !!}
                                        </p>
                                    </template>
                                    <p>{!!trans('front.paginas.colecciones.interna.porCantidades')!!}</p>
                                </div>
                                <div v-if="actual.vendible" class="shop">
                                    <p class="destacado mb-3">
                                        {!! str_replace('_COMPRAS_SUPERIORES_',$data['configuraciones']['COMPRAS_SUPERIORES'],trans('front.paginas.colecciones.interna.porCompras')) !!}
                                    </p>
                                    <a href="javascript:void(0)" class="btn btn-brown" style="text-transform: uppercase;font-weight: bold;" @click="carritoAgregarItem()">{!!trans('front.paginas.colecciones.interna.btnAgregar')!!}</a>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>

@endsection
