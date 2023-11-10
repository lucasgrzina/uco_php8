@extends('layouts.front')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.3/build/css/intlTelInput.css">
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.3/build/js/intlTelInput.js"></script>
@section('css')
    @parent

	<!--link href="{asset('css/home.css')}" rel="stylesheet" /-->
@endsection
@section('scripts')
    @parent
    <script type="text/javascript">
        var _data = {!! json_encode($data) !!};
		//Vue.use(window['vue-tel-input']);
        _methods.contactoSubmit = function (scope){
            var _this = this;
            _this.form.submitted = true;
            _this.loading = true;

            _this._call(_this.url_post_save,'POST',_this.form).then(function(data) {
                //_this.alertShow('Gracias por suscribirte');
                alert2(_this.trans.paginas.contacto.gracias);
                _this.loading = false;
            }, function(error) {
                if (error.status === 422) {
                    for(var key in error.fields) {
                        alert2(error.fields[key][0]);
                        break;
                    }
                } else {
                    alert2(error.message);
                }
                _this.loading = false;
            });

        }

		_methods.alCambiarPais = function (e) {
			console.debug(e);
		}
        this._mounted.push(function(_this) {
        });
    </script>

    <script type="text/javascript">
		$(function() {
			var input = document.querySelector("#phone");
			var iti = window.intlTelInput(input, {
				hiddenInput: "tel_prefijo",
				separateDialCode: true,
				initialCountry: 'ar',
				utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.3/build/js/utils.js",
			});

			// store the instance variable so we can access it in the console e.g. window.iti.getNumber()
			window.iti = iti;

		})
        //console.debug(window.iti);


    </script>
@endsection
@section('content')
<!-- CONTENT -->
@include('front.modules.module-full-slider',['items' => $data['slides']])
<section class="section-contacto-map bg-gris-claro">
	<div class="container">
		<div class="row ">
			<div class="col-lg-6  fade_JS col-text">
				<div class="wrap-text ">
					<h2>{!! trans('front.paginas.contacto.titulo') !!}</h2>
					<p>{!! trans('front.paginas.contacto.subtitulo') !!}</p>
				</div>
			</div>

			<div class="col-lg-6 col-map fade_JS">
				<h2>{!! trans('front.paginas.contacto.donde') !!}</h2>
				<div class="map"><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3322.9070125035905!2d-69.19128188484052!3d-33.60771708072918!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x967c3a1f7968f645%3A0xa442f1d32cd4415b!2sRuta%20Provincial%2094%20%26%20Clodomiro%20Silva%2C%20Mendoza!5e0!3m2!1sen!2sar!4v1666896168565!5m2!1sen!2sar" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe></div>
			</div>

		</div>

	</div>
</section>

<section class="section-form bg-white">
	<div class="container">
		<div class="row ">
			<div class="col-md-12">
				<h2>{!! trans('front.paginas.contacto.contactanos') !!}</h2>
				<form>
					<div class="form-floating  mb-form">
						<input type="text" class="form-control" id="nombreInput" placeholder="{!! trans('front.paginas.contacto.form.placeholderNombre') !!}" v-model="form.nombre" required>
						<label for="nombreInput">{!! trans('front.paginas.contacto.form.nombre') !!}</label>
					</div>
					<div class="form-floating  mb-form">
						<input type="text" class="form-control" id="apellidoInput" placeholder="{!! trans('front.paginas.contacto.form.placeholderApellido') !!}" v-model="form.apellido" required>
						<label for="apellidoInput">{!! trans('front.paginas.contacto.form.apellido') !!}</label>
					</div>
					<div class="form-floating  mb-form">
						<input type="email" class="form-control" id="emailInput" placeholder="{!! trans('front.paginas.contacto.form.placeholderEmail') !!}" v-model="form.email" required>
						<label for="emailInput">{!! trans('front.paginas.contacto.form.email') !!}</label>
					</div>

					<div class="form-floating  mb-form">
						<input type="text" class="form-control" id="paisInput" placeholder="{!! trans('front.paginas.contacto.form.placeholderPais') !!}" v-model="form.pais" required>
						<label for="emailInput">{!! trans('front.paginas.contacto.form.pais') !!}</label>
					</div>

					<div class="input-group  mb-form">
                        <input type="hidden" name="tel_prefijo" id="tel_prefijo" v-model="form.tel_prefijo">
						<input type="telefono" id="phone" class="form-control" iplaceholder="" aria-label="Example text with button addon" aria-describedby="button-addon1">
						<!--vue-tel-input v-model="form.tel_numero" class="form-control"></vue-tel-input-->
					</div>

					<div class="form-floating mb-form">
						<textarea class="form-control" placeholder="{!! trans('front.paginas.contacto.form.placeholderMensaje') !!}" id="floatingTextarea2" style="height: 100px" v-model="form.mensaje"></textarea>
						<label for="floatingTextarea2">{!! trans('front.paginas.contacto.form.mensaje') !!}</label>
					</div>

					<p class="mb-form">{!! trans('front.paginas.contacto.obligatorios') !!}</p>

					<div class="form-check " style="margin-bottom: 10px;">
						<label class="container">
							<input type="checkbox" checked="checked" name="terminos-condiciones" v-model="form.acepto" :value="true">
							<span class="checkmark"></span>
						</label>
                        <span class="lbl">{!! str_replace('_link_tyc_',routeIdioma('terminosCondiciones'),str_replace('_link_pp_',routeIdioma('politicasPrivacidad'),trans('front.paginas.contacto.form.acepto'))) !!}</span>
					</div>

					<div class="form-check mb-form">
						<label class="container">
							<input type="checkbox" checked="checked" name="recibir-informacion" v-model="form.recibir_info" :value="true">
							<span class="checkmark"></span>
						</label>
                        <span class="lbl">{!! trans('front.paginas.contacto.form.recibir') !!}</span>
					</div>


					<p class="mb-form">{!! str_replace('_link_pp_',routeIdioma('politicasPrivacidad'),trans('front.paginas.contacto.disclaimer')) !!}</p>

					<button type="button" class="btn btn-form" @click="contactoSubmit()" :disabled="form.loading">{!! trans('front.paginas.contacto.btn') !!}</button>
				</form>
			</div>

		</div>
	</div>
</section>

<!-- EDN CONTENT -->
@endsection
