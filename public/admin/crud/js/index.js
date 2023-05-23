    _components['paginate'] = VuejsPaginate;   

    _methods.storeFilters = function() {
        var _this = this;
        var _filters = {};
        if (_data.stored_filters_key) {
            if (this.$cookies.isKey('admin.filters')) {
                _filters = this.$cookies.get('admin.filters');
            }
            _filters[_this.stored_filters_key] = this.filters;
            this.$cookies.set('admin.filters', _filters);
        }
    };

    _methods.getStoredFilters = function() {
        var _this = this;
        if (_data.stored_filters_key && this.$cookies.isKey('admin.filters')) {
            _filters = this.$cookies.get('admin.filters');
            if (typeof _filters[_this.stored_filters_key] !== 'undefined') {
                console.debug(_filters[_this.stored_filters_key]);
                _this.filters = _filters[_this.stored_filters_key];  
                delete _filters[_this.stored_filters_key];
                this.$cookies.set('admin.filters', _filters);
            }
        }        
    };     
    if (typeof _data.filters === 'undefined')
    {
        _data.filters.enabled = null;    
    }
    //

    if (typeof _methods.exportTo === 'undefined') {
        _methods.exportTo = function (type) {
            var _this = this;
            var _value = null;
            let queryString = Object.keys(_this.filters).map((key) => {
                if (_this.filters[key] != null) {
                    _value = _this.filters[key];
                    if (_this.filters[key] instanceof Date) {
                        _value = moment(_this.filters[key]).format("YYYY-MM-DD");
                    }
                    return encodeURIComponent(key) + '=' + encodeURIComponent(_value);
                }
            }).join('&');
            document.location = _this.url_export.replace('_TYPE_',type).concat('?').concat(queryString);
        };
    }

    if (typeof _methods.filter === 'undefined') {
        _methods.filter = function () {
            var _this = this;
            _this.filters.page = 1;
            _this.doFilter();
        };
    }

    if (typeof _methods.clearFilters === 'undefined') {
        _methods.clearFilters = function () {
            var _this = this;
            _this.filters.page = 1;
            _this.filters.search = null;
            _this.doFilter();
        };
    }    

    if (typeof _methods.doFilter === 'undefined') {
        _methods.doFilter = function () {
            var _this = this;
            _this.loading = true;
            _this.ajaxPost(_this.url_filter,_this.filters).then(function(data) {
                _this.list.length = 0;
                _this.list = data.list;
                _this.paging = data.paging;
                _this.loading = false;
            }, function(error) {
                _this.loading = false;
            });
        };
    }

    if (typeof _methods.onChangePage === 'undefined') {
        _methods.onChangePage = function(page) {
            var _this = this;
            _this.filters.page = page;
            _this.doFilter();
        };
    }

    if (typeof _methods.create === 'undefined') {
        _methods.create = function() {
            this.storeFilters();
            document.location = this.url_create;
        };
    }

    if (typeof _methods.edit === 'undefined') {
        _methods.edit = function(item) {
            this.storeFilters();
            document.location = this.url_edit.replace('_ID_',item.id);
        };
    }

    if (typeof _methods.editLang === 'undefined') {
        _methods.editLang = function(item,lang) {
            this.storeFilters();
            document.location = this.url_edit_lang.replace('_ID_',item.id).replace('_LANG_',lang);
        };
    }

    if (typeof _methods.show === 'undefined') {
        _methods.show = function(item) {
            this.storeFilters();
            document.location = this.url_show.replace('_ID_',item.id);
        };
    }

    if (typeof _methods.destroy === 'undefined') {
        _methods.destroy = function(item) {
            var _this = this;
            if (confirm(_this.lang.are_you_sure)) {
                _this.loading = true;
                _this.ajaxDelete(_this.url_destroy.replace('_ID_',item.id),true)
                    .then(function(data){
                        _this.doFilter();
                    },function(resp) {
                        _this.loading = false;
                    });
            }
        };
    }

    if (typeof _methods.archive === 'undefined') {
        _methods.archive = function(item) {
            var _this = this;
            if (confirm(_this.lang.are_you_sure_archive)) {
                _this.loading = true;
                _this.ajaxPost(_this.url_archive.replace('_ID_',item.id),true)
                    .then(function(data){
                        _this.doFilter();
                    },function(resp) {
                        _this.loading = false;
                    });
            }
        };
    }

    if (typeof _methods.unarchive === 'undefined') {
        _methods.unarchive = function(item) {
            var _this = this;
            if (confirm(_this.lang.are_you_sure_dearchive)) {
                _this.loading = true;
                _this.ajaxPost(_this.url_unarchive.replace('_ID_',item.id),true)
                    .then(function(data){
                        _this.doFilter();
                    },function(resp) {
                        _this.loading = false;
                    });
            }
        };
    }

    if (typeof _methods.onChangeEnabled === 'undefined') {
        _methods.onChangeEnabled = function(item) {
            var _this = this;
            _this.loading = true;
            _this.ajaxPost(_this.url_change_enabled,item,true).then(function(data) {
                _this.loading = false;
            }, function(error) {
                item.enabled = !item.enabled;
                _this.loading = false;

            });            
        };
    }

    if (typeof _methods.orderBy === 'undefined') {
        _methods.orderBy = function(field) {
            var _this = this;
            _this.filters.page = 1;
            if (typeof _this.filters.orderBy !== 'undefined') {
                if (_this.filters.orderBy === field) {
                    if (_this.filters.sortedBy === null) {
                        _this.filters.sortedBy = 'asc';   
                    } else if (_this.filters.sortedBy === 'asc') {
                        _this.filters.sortedBy = 'desc';   
                    } else {
                        _this.filters.sortedBy = null;   
                    }
                } else {
                    _this.filters.orderBy = field;
                    _this.filters.sortedBy = 'asc';   
                }
            } else {
                    _this.filters.orderBy = field;
                    _this.filters.sortedBy = 'asc';                   
            }

            _this.doFilter();
        };
    }

    if (typeof _methods.cssOrderBy === 'undefined') {
        _methods.cssOrderBy = function(field) {
            var _this = this;
            var _class = "sort-by";
            if (_this.filters.orderBy === field) {
                if (_this.filters.sortedBy === 'asc') {
                    _class+= ' asc';
                } else if (_this.filters.sortedBy === 'desc') {
                    _class+= ' desc';
                }
            }

            return _class;
        };
    }

    if (typeof _methods.goToParent === 'undefined') {
        _methods.goToParent = function(item) {
            document.location = this.url_back;
        };
    }