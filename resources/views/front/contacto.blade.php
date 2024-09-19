@extends('layouts.front')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.3/build/css/intlTelInput.css">
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.3/build/js/intlTelInput.js"></script>
@section('css')
    @parent
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css" rel="stylesheet">
    <style>
        label.focused {
            opacity: .65;
            transform: scale(.85) translateY(-.5rem) translateX(.15rem);
        }
        .dropdown-country {
            padding-top: 30px;
            display: flex;
            width: 100%;
        }
        .dropdown-country .dropdown-menu {
            height: auto;
            

        }
        .dropdown-country .dropdown-menu li .dropdown-item.active,
        .dropdown-country .dropdown-menu li .dropdown-item:active,
        .dropdown-country .dropdown-menu li .dropdown-item:hover {
            background-color: rgba(0, 0, 0, 0.05)!important;
            color: #212529!important;
        }
        .dropdown-country button{
            width: 100%;
            border: 0;
            border-bottom: 1px solid;
            border-color: #cdcfad;
            border-radius: 0;
            font-weight: 100;
        }
    </style>
@endsection
@section('scripts')
    @parent
    <script type="text/javascript">
        var _data = {!! json_encode($data) !!};
		//Vue.use(window['vue-tel-input']);
        _methods.contactoSubmit = function (scope){
            var _this = this;

            if (_this.loading) {
                return false;
            }

            _this.form.tel_prefijo = iti.getSelectedCountryData().dialCode;
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

    // Guardar la instancia para acceder a ella desde la consola
    window.iti = iti;

    // Obtener la lista de países del plugin intlTelInput
    const countryData = window.intlTelInputGlobals.getCountryData();

    // Llenar el dropdown con la lista de países
    const countryList = document.getElementById('countryList');
    countryData.forEach(country => {
        let listItem = document.createElement('li');
        listItem.innerHTML = `<div class="dropdown-item d-flex align-items-center dropdown-item-country"  data-flag="${country.iso2}" data-country="${country.name}"><span class="flag-icon flag-icon-${country.iso2} me-2"></span> ${country.name}</div>`;
        countryList.appendChild(listItem);
    });

    // Manejo de selección de país en el combo
    document.querySelectorAll('.dropdown-item').forEach(item => {
        item.addEventListener('click', function() {
            // Obtener el código de la bandera y el nombre del país seleccionados
            const flag = this.getAttribute('data-flag');
            const country = this.getAttribute('data-country');

            // Actualizar el botón con la bandera y el nombre seleccionados
            document.getElementById('selectedFlag').className = `flag-icon flag-icon-${flag} me-2`;
            document.getElementById('selectedCountry').textContent = country;

            // Cambiar el país inicial en el input de teléfono
            iti.setCountry(flag);  // Esto actualiza el país en el input de teléfono
        });
    });
});
        //console.debug(window.iti);


    </script>
    <script>

      </script>
@endsection
@section('content')
<!-- CONTENT -->
@include('front.modules.module-full-slider',['items' => $data['slides']])
<section class="section-contacto-map bg-gris-claro">
	<div class="container">
		<div class="row ">
			<div class="col-lg-12  fade_JS col-text">
				<div class="wrap-text ">
					<h2>{!! trans('front.paginas.contacto.titulo') !!}</h2>
					<p>{!! trans('front.paginas.contacto.subtitulo') !!}</p>
				</div>
			</div>

			<!--div class="col-lg-6 col-map fade_JS">
				<h2>{!! trans('front.paginas.contacto.donde') !!}</h2>
				<div class="map"><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3322.9070125035905!2d-69.19128188484052!3d-33.60771708072918!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x967c3a1f7968f645%3A0xa442f1d32cd4415b!2sRuta%20Provincial%2094%20%26%20Clodomiro%20Silva%2C%20Mendoza!5e0!3m2!1sen!2sar!4v1666896168565!5m2!1sen!2sar" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe></div>
			</div-->

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
                        <label class="focused" for="emailInput">{!! trans('front.paginas.contacto.form.pais') !!}</label>
                        <div class="dropdown dropdown-country">
                            <button class="btn btn-secondary dropdown-toggle d-flex align-items-center" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                              <span id="selectedFlag" class="flag-icon flag-icon-ar me-2"></span>
                              <span id="selectedCountry">Argentina</span>
                            </button>
                            <ul class="dropdown-menu iti__country-list" id="countryList" aria-labelledby="dropdownMenuButton" >
                               
                            </ul>
                            
                          </div>
						<!--input type="text" class="form-control" id="paisInput" placeholder="{!! trans('front.paginas.contacto.form.placeholderPais') !!}" v-model="form.pais" required-->

					</div>

                    

					<div class="input-group  mb-form">
                        <input type="hidden" name="tel_prefijo" id="tel_prefijo" v-model="form.tel_prefijo">
						<input type="telefono" id="phone" class="form-control" iplaceholder="" aria-label="Example text with button addon" aria-describedby="button-addon1" v-model="form.tel_numero">
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
					<div class="text-center">
						<button type="button" class="btn btn-primary" @click="contactoSubmit()" :disabled="form.loading">{!! trans('front.paginas.contacto.btn') !!}</button>
					</div>

				</form>
			</div>

		</div>
        <div class="row mt-5">
            <div class="col-12 fade_JS">
				<h2>{!! trans('front.paginas.contacto.donde') !!}</h2>

			</div>
            <div class="col-sm-6 col-map fade_JS">
                <div class="map"><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3322.9070125035905!2d-69.19128188484052!3d-33.60771708072918!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x967c3a1f7968f645%3A0xa442f1d32cd4415b!2sRuta%20Provincial%2094%20%26%20Clodomiro%20Silva%2C%20Mendoza!5e0!3m2!1sen!2sar!4v1666896168565!5m2!1sen!2sar" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe></div>
            </div>
        </div>
	</div>
</section>

<!-- EDN CONTENT -->
@endsection
