@extends('layouts.front')
@section('scripts')
    @parent
    <script type="text/javascript">
 		var _data = {!! json_encode($data) !!};

		_methods.loginSubmit = function(scope) {
			var _this = this;
			var _login = _this.login;
			//var scope = 'frm-login';
			var _url = _login.vista === 'login' ? _login.url_post : _login.url_post_recuperar;
			var _form = _login.vista === 'login' ? _login.form : _login.formRecuperar;
			var _errorMsg = null;

			//console.debug('entraaa');

			this.$validator.validateAll(scope).then(function() {

				_login.enviando = true;
				_this._call(_url,'POST',_form).then(function(data) {
					console.debug(data);
					if (data.url_redirect) {
						document.location = data.url_redirect;
					} else {
						if (_login.vista === 'login') {
							location.reload();
						} else {
							// _this.toastOk('Hemos enviado los pasos a seguir a tu correo electrónico.');
							alert(data.message);
							_this.cambiarVista('login');
						}

					}
					_login.enviando = false;
				}, function(error) {
                    console.log(error);
					_login.enviando = false;
					if (error.status != 422) {
						//console.debug(error.data.message);
						alert(error.message);
					} else {
                        var mensaje = [];
						_.forEach(error.fields, function(msj,campo) {
							mensaje.push(msj[0]);
						});
						alert(mensaje[0]);
					}


				});

			}).catch(function(e) {
				console.debug(e.response);
				_login.enviando = false;
			});
		}

		_methods.cambiarVista = function (vista) {
			var _this = this;
			var _login = _this.login;


			_login.enviado = false;
			if (vista === 'login') {
			_login.formRecuperar.email = null;
			} else {
			_login.form.email = null;
			_login.form.password = null;
			}
			_login.vista = vista;

		};

        this._mounted.push(function(_this) {
            console.debug(_this);
        });

        var loginPage = true;
    </script>
@endsection
@section('content')
<section class="registro">
    <div class="container-fluid h-custom">
        <div class="row d-flex align-items-center h-100">
            <div class="col-lg-5 col-image">
                <img src="{{asset('img/login.jpg')}}" class="img-fluid" alt="Sample image" />
            </div>
            <div class="col-lg-7 col-form">
                <div class="wraper" v-if="login.vista === 'login'">
                    <a class="brand"><img src="{{asset('img/logo.png')}}" /></a>
                    <form v-on:submit.prevent="loginSubmit('frm-login')" data-vv-scope="frm-login">
                        <!-- Email input -->
                        <div class="form-floating mb-form">
                            <input type="text" class="form-control" id="usuarioInput" placeholder="{!! trans('front.paginas.login.form.usuario') !!}*" v-model="login.form.usuario" required/>
                            <label for="usuarioInput">{!! trans('front.paginas.login.form.usuario') !!}*</label>
                        </div>
                        <div class="form-floating mb-form">
                            <input type="password" class="form-control" id="emailInput" placeholder="{!! trans('front.paginas.login.form.password') !!}*" v-model="login.form.password" required/>
                            <label for="emailInput">{!! trans('front.paginas.login.form.password') !!}*</label>
                        </div>

                        <div class="d-flex justify-content-between small">
                            <!-- Checkbox -->


                        </div>

                        <div class="text-center text-lg-start mt-4">
                            <button type="submit" class="btn btn-registro w-100" style="padding-left: 2.5rem; padding-right: 2.5rem;" :disabled="login.enviando">
                                <svg v-if="login.enviando" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 100 100" overflow="visible" fill="#ffffff" stroke="#f2eeee"><defs><rect id="loader" x="46.5" y="40" width="7" height="20" rx="2" ry="2" transform="translate(0 -30)" /></defs><use xlink:href="#loader" transform="rotate(30 50 50)"><animate attributeName="opacity" values="0;1;0" dur="1s" begin="0.08s" repeatCount="indefinite"></animate></use><use xlink:href="#loader" transform="rotate(60 50 50)"><animate attributeName="opacity" values="0;1;0" dur="1s" begin="0.17s" repeatCount="indefinite"></animate></use><use xlink:href="#loader" transform="rotate(90 50 50)"><animate attributeName="opacity" values="0;1;0" dur="1s" begin="0.25s" repeatCount="indefinite"></animate></use><use xlink:href="#loader" transform="rotate(120 50 50)"><animate attributeName="opacity" values="0;1;0" dur="1s" begin="0.33s" repeatCount="indefinite"></animate></use><use xlink:href="#loader" transform="rotate(150 50 50)"><animate attributeName="opacity" values="0;1;0" dur="1s" begin="0.42s" repeatCount="indefinite"></animate></use><use xlink:href="#loader" transform="rotate(180 50 50)"><animate attributeName="opacity" values="0;1;0" dur="1s" begin="0.50s" repeatCount="indefinite"></animate></use><use xlink:href="#loader" transform="rotate(210 50 50)"><animate attributeName="opacity" values="0;1;0" dur="1s" begin="0.58s" repeatCount="indefinite"></animate></use><use xlink:href="#loader" transform="rotate(240 50 50)"><animate attributeName="opacity" values="0;1;0" dur="1s" begin="0.67s" repeatCount="indefinite"></animate></use><use xlink:href="#loader" transform="rotate(270 50 50)"><animate attributeName="opacity" values="0;1;0" dur="1s" begin="0.75s" repeatCount="indefinite"></animate></use><use xlink:href="#loader" transform="rotate(300 50 50)"><animate attributeName="opacity" values="0;1;0" dur="1s" begin="0.83s" repeatCount="indefinite"></animate></use><use xlink:href="#loader" transform="rotate(330 50 50)"><animate attributeName="opacity" values="0;1;0" dur="1s" begin="0.92s" repeatCount="indefinite"></animate></use><use xlink:href="#loader" transform="rotate(360 50 50)"><animate attributeName="opacity" values="0;1;0" dur="1s" begin="1.00s" repeatCount="indefinite"></animate></use></svg>
                                {!! trans('front.paginas.login.btnIngresar') !!}
                            </button>
                            <p class="small mt-2 pt-1 mb-0 text-center">
                            	<a class="d-block" href="javascript:void(0);" @click="cambiarVista('recuperar')">{!! trans('front.paginas.login.olvidaste') !!}</a>
                            	<a class="d-block" href="{{routeIdioma('registro')}}" >{!! trans('front.paginas.login.lnkRegistro') !!}</a>
                            </p>
                        </div>
                    </form>
                </div>
                <div class="wraper" v-else>
                    <a class="brand"><img src="{{asset('img/logo.png')}}" /></a>
                    <form v-on:submit.prevent="loginSubmit('frm-recuperar')" data-vv-scope="frm-recuperar">
                        <!-- Email input -->
                        <div class="form-floating mb-form">
                            <input type="text" class="form-control" id="usuarioInput" placeholder="{!! trans('front.paginas.login.recuperar.form.email') !!}*" v-model="login.formRecuperar.email" required/>
                            <label for="usuarioInput">{!! trans('front.paginas.login.recuperar.form.email') !!}*</label>
                        </div>

                        <div class="text-center text-lg-start mt-4">
                            <button type="submit" class="btn btn-registro w-100" style="padding-left: 2.5rem; padding-right: 2.5rem;" :disabled="login.enviando">
                                <svg v-if="login.enviando" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 100 100" overflow="visible" fill="#ffffff" stroke="#f2eeee"><defs><rect id="loader" x="46.5" y="40" width="7" height="20" rx="2" ry="2" transform="translate(0 -30)" /></defs><use xlink:href="#loader" transform="rotate(30 50 50)"><animate attributeName="opacity" values="0;1;0" dur="1s" begin="0.08s" repeatCount="indefinite"></animate></use><use xlink:href="#loader" transform="rotate(60 50 50)"><animate attributeName="opacity" values="0;1;0" dur="1s" begin="0.17s" repeatCount="indefinite"></animate></use><use xlink:href="#loader" transform="rotate(90 50 50)"><animate attributeName="opacity" values="0;1;0" dur="1s" begin="0.25s" repeatCount="indefinite"></animate></use><use xlink:href="#loader" transform="rotate(120 50 50)"><animate attributeName="opacity" values="0;1;0" dur="1s" begin="0.33s" repeatCount="indefinite"></animate></use><use xlink:href="#loader" transform="rotate(150 50 50)"><animate attributeName="opacity" values="0;1;0" dur="1s" begin="0.42s" repeatCount="indefinite"></animate></use><use xlink:href="#loader" transform="rotate(180 50 50)"><animate attributeName="opacity" values="0;1;0" dur="1s" begin="0.50s" repeatCount="indefinite"></animate></use><use xlink:href="#loader" transform="rotate(210 50 50)"><animate attributeName="opacity" values="0;1;0" dur="1s" begin="0.58s" repeatCount="indefinite"></animate></use><use xlink:href="#loader" transform="rotate(240 50 50)"><animate attributeName="opacity" values="0;1;0" dur="1s" begin="0.67s" repeatCount="indefinite"></animate></use><use xlink:href="#loader" transform="rotate(270 50 50)"><animate attributeName="opacity" values="0;1;0" dur="1s" begin="0.75s" repeatCount="indefinite"></animate></use><use xlink:href="#loader" transform="rotate(300 50 50)"><animate attributeName="opacity" values="0;1;0" dur="1s" begin="0.83s" repeatCount="indefinite"></animate></use><use xlink:href="#loader" transform="rotate(330 50 50)"><animate attributeName="opacity" values="0;1;0" dur="1s" begin="0.92s" repeatCount="indefinite"></animate></use><use xlink:href="#loader" transform="rotate(360 50 50)"><animate attributeName="opacity" values="0;1;0" dur="1s" begin="1.00s" repeatCount="indefinite"></animate></use></svg>
                                {!! trans('front.paginas.login.recuperar.btnIngresar') !!}
                            </button>
                            <p class="small mt-2 pt-1 mb-0 text-center">
                            	<a class="d-block" href="javascript:void(0);" @click="cambiarVista('login')">{!! trans('front.paginas.login.recuperar.acceso') !!}</a>
                            </p>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</section>

@endsection
