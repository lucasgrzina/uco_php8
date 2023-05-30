//var MODAL = VueStrap.modal;
//var TAB = VueStrap.tab;
//var TABS = VueStrap.tabs;
//var ALERT = VueStrap.alert;
//var DROPDOWN = VueStrap.dropdown;
//var TYPEAHEAD = VueStrap.typeahead;
//var AWESOMESWIPER = VueAwesomeSwiper;

//Vue.component('alert',ALERT);
//Vue.component('modal',MODAL);
//Vue.component('tab',TAB);
//Vue.component('tabs',TABS);
//Vue.component('dropdown',DROPDOWN);
//Vue.component('typeahead',TYPEAHEAD);

//Vue.directive('tooltip', VTooltip.VTooltip);

//Vue.use(AWESOMESWIPER);

_methods.inputFile = function(newFile, oldFile,auto,onSuccess, onError,ref) {
  // Automatic upload
  var _this = this;
  var _ref = typeof ref !== 'undefined' ? ref : 'upload';
  var _auto = typeof auto !== 'undefined' ? auto : false;


  if (Boolean(newFile) !== Boolean(oldFile) || oldFile.error !== newFile.error) {
    if (_auto && !this.$refs[_ref].active) {
      this.$refs[_ref].active = true;
    }
  }

  if (newFile && oldFile) {
    if (newFile.success) {
      this.$refs[_ref].clear();
      onSuccess(newFile);
    }
    if (newFile.error) {
        onError(newFile);
    }
  }
}

if (typeof _dictionary !== 'undefined') {
    Vue.use(VeeValidate, {
        locale: 'es',
        dictionary: _dictionary
    });
}
_methods.fnStrFilter = function (str,replace) {
    var _replace = replace || '';
    return (str ? str : replace);
}

function currency(value, decimals, separators) {
    decimals = decimals >= 0 ? parseInt(decimals, 0) : 2;
    separators = separators || ['.', "'", ','];
    var number = (parseFloat(value) || 0).toFixed(decimals);
    if (number.length <= (4 + decimals))
        return number.replace('.', separators[separators.length - 1]);
    var parts = number.split(/[-.]/);
    value = parts[parts.length > 1 ? parts.length - 2 : 0];
    var result = value.substr(value.length - 3, 3) + (parts.length > 1 ?
        separators[separators.length - 1] + parts[parts.length - 1] : '');
    var start = value.length - 6;
    var idx = 0;
    while (start > -3) {
        result = (start > 0 ? value.substr(start, 3) : value.substr(0, 3 + start)) + separators[idx] + result;
        idx = (++idx) % 2;
        start -= 3;
    }
    return (parts.length == 3 ? '-' : '') + result;
}

_methods.showAlertFreeShipping = function () {
    var _this = this;

    var _show_since = (typeof _this.configs['MONTO_AVISO_ENVIO_GRATIS'] !== 'undefined' ? Number(_this.configs['MONTO_AVISO_ENVIO_GRATIS']) : false);
    var _free_since = (typeof _this.configs['MONTO_MIN_ENVIO_GRATIS'] !== 'undefined' ? Number(_this.configs['MONTO_MIN_ENVIO_GRATIS']) : false);

    if  (_this.customer_info['type'] === 'W' && _free_since) {
        if (this.cart.total < _free_since && this.cart.total >= _show_since) {
            return _free_since;
        }
    }
    return false;
}

_methods.showAlertMinPurchaseAmount = function () {
    var _this = this;

    var _min = (typeof _this.configs['MONTO_MIN_COMPRA_B2B'] !== 'undefined' ? Number(_this.configs['MONTO_MIN_COMPRA_B2B']) : false);
    if (_this.customer_info['type'] === 'C' && this.cart.total < _min) {
        return _min;
    }
    return false;

}

_methods.showCheckoutBtn = function () {
    var _this = this;
    return !_this.showAlertMinPurchaseAmount();
}


_methods.fnGreaterZero = function (input)
{
    if (input < 0) {
        input = 0;
    }
}

_methods.showErrors = function(errors,server){
        var _msg = null;

        switch (typeof errors) {
            case 'object':
                _errors = [];
                for(var i in errors){

                    _errors.push(errors[i][0]);
                };
                _msg = _errors.join('<br>');
                break;
            case 'array':
                _msg = errors.join('<br>');
                break;
            default:
                _msg = errors;
                break;
        }

        this.alertShow(_msg);
};

_methods.toastShow = function(title,msg,type,classs) {
    var _title = (title || '');
    var _msg = (msg || '');
    var _class = (classs || 'success');
    var _type = (type || 'cart');

    this.toast.title = _title;
    this.toast.msg = _msg;
    this.toast.type = _type;
    this.toast.class = _class;
    this.toast.show = true;
}

_methods.alertShow = function(message,title) {
    if (typeof title !== 'undefined') {
        this.alert.title = title;
    }
    this.alert.body = message;
    this.alert.show = true;
};

_methods.menuOpen = function ()
{
    this.menu.open = !this.menu.open;
}

_methods.alertClose = function () {
    this.alert.show = false;
};

_methods.newsletterSubmit = function (scope){
    var _this = this;
    _this.newsletter.form.submitted = true;
    _this.newsletter.loading = true;

    _this._call(_this.newsletter.url_post_save,'POST',_this.newsletter.form).then(function(data) {
        //_this.alertShow('Gracias por suscribirte');
        alert(_this.trans.modulos.newsletter.gracias);
        _this.newsletter.loading = false;
    }, function(error) {
        if (error.status === 422) {
            for(var key in error.fields) {
                alert(error.fields[key][0]);
                break;
            }
        } else {
            alert(error.message);
        }
        _this.newsletter.loading = false;
    });

}
_methods._call = function (path,method,data) {
    var _this = this;
    var _fn = null;
    var _data = data || {};
    Vue.http.headers.common['X-CSRF-TOKEN'] = _csrfToken;


    switch (method) {
        case 'GET':
            _fn = Vue.http.get(path,_data);
            break;
        case 'POST':
            _data['_token'] = _csrfToken;
            _fn = Vue.http.post(path,_data);
            break;
        case 'PUT':
            _data['_token'] = _csrfToken;
            _fn = put(path,_data);
            break;
    }
    return new Promise(function(resolve, reject) {
        _fn.then(function(response){
            if (response.body.success) {
                resolve(response.body.data);
            } else {
                if (response.body.errors === 'LOGIN') {
                    document.location = _this.url_login;
                } else {
                    reject(response.body.errors ? response.body.errors : response.body.message);
                }

            }
        }, function(response) {
            switch (response.status) {
                case 422:
                    //Validacion de laravel
                    reject({
                        status: response.status,
                        fields: response.body.errors,
                        message: response.body.message
                    });
                    break;
                default:
                    reject(response.body);
                    break;
            }
        });
    });
}

_methods.searchSubmit = function (item) {
    if (typeof item !== 'undefined') {
        document.location = item.url;
    } else {
        document.location = _data.search.url_get_articles.concat('?search_text=').concat(_data.search.search_text);
    }
    //;
}

_methods.goTo = function (url) {
    if (url) {
        document.location = url;
    }
}

function positiveInt(evt) {
  var theEvent = evt || window.event;
  var key = theEvent.keyCode || theEvent.which;
  key = String.fromCharCode( key );
  var regex = /[0-9]/;
  if( !regex.test(key) ) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
  }
}

Vue.directive('positive-int', function (el, binding) {
    if (!(/[\d\.]+/i.test(el.value) && parseInt(el.value) > -1)) {
      var newValue = el.value.replace(/[a-zA-Z]+/ig, '');
      if (parseInt(el.value) < 0) {
        newValue = 0;
      }
      el.value = newValue;
      binding.value = el.value;
    }
});

Vue.filter('currency', function(value, decimals, separators){
    var locale = typeof window._generalData !== 'undefined' ? window._generalData.locale : 'es';

    if (typeof decimals === 'undefined') {
        decimals = 2;
    }
    if (typeof separators === 'undefined') {
        separators = ['.', "'", ','];
    }
    if (locale == 'es') {
        return 'AR$ ' + currency(value,decimals, separators);
    } else {
        return 'AR$ ' + currency(value,decimals, separators);
        // return currency(value,decimals, separators) + ' USD';
    }
});
Vue.filter('dateFormat', function (value,format_o,format_i) {
    var _format_o = format_o || 'DD/MM/YYYY';
    var _format_i = format_i || 'YYYY-MM-DD';

    if (value) {
      return moment(value,_format_i).format(_format_o);
    } else {
      return null;
    }

  });

  Vue.filter('datetimeFormat', function (value,format_o,format_i) {
    var _format_o = format_o || 'DD/MM/YYYY HH:mm';
    var _format_i = format_i || 'YYYY-MM-DD HH:mm:ss';
    if (value) {
      return moment(value,_format_i).format(_format_o);
    } else {
      return null;
    }
  });


var _fuHeader = {'X-CSRF-TOKEN': _csrfToken};
var app = new Vue({
    el: '#app',
    delimiters: ['(%', '%)'],
    data: _.assign(_generalData,_data),
    components: _components,
    methods: _methods,
    mounted: function() {
        console.debug(this);
        //this.cartGetContent();
        for(var i in _mounted) {
            _mounted[i](this);
        }

    }
});
