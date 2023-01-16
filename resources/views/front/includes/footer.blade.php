@include('front.modules.modal-politicas')
@include('front.modules.cart')
@include('front.modules.user')
<section class="section-subscribirse">
  <div class="container">
      <div class="row">
          <div class="col-12 fade_JS">
              <h2>{{trans('front.modulos.suscripcion.titulo')}}</h2>
              <form>
                  <div class="row-form">
                        <input type="text" v-model="newsletter.form.email" name="email" placeholder="{{trans('front.modulos.suscripcion.placeholderEmail')}}">
                        <button type="button" class="btn-primary" @click="newsletterSubmit()" :disabled="newsletter.loading">
                        {{trans('front.modulos.suscripcion.btn')}}
                        </button>
                  </div>
                  <div class="form-check w-100">
                    <label class="form-check-label" for="acepto">
                      <input class="form-check-input" v-model="newsletter.form.acepto" type="checkbox" name="acepto" id="acepto" :value="true">
                      <span class="checkmark"></span>
                        &nbsp;
                    </label>
                    <span class="lblmin">{!! str_replace('_link_tyc_',routeIdioma('terminosCondiciones'),trans('front.modulos.suscripcion.chk')) !!}</span>
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
                  <img src="{{asset('img/logo.svg')}}">
              </a>
          </div>
          <div class="col-12">
              <div class="social-links" style="max-width: 130px;">
                  <a href="https://www.facebook.com/magiadeluco" target="_blank"><i class="fa-brands fa-facebook-f"></i></a>
                  <a href="https://www.instagram.com/magiadeluco/" target="_blank"><i class="fa-brands fa-instagram"></i></a>
                  <!-- <a href="#"><i class="fa-brands fa-twitter"></i></a> -->
              </div>
          </div>
          <div class="col-12">
              <div class="site-links">
                  <a href="{{routeIdioma('home')}}">{{trans('front.navMenuFooter.links.home')}}</a>
                  <a href="{{routeIdioma('colecciones')}}">{{trans('front.navMenuFooter.links.colecciones')}}</a>
                  <a href="{{routeIdioma('francescaTucci')}}">{{trans('front.navMenuFooter.links.francescaTucci')}}</a>
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
                <a href="{{routeIdioma('home')}}">{{trans('front.navMenuFooter.links.lcr')}}</a>
                <a href="{{routeIdioma('home')}}">{{trans('front.navMenuFooter.links.dc')}}</a>
              </div>
          </div>
      </div>
  </div>

</footer>
