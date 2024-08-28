<div class="table-responsive">
    <table class="table m-b-0" id="contactos-table">
        <thead>
            <tr>
                <th @click="orderBy('id')" class="td-id" :class="cssOrderBy('id')">ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Email</th>
                <th>País</th>
                <th>Prefijo</th>
                <th>Teléfono</th>
                <th>Mensaje</th>
                <th align="center" style="white-space:nowrap;">Recibir Info</th>
                <th align="center" style="white-space:nowrap;">Estatus 1</th>
                <th align="center" style="white-space:nowrap;">Estatus 2</th>
                <!--th class="td-enabled">{{ trans('admin.table.enabled') }}</th-->
                <th class="td-actions">{{ trans('admin.table.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="item in list" v-if="paging.total > 0">
                <td>(% item.id %)</td>
                <!--td><img v-if="item.foto" :src="item.foto_url" style="max-width: 50px;"></td-->
                <td style="white-space:nowrap;">(% item.nombre %)</td>
                <td style="white-space:nowrap;">(% item.apellido %)</td>
                <td style="white-space:nowrap;">(% item.email %)</td>
                <td>(% item.pais %)</td>
                <td>(% item.tel_prefijo ? "+" + item.tel_prefijo : "" %)</td>
                <td style="white-space:nowrap;">(% item.tel_numero %)</td>
                <td>(% item.mensaje %)</td>
                <td align="center">
                    <span class="label" :class="{'label-success':item.recibir_info,'label-danger':!item.recibir_info,}">
                        (% item.recibir_info ? 'SI' : 'NO' %)
                    </span>
                </td>
                <td>
                    <button @click="cambiarEstatus(item, 'estatus_1')" class="btn btn-sm" :class="{'label-success': item.estatus_1,'label-gris': !item.estatus_1}">
                        Leido
                    </button>
                </td>
                <td>
                    <button @click="cambiarEstatus(item, 'estatus_2')" class="btn btn-sm" :class="{'label-success': item.estatus_2,'label-gris': !item.estatus_2}">
                        Respondido
                    </button>
                </td>
                <td class="td-actions" style="white-space:nowrap;">
                    @if(auth()->user()->hasRole('Superadmin') || auth()->user()->can('editar-'.$data['action_perms']))
                        <button-type type="edit-list" @click="edit(item)"></button-type>
                        <a class="btn btn-xs btn-default" @click="editLang(item,'en')">EN</a>
                        <a class="btn btn-xs btn-default" @click="editLang(item,'pt')">PT</a>
                        <button-type type="remove-list" @click="destroy(item)"></button-type>
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
</div>
