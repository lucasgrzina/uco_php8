@include('front.modules.modal-politicas')
@include('front.modules.cart')
@include('front.modules.user')
<section class="section-subscribirse">
  <div class="container">
      <div class="row">
          <div class="col-12 col-sm-10 col-md-8 mx-auto fade_JS">
              <h2>{{trans('front.modulos.suscripcion.titulo')}}</h2>
              <form >
                  <div class="row">
                    <div class="col-12">
                      <label for="input_nombre">{{trans('front.modulos.suscripcion.labelNombre')}}</label>
                      <input id="input_nombre" type="text" v-model="newsletter.form.nombre" name="nombre" v-on:keydown.enter.prevent='newsletterSubmit()' placeholder="{{trans('front.modulos.suscripcion.placeholderNombre')}}">
                    </div>

                    <div class="col-12">
                      <label for="input_apellido">{{trans('front.modulos.suscripcion.labelApellido')}}</label>
                      <input id="input_apellido" type="text" v-model="newsletter.form.apellido" name="apellido" v-on:keydown.enter.prevent='newsletterSubmit()' placeholder="{{trans('front.modulos.suscripcion.placeholderApellido')}}">
                    </div>

                    <div class="col-12">
                      <label for="input_email">{{trans('front.modulos.suscripcion.labelEmail')}}</label>
                      <input id="input_email" type="text" v-model="newsletter.form.email" name="email" v-on:keydown.enter.prevent='newsletterSubmit()' placeholder="{{trans('front.modulos.suscripcion.placeholderEmail')}}">
                    </div>
                  </div>
                  <div class="row-form">

                        <div class="form-check w-100">
                          <label class="form-check-label" for="acepto" style="z-index:999;">
                            <input class="form-check-input" v-model="newsletter.form.acepto" type="checkbox" name="acepto" id="acepto" :value="true">
                            <span class="checkmark"></span>
                              &nbsp;
                          </label>
                          <span class="lblmin">{!! str_replace('_link_tyc_',routeIdioma('terminosCondiciones'),trans('front.modulos.suscripcion.chk')) !!}</span>
                        </div>
                        <div class="form-check w-100">
                            <label class="form-check-label" for="recibir_info" style="z-index:999;">
                              <input class="form-check-input" v-model="newsletter.form.recibir_info" type="checkbox" name="recibir_info" id="recibir_info" :value="true">
                              <span class="checkmark"></span>
                                &nbsp;
                            </label>
                            <span class="lblmin">{!! trans('front.paginas.contacto.form.recibir') !!}</span>
                          </div>
                        <button type="button" class="btn-primary" @click="newsletterSubmit()" :disabled="newsletter.loading">
                        {{trans('front.modulos.suscripcion.btn')}}
                        </button>
                  </div>

              </form>
          </div>
      </div>
  </div>
</section>
<footer>
  <div class="container">
      <div class="row">
          <div class="col-12">
              <a href="#" class="logo">
                  <img src="{{asset('img/logo.png')}}">
              </a>
          </div>
          <div class="col-12">
              <div class="social-links" style="max-width: 130px;">
                  <a href="https://www.facebook.com/magiadeuco" target="_blank"><i class="fa-brands fa-facebook-f"></i></a>
                  <a href="https://www.instagram.com/magiadeuco/" target="_blank"><i class="fa-brands fa-instagram"></i></a>
                  <a href="https://www.instagram.com/magiadeuco/" target="_blank"><i class="fa-brands fa-linkedin"></i></a>
              </div>
          </div>
          <div class="col-12">
              <div class="site-links">
                  <a href="{{routeIdioma('home')}}">{{trans('front.navMenuFooter.links.home')}}</a>
                  <a href="{{routeIdioma('colecciones')}}">{{trans('front.navMenuFooter.links.colecciones')}}</a>
                  <!--a href="{{routeIdioma('francescaTucci')}}">{{trans('front.navMenuFooter.links.francescaTucci')}}</a-->
                  <a href="{{routeIdioma('novedades')}}">{{trans('front.navMenuFooter.links.novedades')}}</a>
                  <a href="{{routeIdioma('nuestroCompromiso')}}">{{trans('front.navMenuFooter.links.nuestroCompromiso')}}</a>
                  <a href="{{routeIdioma('legado')}}">{{trans('front.navMenuFooter.links.legado')}}</a>
                  <a href="{{routeIdioma('contacto')}}">{{trans('front.navMenuFooter.links.contacto')}}</a>
              </div>
          </div>

          <div class="col-12">
              <img class="firulete" src="{{asset('img/firulete-2.svg')}}">
          </div>
          <div class="col-12"><p class="legales">{{trans('front.navMenuFooter.legales')}}</p></div>
          <div class="col-12">
              <div class="legales-links">
                <a target="_blank" href="{{routeIdioma('politicasPrivacidad')}}">{{trans('front.navMenuFooter.links.pp')}}</a>
                <a target="_blank" href="{{routeIdioma('terminosCondiciones')}}">{{trans('front.navMenuFooter.links.tyc')}}</a>
                <a target="_blank" href="https://www.argentina.gob.ar/normativa/nacional/ley-24788-42480">{{trans('front.navMenuFooter.links.lcr')}}</a>
                <a target="_blank" href="https://www.argentina.gob.ar/normativa/nacional/ley-24240-638/actualizacion">{{trans('front.navMenuFooter.links.dc')}}</a>
              </div>
          </div>
      </div>
  </div>

</footer>
