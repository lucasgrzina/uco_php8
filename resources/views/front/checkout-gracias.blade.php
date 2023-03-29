@extends('layouts.front')
@section('scripts')
    @parent
    <script type="text/javascript">
        var _data = {!! json_encode($data) !!};



        this._mounted.push(function(_this) {
        });
    </script>
@endsection
@php
    //$actual = $data['actual'];
@endphp
@section('content')
<section class="section-checkout" >

	<div class="container " >
		<div class="row">

			<div class="col-md-12 col-checkout-1">

				<!-- DATOS DE CONTACTO -->

				<div class="block-content">
					<h2>{{$data['checkout']['mensaje']['titulo']}}</h2>
                    <p>{!! $data['checkout']['mensaje']['texto'] !!}</p>
                    <a class="btn btn-primary btn-finalizar" href="{{routeIdioma('miCuenta.pedidos')}}">
                        {{ trans('front.paginas.checkout.gracias.btnMisPedidos') }}
                    </a>
				</div>

			</div>
		</div>
	</div>

</section>


@endsection
