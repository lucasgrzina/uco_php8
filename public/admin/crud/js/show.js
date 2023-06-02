    if (typeof _methods.edit === 'undefined') {
        _methods.edit = function(item) {
            document.location = this.url_edit.replace('_ID_',item.id);
        };
    }