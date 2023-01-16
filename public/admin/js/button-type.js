'use strict';
_components['button-type'] = {
    props: {
        type: {
            type: String,
            required: true
        },
        submit: {
            type: Boolean,
            default: false
        },
        promise: {
            type: Function,
            required: false
        },
        caption: {},
        i18n: {
            type: String,
            default: function() {
                return 'en';
            }
        },        
    },
    render: function render(h) {
        return h('button', {
            attrs: {
                type: this.submit ? 'submit' : 'button',
                title: this.title ? this.title : ''
            },
            class: this.theClass,
            on: this.events
        }, [h('i', {
            class: [{ 'fa fa-spinner fa-spin': this.loading }, this.icon]
        }), this.text]);
    },
    data: function data() {
        return {
            theClass: 'btn btn-sm',
            icon: 'fa fa-',
            text: '',
            loading: false,
            events: { click: this.clickButton },
            lang: {
                en: {
                    edit: 'Editar',
                    save: 'Guardar',
                    confirm: 'Confirmar',
                    refresh: 'Refrescar',
                    close: 'Cerrar',
                    new: 'Nuevo',
                    filter: 'Filtrar',
                    login: 'Ingresar',
                    forgot: 'Send password reset link',
                    add: 'Agregar',
                    back: 'Volver',
                    review: 'Revisar',
                    print: 'Imprimir',
                    cancel: 'Cancelar',
                    clear: 'Limpiar',
                    export: 'Exportar'
                }
            },
            title: ''
        };
    },
    mounted: function mounted() {
        switch (this.type) {
            case 'edit':
                this.theClass += ' bg-yellow';
                this.icon += 'pencil-alt';
                this.title = 'Editar';
                this.text = this.getText(this.type);
                break;            
            case 'save':
            case 'confirm':
                this.theClass += ' bg-green';
                this.icon += 'save';
                this.title = 'Guardar';                
                this.text = this.getText(this.type);
                break;
            case 'refresh':
                this.theClass += ' btn-default';
                this.icon = '';
                this.title = 'Refrescar';
                this.text = this.getText(this.type);
                break;
            case 'close':
                this.theClass += ' bg-navy';
                this.icon += 'times';
                this.text = this.getText(this.type);
                this.title = 'Cerrar';
                break;
            case 'cancel':
                this.theClass += ' bg-navy';
                this.icon += 'times';
                this.text = this.getText(this.type);
                this.title = 'Cancelar';
                break;                
            case 'add-list':
                this.theClass = 'btn btn-xs bg-green';
                this.icon += 'plus';
                this.title = 'Agregar';
                break;
            case 'edit-list':
                this.theClass = 'btn btn-xs bg-yellow';
                this.icon += 'pencil-alt';
                this.title = 'Editar';
                break;
            case 'show-list':
                this.theClass = 'btn btn-xs bg-gray';
                this.icon += 'eye';
                this.title = 'Ver';
                break;
            case 'remove-list':
                this.theClass = 'btn btn-xs bg-red';
                this.icon += 'trash-alt';
                this.title = 'Eliminar';
                break;
            case 'archive-list':
                this.theClass = 'btn btn-xs bg-green';
                this.icon += 'archive';
                this.title = 'Archivar';
                break;
            case 'unarchive-list':
                this.theClass = 'btn btn-xs bg-blue';
                this.icon += 'inbox';
                this.title = 'Desarchivar';
                break;
            case 'download-list':
                this.theClass = 'btn btn-xs bg-teal';
                this.icon += 'download';
                this.title = 'Descargar';
                break;                              
            case 'close-list':
                this.theClass = 'btn btn-xs bg-navy';
                this.icon += 'times';
                this.title = 'Cerrar';
                break;
            case 'close-oc-list':
                this.theClass = 'btn btn-xs bg-navy';
                this.icon += 'check-circle';
                this.title = 'Cerrar';
                break;                
            case 'perm-list':
                this.theClass = 'btn btn-xs bg-navy';
                this.icon += 'lock';
                break;            
            case 'reset-pass-list':
                this.theClass = 'btn btn-xs bg-gray';
                this.icon += 'key';
                this.title = 'Resetear';
                break;                
            case 'clear':
                this.theClass += ' btn bg-gray';
                this.icon += 'eraser';
                this.text = this.getText(this.type);
                this.title = 'Limpiar';
                break;                
            case 'login':
            case 'forgot':
                this.theClass += ' btn-primary btn-block';
                this.icon += this.type === 'login' ? 'sign-in' : 'arrow-circle-o-right';
                this.text = this.getText(this.type);
                break;
            case 'add':
                this.theClass += ' bg-purple';
                this.icon += 'plus';
                this.text = this.getText(this.type);
                this.title = 'Agregar';
                break;
            case 'back':
            case 'review':
                this.theClass += ' bg-purple';
                this.icon += 'arrow-left';
                this.text = this.getText(this.type);
                break;
            case 'new':
                this.theClass += ' bg-green';
                this.icon += 'plus';
                this.text = this.getText(this.type);
                break;
            case 'filter':
                this.theClass += ' bg-purple';
                this.icon += 'search';
                this.text = this.getText(this.type);;
                break;
            case 'print':
                this.theClass += ' btn-default';
                this.icon += 'print';
                this.text = this.getText(this.type);
                this.title = 'Imprimir';
                break;
            case 'export':
                this.theClass += ' btn-default';
                this.icon += 'file-download';
                this.text = this.getText(this.type);
                this.title = 'Exportar';
                break;                
            case 'liquidar':
                this.theClass += ' bg-gray';
                //this.icon += 'save';
                this.title = 'Calcular liquidación';                
                this.text = ' Calcular liquidación';
                break;
            case 'clone-list':
                this.theClass = 'btn btn-xs bg-primary';
                this.icon += 'clone';
                this.title = 'Clonar';
                break;                
    
        }

        this.theClass += ' btn-' + this.type;
    },

    methods: {
        clickButton: function clickButton() {
            var _this = this;

            if (this.promise && !this.loading) {
                this.loading = true;
                return this.promise().then(function (response) {
                    _this.loading = false;
                    return response;
                }, function (error) {
                    _this.loading = false;
                    return error;
                });
            } else {
                this.$emit('click', this);
            }
        },
        getText: function(type) {
            return ' '.concat(this.lang[this.i18n][type]);
        }
    }
};