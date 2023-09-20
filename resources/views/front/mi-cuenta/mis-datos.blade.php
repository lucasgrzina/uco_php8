@extends('layouts.front')
@section('scripts')
    @parent
    <script type="text/javascript">
 		var _data = {!! json_encode($data) !!};

		 _methods.registroSubmit = function() {
			var _this = this;
			var _registro = _this.registro;
			var scope = 'frm-registro';
			var _errorMsg = null;

			//_registro.enviado = true;
			this.$validator.validateAll(scope).then(function() {

				_registro.enviando = true;
				_this._call(_registro.url_post,'POST',_registro.form).then(function(data) {
					_registro.enviando = false;
					_registro.enviado = true;
					document.location = '{{routeIdioma('home')}}';
				}, function(error) {
					if (error.status != 422) {
						_this.toastError(error.statusText);
					} else {
						var mensaje = [];
						_.forEach(error.fields, function(msj,campo) {
							mensaje.push(msj[0]);
						});
						_this.toastError(mensaje[0]);
					}
					_registro.enviando = false;
				});

			}).catch(function(e) {
				_registro.enviando = false;
			});
		};


        this._mounted.push(function(_this) {
            console.debug(_this);
        });

        var registroPage = true;
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
                <div class="wraper">
                    <a class="brand"><img src="{{asset('img/logo.png')}}" /></a>
                    <h2 class="text-center titulo">{{$data['registro']['titulo']}}</h2>

                    <form v-on:submit.prevent="registroSubmit()" data-vv-scope="frm-registro">
                        <template v-if="registro.vista == 'datos'">
                            <!-- Email input -->
                            <div class="form-floating">
                                <input type="text" class="form-control" id="usuarioInput" placeholder="{!! trans('front.paginas.registro.form.usuario') !!}" required autofocus v-model="registro.form.usuario"/>
                                <label for="usuarioInput">{!! trans('front.paginas.registro.form.usuario') !!}*</label>
                            </div>
                            <div class="form-floating mb-form">
                                <input type="email" class="form-control" id="emailInput" placeholder="{!! trans('front.paginas.registro.form.email') !!}*" required v-model="registro.form.email"/>
                                <label for="emailInput">{!! trans('front.paginas.registro.form.email') !!}*</label>
                            </div>
                        </template>
                        <template v-if="registro.vista == 'password'">
                            <!-- Password input -->
                            <div class="form-floating">
                                <input type="password" class="form-control" id="passwordInput" placeholder="{!! trans('front.paginas.registro.form.password') !!}" required v-model="registro.form.password"/>
                                <label for="passwordInput">{!! trans('front.paginas.registro.form.password') !!}*</label>
                            </div>

                            <div class="form-floating">
                                <input type="password" class="form-control" id="confirmarPasswordInput" placeholder="{!! trans('front.paginas.registro.form.confirmarPassword') !!}"  required v-model="registro.form.password_confirmation"/>
                                <label for="confirmarPasswordInput">{!! trans('front.paginas.registro.form.confirmarPassword') !!}*</label>
                            </div>
                        </template>
                        <div class="text-center text-lg-start mt-4">
                            <button type="submit" :disabled="registro.enviando" class="btn btn-registro w-100" style="padding-left: 2.5rem; padding-right: 2.5rem;">
								{!! trans('front.paginas.miCuenta.misDatos.btnGuardar') !!}
							</button>
                            <p class="small mt-2 pt-1 mb-0 text-center">
                            <a v-if="registro.vista == 'datos'" href="{{routeIdioma('miCuenta.cambiarPassword')}}" class=" mt-2">{{trans('front.navMenuFooter.links.menuUsuario.cambiarPassword')}}</a>
                            <a v-else href="{{routeIdioma('miCuenta.misDatos')}}" class="mt-2">{{trans('front.navMenuFooter.links.menuUsuario.miCuenta')}}</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
