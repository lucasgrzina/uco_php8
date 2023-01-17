_methods.toastError = function(mensaje,titulo) {
    iziToast.error({
      title: 'ERROR!',
      message: mensaje,
      position: 'topRight',
      transitionIn: 'fadeInLeft',
      transitionOut: 'fadeOut',
      transitionInMobile: 'fadeIn',
      transitionOutMobile: 'fadeOut'
    });
  };
  _methods.toastOk = function(mensaje,titulo) {
    iziToast.success({
      title: 'OK',
      message: mensaje,
      position: 'topRight',
      transitionIn: 'fadeInLeft',
      transitionOut: 'fadeOut',
      transitionInMobile: 'fadeIn',
      transitionOutMobile: 'fadeOut'
    });
  };

  _methods.carritoAgregarItem = function() {
        var _this = this;

        if (!_this.carrito.item.id) {
            alert('Debe seleccionar un producto');
            return false;
        }

        if (!_this.carrito.item.cantidad) {
            alert('Debe indicar una cantidad mayor a 0');
            return false;
        }

        _this.carrito.agregando = true;
        _this._call(_this.carrito.url_agregar,'POST',_this.carrito.item).then(function(data) {
            _this.setDataCarrito(data);
            _this.carritoMostrarModalPorTiempo();
            _this.cambiarCantidad(0);
            //_this.toastOk('La operación finalizó con éxito');
            //document.location = _this.carrito.url_index;
        }, function(error) {
            _this.toastError(error.message);
            _this.carrito.agregando = false;
        });

  }

  _methods.carritoModificarItem = function(item, cant) {
    var _this = this;
    console.debug(item);
    item.quantity = cant;

    _this.carrito.agregando = true;
    _this._call(_this.carrito.url_agregar,'POST',{
      rowId: item.id,
      cantidad: cant
    }).then(function(data) {
        _this.setDataCarrito(data);
        _this.toastOk('La operación finalizó con éxito');
        //document.location = _this.carrito.url_index;
    }, function(error) {
        _this.toastError(error.message);
        _this.carrito.agregando = false;
    });

  }

  _methods.carritoQuitarItem = function(item, index) {
    var _this = this;
    _this.carrito.quitando = true;
    _this._call(_this.carrito.url_quitar.replace('_ID_',item.id),'POST',{}).then(function(data) {
        console.debug(data);
        _this.setDataCarrito(data);
        //_this.toastOk('La operación finalizó con éxito');
        _this.carritoMostrarModalPorTiempo();
        //document.location = _this.carrito.url_index;
    }, function(error) {
        _this.toastError(error.message);
        _this.carrito.quitando = false;
    });

  }

  _methods.setDataCarrito = function (data) {
    this.$set(this.carrito,'items',data.items);
    this.$set(this.carrito,'cantidad',data.cantidad);
    this.$set(this.carrito,'total',data.total);
  };

  _methods.carritoIndex = function () {
      var _this = this;
      document.location = _this.carrito.url_index;
  }

  _methods.checkoutConfirmar = function() {
    var _this = this;

    if (_this.carrito.items.length < 1) {
        alert('Debe seleccionar un producto');
        return false;
    }

    _this.checkout.confirmando = true;
    _this._call(_this.checkout.url_confirmar,'POST',_this.checkout.form).then(function(data) {
        //_this.setDataCarrito(data);
        //_this.toastOk('La operación finalizó con éxito');
        document.location = data.redirect;
    }, function(error) {
        _this.toastError(error.message);
        _this.checkout.confirmando = false;
    });

  }

  _methods.carritoMostrarModalPorTiempo = function (tiempo) {
    var _tiempo = typeof tiempo != 'undefined' ? tiempo : 2000;
    bsMenuCart.show();
    /*setTimeout(function() {*/
      //bsMenuCart.hide();
    /*},_tiempo);*/
  }

  if (typeof loginPage !== 'undefined') {
    _methods.loginSubmit = function(scope) {
      var _this = this;
      var _login = _this.login;
      //var scope = 'frm-login';
      var _url = _login.vista === 'login' ? _login.url_post : _login.url_post_recuperar;
      var _form = _login.vista === 'login' ? _login.form : _login.formRecuperar;
      var _errorMsg = null;

      //console.debug('entraaa');
      _login.enviado = true;
      this.$validator.validateAll(scope).then(function() {

          _login.enviando = true;
          _this._call(_url,'POST',_form,true,_this.errors,scope).then(function(data) {
              console.debug(data);
              if (data.url_redirect) {
                document.location = data.url_redirect;
              } else {
                if (_login.vista === 'login') {
                  location.reload();
                } else {
                  _this.toastOk('Hemos enviado los pasos a seguir a tu correo electrónico.');
                }

              }
              _login.enviando = false;
          }, function(error) {
              if (error.status != 422) {
                _this.toastError(error.message);
              }

            _login.enviando = false;
          });

      }).catch(function(e) {
        _login.enviando = false;
      });
    };

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

  }
  if (typeof registroPage !== 'undefined') {
    _methods.registroSubmit = function() {
      var _this = this;
      var _registro = _this.registro;
      var scope = 'frm-registro';
      var _errorMsg = null;

      _registro.enviado = true;
      this.$validator.validateAll(scope).then(function() {

          _registro.enviando = true;
          _this._call(_registro.url_post,'POST',_registro.form,true,_this.errors,scope).then(function(data) {
              location.reload();
              _registro.enviando = false;
          }, function(error) {
            _registro.enviando = false;
          });

      }).catch(function(e) {
        _registro.enviando = false;
      });
    };

  }

var bsMenuCart;
var menuCart;

$(function() {
  menuCart = document.getElementById('offcanvasRight');
  bsMenuCart = new bootstrap.Offcanvas(menuCart);
});
