<div class="table-responsive">
    <table class="table m-b-0" id="pedidos-table">
        <thead>
            <tr>
                <th @click="orderBy('id')" class="td-id" :class="cssOrderBy('id')">Nro</th>
                <th>Despacho</th>
                <th>Cliente</th>
                <th style="white-space:nowrap;">Tipo factura</th>
                <th>Estado</th>
                <th>Envío</th>
                <th>Total</th>
                <th>SAP</th>
                <th>Fecha</th>
                <th align="center">Estatus 1</th>
                <th align="center">Estatus 2</th>
                <th align="center">Estatus 3</th>
                <!--th class="td-enabled">{{ trans('admin.table.enabled') }}</th-->
                <th class="td-actions">{{ trans('admin.table.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="item in list" v-if="paging.total > 0">
                <td>(% item.id %)</td>
                <td>(% item.despacho %)</td>
                <td style="white-space:nowrap;">
                    (% item.nombre + ' ' + item.apellido %)<br>
                    (% item.email %)
                </td>
                <td>(% item.tipo_factura == 'A' ? 'Factura A' : 'CF' %)</td>
                <td>(% item.estado %)</td>
                <td>
                    <a v-if="item.ups_tracking_number" :href="url_ver_etiqueta.replace('_ID_',item.id)" target="_blank">
                        <i class="fa fa-print"></i> Ver
                    </a>
                    <a v-else href="javascript:void(0);" @click="generarEnvio(item)">Generar</a>
                </td>
                <td style="white-space: nowrap;">(% item.total | currency %)</td>
                <td>
                    <template v-if="item.sincronizo_sap">
                        <span v-if="item.documento_sap" class="label label-success">
                            N° (% item.documento_sap %)
                        </span>
                        <span v-else class="label label-warning">
                            SI - Sin Nro.
                        </span>
                        <template v-if="item.error_sincronizacion_sap">
                            <br>
                            <span  class="label label-danger">
                                (% item.error_sincronizacion_sap %)
                            </span>
                        </template>


                    </template>
                    <template v-else>
                        <span v-if="item.error_sincronizacion_sap" class="label label-danger">
                            NO - (% item.error_sincronizacion_sap %)
                        </span>

                    </template>
                </td>
                <td>(% item.created_at | dateFormat %)</td>
                <!--td class="td-enabled">
                    <switch-button v-model="item.enabled" theme="bootstrap" type-bold="true" @onChange="onChangeEnabled(item)"></switch-button>
                </td-->
                <td>
                    <span class="btn btn-sm" :class="{'label-success': item.estatus_1,'label-gris': !item.estatus_1}">
                        En preparación
                    </span>
                </td>
                <td>
                    <button @click="cambiarEstatus(item, 'estatus_2')" class="btn btn-sm" :class="{'label-success': item.estatus_2,'label-gris': !item.estatus_2}">
                        Empaquetado
                    </button>
                </td>
                <td>
                    <button @click="cambiarEstatus(item, 'estatus_3')" class="btn btn-sm" :class="{'label-success': item.estatus_3,'label-gris': !item.estatus_3}">
                        Entregado
                    </button>
                </td>
                <td class="td-actions">
                    @if(auth()->user()->hasRole('Superadmin') || auth()->user()->can('editar-'.$data['action_perms']))
                        <button-type type="edit-list" @click="edit(item)"></button-type>
                        <!--button-type type="remove-list" @click="destroy(item)"></button-type-->
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
</div>
