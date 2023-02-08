<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header">
      <h5 id="offcanvasRightLabel"></h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="cart">
        <div class="list-cart">
          <div class="item" v-for="(item,key,index) in carrito.items">
            <div class="image"><img :src="item.attributes.imagen"></div>
            <div class="info">
               <a href="javascript:void(0)" @click="carritoQuitarItem(item,index)" class="remove"></a>
              <h1>(%item.name%)</h1>
              <div class="cantidad">
                <span>(% item.quantity %) X (% item.price | currency %)</span>

              </div>
            </div>

          </div>
        </div>

        <div class="total">
          <span>SUBTOTAL: (% carrito.total | currency %)</span>
        </div>

        <div class="actions">
          <button class="btn-white inverse" v-if="carrito.cantidad > 0" @click="goTo('{{routeIdioma('checkout')}}')">Check-out</button>
          <!--button class="btn-white" data-bs-dismiss="offcanvas">Continuar comprando</button-->
          <button class="btn-white" v-if="carrito.cantidad > 0" href="{{routeIdioma('carrito')}}" @click="goTo('{{routeIdioma('carrito')}}')">{{trans('front.paginas.carrito.editarCarrito')}}</button>
        </div>
      </div>

    </div>
  </div>
