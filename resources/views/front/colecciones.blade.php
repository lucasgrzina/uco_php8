@extends('layouts.front')
@section('css')
@parent
<style>
.btn-aniadas.active {
    background:#cdcfad;
}
.btn-aniadas:hover {
    background:#cdcfad;

    /*border-width: 2px;*/
}

</style>
@endsection
@section('scripts')
@parent
<script type="text/javascript">
    var _data = {!! json_encode($data) !!};
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

    _methods.cantMaxima = function(item) {
        return item.stock <= 18 ? item.stock : 18;
    }

    _methods.checkCantidad = function(item) {
        console.debug('checkCantidad');
        var cantMax = this.cantMaxima(item);
        console.debug([this.carrito.item.cantidad,cantMax]);
        if (this.carrito.item.cantidad > cantMax) {
            this.carrito.item.cantidad = cantMax;
        }
        if (this.carrito.item.cantidad == '' || parseInt(this.carrito.item.cantidad) < 1){
            this.carrito.item.cantidad = 0;
        }

    }

    _methods.ultUnidadesMsg = function() {
        return this.aniadaActual.stock == 0 ? "{{trans('front.paginas.colecciones.interna.sinStock')}}" : "{{trans('front.paginas.colecciones.interna.ultUnidades')}}".replace('_CANT_',this.aniadaActual.stock);
    }

    this._mounted.push(function(_this) {
        setTimeout(function() {
            var access = document.getElementById("section-colecciones");
            access.scrollIntoView();

        }, 500);
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
                <nav class="navbar navbar-expand-md navbar-colecciones">
                    <div class="container-fluid">
                        <button style="margin-left: 0; margin-right: auto;" class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navColecciones" aria-controls="navColecciones" aria-expanded="false" aria-label="Toggle navigation">
                           <div id="nav-lines">
                              <svg viewbox="0 0 64 64">


                                <line x1="15" x2="15" y1="16" y2="45" class="nav-line" />
                                <line x1="0" x2="30" y1="30" y2="30" class="nav-line" />


                                <line x1="16" x2="48" y1="16" y2="48" class="cross-line" />
                                <line x1="16" x2="48" y1="48" y2="16" class="cross-line" />


                                <rect class="rect" width="42" height="42" x="11" y="11" />
                              </svg>
                            </div>
                        </button>
                        <div class="offcanvas offcanvas-start offcanvas-md" tabindex="-1" id="navColecciones" aria-labelledby="offcanvasDarkNavbarLabel">
                            <div class="offcanvas-body">
                                <ul class="navbar-nav">
                                    @foreach ($data['vinos'] as $item)
                                    <li class="nav-item">
                                        <a class="nav-link {{$data['actual']->id == $item->id ? 'active' : ''}}" aria-current="page" href="{{routeIdioma('colecciones.'.$data['routePrefix'],[$item->id,\Str::slug($item->titulo)])}}">{{$item->titulo}}</a>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
            @if ($actual)

            <div class="col-12">
                <div class="producto">
                    <div class="wrapper">
                        <div class="producto-item">
                            <div class="row">
                                <div class="col-12">
                                    <div class="image slider-product">
                                        <div><img src="{{$actual->imagen_url}}" /></div>

                                        @foreach($actual->imagenes as $img)
                                            <div><img src="{{$img->filename_url}}" /></div>
                                        @endforeach


                                    </div>
                                </div>

                                <div class="col-12 d-none d-sm-block">
                                    <div class="image slider-product-nav">
                                        <div>
                                            <div class="box-thumb">
                                                <img src="{{$actual->imagen_url}}" />
                                            </div>
                                        </div>
                                        @foreach($actual->imagenes as $img)
                                        <div>
                                            <div class="box-thumb">

                                                <img src="{{$img->filename_url}}" />

                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>


                            <div class="info">
                                <div class="titulo">
                                    <h1 class="text-uppercase">{{$actual->titulo}}</h1>
                                </div>
                                <div class="w-100">
                                    <button v-for="aniada in actual.aniadas"
                                            @click="cambiarAniada(actual.id, aniada.id)"
                                            class="btn btn-secondary btn-aniadas"
                                            :class="{ 'active': aniada.id === aniadaActual.id }"
                                            type="button">
                                        <span>(% aniada.anio %)</span>
                                    </button>
                                </div>

                                <div class="links-producto" style="padding-bottom: 0; margin-top: 15px; justify-content: flex-start;  gap: 11px;">
                                    <div class="total" style="font-size: 24px; font-weight: 600;">  (% locale == 'es' ? 'AR$ ' + aniadaActual.precio_pesos : 'AR$ ' + aniadaActual.precio_pesos %) </div>
                                    <div v-show="aniadaActual.stock <= 10">
                                        <div class="btn label-stock">
                                          <span class="text-uppercase">(% ultUnidadesMsg() %)</span>
                                        </div><!-- Nuevo label Agotado -->
                                    </div>
                                </div>

                                <div class="links-producto" v-if="aniadaActual.stock > 0" style="padding-bottom: 0; margin-top: 10px;">

                                    <a href="https://www.mercadopago.com.ar/ayuda/19301" target="blank">
                                        <div class="label-info" >
                                          <span >MEDIOS DE PAGO</span>
                                        </div>
                                    </a>


                                </div>

                                <div class="links-producto row" style="margin-top: 15px; align-items: baseline;">
                                  <div class="col-12 col-md-12">
                                    <div class="d-flex mb-3 flex-wrap gap-3">
                                        <div class="input-cantidad" v-if="aniadaActual.stock > 0">
                                            <input type="number" placeholder="1" :min="1" :max="cantMaxima(aniadaActual)" v-model="carrito.item.cantidad" @blur="checkCantidad(aniadaActual)">
                                        </div>

                                        <div class="shop" v-if="aniadaActual.stock > 0">
                                            <a href="javascript:void(0)" class="btn btn-brown m-0 mr-2" style="text-transform: uppercase;font-weight: bold;" @click="carritoAgregarItem()">{!!trans('front.paginas.colecciones.interna.btnAgregar')!!}</a>
                                        </div>
                                        <div class="shop">
                                            <a :href="aniadaActual.ficha_url" class="label-info mb-3 btn-ficha d-inline-block" target="_blank"  v-if="aniadaActual.ficha">
                                                <span>{{trans('front.paginas.colecciones.interna.fichaTecnica')}}</span>
                                            </a>
                                        </div>

                                    </div>

                                  </div>
                                  <template  v-if="actual.vendible && aniadaActual">
                                    <!--div class="col-12" v-if="aniadaActual.stock <= 10 && aniadaActual.stock > 0">
                                        <div class="bajada">

                                                <p  class="destacado mb-3" style="font-weight: bolder;">
                                                    (% ultUnidadesMsg() %)
                                                </p>
                                                <p v-if="aniadaActual.stock < 1" class="destacado mb-3" style="font-weight: bolder;">
                                                    {!! trans('front.paginas.colecciones.interna.sinStock') !!}
                                                </p>

                                            <!p>{!!trans('front.paginas.colecciones.interna.porCantidades')!!}</p>
                                        </div>
                                    </div-->
                                  </template>
                                </div>


                          <div class="descripcion"><p>(% aniadaActual ? aniadaActual.descripcion : '' %)</p></div>

                          <!--div class="links-producto" v-if="aniadaActual">

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
                        </div-->

                        <div v-if="actual.vendible" class="shop">
                            <p class="destacado mb-3">
                                {!! str_replace('_COMPRAS_SUPERIORES_',$data['configuraciones']['COMPRAS_SUPERIORES'],trans('front.paginas.colecciones.interna.porCompras')) !!}
                            </p>
                          <div class="bajada">
                              <p>{!!trans('front.paginas.colecciones.interna.porCantidades')!!}</p>
                          </div>


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
