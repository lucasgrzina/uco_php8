Vue.use(VeeValidate, {
    locale: 'es',
    dictionary: _dictionary,
});



if (typeof _methods.store === 'undefined') {
    _methods.store = function() {
        var _this = this;
        var _ajaxMethod = _this.selectedItem.id == 0 ? _this.ajaxPost : _this.ajaxPut ;
        var _is_valid = _this.validateForm();
        _this.alert.show = false;
        return _this.$validator.validateAll().then(function(result) {
            if (result && _is_valid) {
                return _ajaxMethod(_this.url_save,_this.selectedItem,true,_this.errors).then(function(data){
                    location.href = _this.url_index;
                });                            
            }
        });
    };
}

if (typeof _methods.validateForm === 'undefined') {
    _methods.validateForm = function() {
        var _this = this;
        return true;
    };
}

if (typeof _methods.cancel === 'undefined') {
    _methods.cancel = function() {
        location.href = this.url_index;
    }
}

if (typeof _methods.onBlur === 'undefined') {
    _methods.onBlur = function(field,model) {
      var _this = this;
      var _model = (typeof model !== 'undefined' ? model : _this.selectedItem);
      switch (field) {
        case 'order':
          _model.order = _model.order ? _model.order : 1;
          break;
      }
    }
}

if (typeof _methods.inputFile === 'undefined') {
    _methods.inputFile = function(newFile, oldFile,onSuccess, onError,ref) {
      // Automatic upload
      var _this = this;
      var _ref = typeof ref !== 'undefined' ? ref : 'upload';

      if (Boolean(newFile) !== Boolean(oldFile) || oldFile.error !== newFile.error) {
        if (!this.$refs[_ref].active) {
          this.$refs[_ref].active = true;
        }
      }

      if (newFile && oldFile) {
        if (newFile.success) {
          this.$refs[_ref].clear();
          onSuccess(newFile);
        }    
        if (newFile.error) {
          this.$refs[_ref].clear();
          onError(newFile);
        }                      
      }
    }
}


if (typeof _methods.downloadHtml === 'undefined') {
  
  _methods.downloadHtml = function(filename, idelement) {
      var contentHTML = document.getElementById(idelement).innerHTML;
      var element = document.createElement('a');
      element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(contentHTML));
      element.setAttribute('download', filename);

      element.style.display = 'none';
      document.body.appendChild(element);

      element.click();

      document.body.removeChild(element);
     
     
    }
}

if (typeof _methods.removeFile === 'undefined') {
  _methods.removeFile = function(field) {
    this.selectedItem[field] = null;
    this.selectedItem[field + '_url'] = null;
  }  
}