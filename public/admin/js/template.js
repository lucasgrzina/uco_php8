//Vue.config.delimiters = ['${', '}'];
var TAB = VueStrap.tab;
var TABS = VueStrap.tabs;

var MODAL = VueStrap.modal;
var ALERT = VueStrap.alert;
var DROPDOWN = VueStrap.dropdown;
var TYPEAHEAD = VueStrap.typeahead;
//var SELECT = VueStrap.select;
//var AWESOMESWIPER = VueAwesomeSwiper;
Vue.component('tab',TAB);
Vue.component('tabs',TABS);
Vue.component('alert',ALERT);
Vue.component('modal',MODAL);
Vue.component('dropdown',DROPDOWN);
Vue.component('typeahead',TYPEAHEAD);
//Vue.component('v-select',SELECT);
//Vue.component('ToggleButton',ToggleButton);


//Vue.use(AWESOMESWIPER);
if (typeof _methods === 'undefined') {
    var _methods = {};
}
if (typeof _mounted === 'undefined') {
    var _mounted = [];
}
if (typeof _components === 'undefined') {
    var _components = {};
}

if (typeof _data === 'undefined') {
    var _data = {};
}

if (typeof _watchs === 'undefined') {
    var _watchs = {};
}

if (typeof _computeds === 'undefined') {
    var _computeds = {};
}

_data.toast = {
    title: '',
    msg: '',
    show: false,
    type: 'cart',
    class: 'success'
};

_data.tinyConfig = {
      theme: 'modern',
      fontsize_formats: "8px 10px 12px 14px 16px 18px 20px 22px 24px 26px 39px 34px 38px 42px 48px",
      plugins: 'searchreplace autolink textcolor code link',
      toolbar1: 'formatselect fontsizeselect | bold italic strikethrough forecolor backcolor link code',
      media_filter_html: false
};


/*if (typeof _dictionary !== 'undefined') {
    Vue.use(VeeValidate, {
        locale: 'en',
        dictionary: _dictionary
    });
}*/

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

_methods.alertShow = function(type,message,title) {
    if (typeof title !== 'undefined') {
        this.alert.title = title;
    }
    this.alert.message = message;
    this.alert.type = type;
    this.alert.show = true;
};

_methods.showErrorAlert = function (message,title) {
    this.alertShow('E',message,title);
}
_methods.showSuccessAlert = function (message,title) {
    this.alertShow('S',message,title);
}

_methods.alertClose = function () {
    this.alert.show = false;
};

_methods._call = function (path,method,data,showAlert,showServerValidations) {
    var _this = this;
    var _fn = null;
    var _data = data || {};
    var _showAlert = showAlert || false;
    var _showServerValidations = showServerValidations || false;
    Vue.http.headers.common['X-CSRF-TOKEN'] = _csrfToken;

    switch (method) {
        case 'GET':
            _fn = Vue.http.get(path,_data);
            break;
        case 'DELETE':
            _fn = Vue.http.delete(path);
            break;            
        case 'POST':
            _data['_token'] = _csrfToken;
            _fn = Vue.http.post(path,_data);
            break;
        case 'PUT':
            _data['_token'] = _csrfToken;
            _fn = Vue.http.put(path,_data);
            break;
        case 'PATCH':
            _data['_token'] = _csrfToken;
            _fn = Vue.http.patch(path,_data);            
            break;
    }
    return new Promise(function(resolve, reject) {
        _fn.then(function(response){
            if (response.body.success) {
                if (_showAlert) {
                    _this.showSuccessAlert(response.body.message);    
                }
                resolve(response.body.data);    
            } else {
                if (response.body.errors === 'LOGIN') {
                    document.location = _this.url_login;
                } else {
                    reject(response.body.errors);        
                }
                
            }
        }, function(response) {
            //if (_showAlert) {
                _this.showErrorAlert(response.body.message);    
            //}
            switch (response.status) {
                case 422:
                    //Validacion de laravel
                    if (_showServerValidations) {
                        for(var key in response.body.errors) {
                            _showServerValidations.add(key, response.body.errors[key][0], 'server');                                        
                        }                           
                    }                    
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

_methods.ajaxGet = function(path,showAlert) {
    return this._call(path,'GET',null,showAlert);
}

_methods.ajaxPost = function(path,data,showAlert,serverErrors) {
    return this._call(path,'POST',data,showAlert,serverErrors);
}

_methods.ajaxPatch = function(path,data,showAlert,serverErrors) {
    return this._call(path,'PATCH',data,showAlert,serverErrors);
}

_methods.ajaxPut = function(path,data,showAlert,serverErrors) {
    return this._call(path,'PUT',data,showAlert,serverErrors);
}

_methods.ajaxDelete = function(path,showAlert) {
    return this._call(path,'DELETE',null,showAlert);
}



_methods.goTo = function (url) {
    if (url) {
        document.location = url;    
    }
}

_methods.hasAnyPerm = function(perms) {
    return _.intersection(this.perms, perms).length > 0;
}
_methods.hasPerm = function(perm) {
    return this.perms.indexOf(perm) > -1;
}
_methods.hasRole = function(role) {
    return this.roles.indexOf(role) > -1;
}
_methods.hasAnyRole = function(roles) {
    return _.intersection(this.roles, roles).length > 0;
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
    if (typeof decimals === 'undefined') {
        decimals = 2;
    }
    if (typeof separators === 'undefined') {
        separators = ['.', "'", ','];
    }    
    return '$ ' + currency(value,decimals, separators);
});

Vue.filter('currencyVtex', function(value, decimals, separators){
    if (typeof decimals === 'undefined') {
        decimals = 2;
    }
    if (typeof separators === 'undefined') {
        separators = ['.', "'", ','];
    }    
    return '$ ' + currency(value/100,decimals, separators);
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

Vue.filter('booleanLabel', function(value, textYes, textNo){
    if (typeof textYes === 'undefined') {
        textYes = 'SI';
    }
    if (typeof textNo === 'undefined') {
        textNo = 'NO';
    }    

    return value ? '<span class="label label-success">' + textYes + '</span>' : '<span class="label label-danger">' + textNo + '</span>';
});

function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }




var _fuHeader = {'X-CSRF-TOKEN': _csrfToken};


var app = new Vue({
    el: '#app',
    delimiters: ['(%', '%)'],
    data: $.extend({}, _generalData, _data),
    components: _components,
    methods: _methods,
    watch: _watchs,
    computed: _computeds,
    mounted: function() {
        var _m;
        for(var i=0; i < _mounted.length; i++) {
            if (typeof _mounted[i] === 'function') {
                _m = _mounted.shift();
                _m(this);
            }
        }
    }
});
