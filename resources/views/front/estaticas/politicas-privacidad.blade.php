@extends('layouts.front')
@section('css')
    @parent
@endsection
@section('scripts')
    @parent
    <script type="text/javascript">
        var _data = {!! json_encode($data) !!};
		//Vue.use(window['vue-tel-input']);
        this._mounted.push(function(_this) {
        });
    </script>

@endsection
@section('content')
<!-- CONTENT -->
<section class="section-form">
	<div class="container">
		<div class="row ">
			<div class="col-md-12">
				<h2>{!! trans('front.modulos.pp.titulo') !!}</h2>
				<form>
                    {!! str_replace('_link_tyc_',routeIdioma('terminosCondiciones'),trans('front.modulos.pp.contenido')) !!}
				</form>
			</div>

		</div>
	</div>
</section>

<!-- EDN CONTENT -->
@endsection
