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
					_login.enviando = false;
					if (error.status != 422) {
						//console.debug(error.data.message);
						alert(error.data.message);
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
			_login.tituloSeccion = 'Iniciar sesión';
			_login.subtituloSeccion = 'Completa tus datos para ingresar.';
			} else {
			_login.form.email = null;
			_login.form.password = null;
			_login.tituloSeccion = '¿Olvidaste tus datos?';
			_login.subtituloSeccion = 'Ingresa tu email y te enviaremos una nueva contraseña.';
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
                    <a class="brand"><img src="{{asset('img/logo.svg')}}" /></a>
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
                            <button type="submit" class="btn btn-registro w-100" style="padding-left: 2.5rem; padding-right: 2.5rem;" :disabled="login.enviando">{!! trans('front.paginas.login.btnIngresar') !!}</button>
                            <p class="small mt-2 pt-1 mb-0 text-center">
                            	<a class="d-block" href="javascript:void(0);" @click="cambiarVista('recuperar')">{!! trans('front.paginas.login.olvidaste') !!}</a>
                            	<a class="d-block" href="{{routeIdioma('registro')}}" >{!! trans('front.paginas.login.lnkRegistro') !!}</a>
                            </p>
                        </div>
                    </form>
                </div>
                <div class="wraper" v-else>
                    <a class="brand"><img src="{{asset('img/logo.svg')}}" /></a>
                    <form v-on:submit.prevent="loginSubmit('frm-recuperar')" data-vv-scope="frm-recuperar">
                        <!-- Email input -->
                        <div class="form-floating mb-form">
                            <input type="text" class="form-control" id="usuarioInput" placeholder="{!! trans('front.paginas.login.recuperar.form.email') !!}*" v-model="login.formRecuperar.email" required/>
                            <label for="usuarioInput">{!! trans('front.paginas.login.recuperar.form.email') !!}*</label>
                        </div>

                        <div class="text-center text-lg-start mt-4">
                            <button type="submit" class="btn btn-registro w-100" style="padding-left: 2.5rem; padding-right: 2.5rem;" :disabled="login.enviando">{!! trans('front.paginas.login.recuperar.btnIngresar') !!}</button>
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
