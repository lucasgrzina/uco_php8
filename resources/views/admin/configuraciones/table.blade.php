<div class="table-responsive">
    <table class="table m-b-0" id="contactos-table">
        <thead>
            <tr>
                <th @click="orderBy('id')" class="td-id" :class="cssOrderBy('id')">ID</th>
                <th>Nombre</th>
        <th>Apellido</th>
        <th>Email</th>
        <th>Pais</th>
        <th>Pais Id</th>
        <th>Tel Prefijo</th>
        <th>Tel Numero</th>
        <th>Mensaje</th>
        <th>Recibir Info</th>
                <!--th class="td-enabled">{{ trans('admin.table.enabled') }}</th-->
                <th class="td-actions">{{ trans('admin.table.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="item in list" v-if="paging.total > 0">
                <td>(% item.id %)</td>
                <!--td><img v-if="item.foto" :src="item.foto_url" style="max-width: 50px;"></td-->
                <td>(% item.nombre %)</td>
            <td>(% item.apellido %)</td>
            <td>(% item.email %)</td>
            <td>(% item.pais %)</td>
            <td>(% item.pais_id %)</td>
            <td>(% item.tel_prefijo %)</td>
            <td>(% item.tel_numero %)</td>
            <td>(% item.mensaje %)</td>
            <td>(% item.recibir_info %)</td>
                <!--td class="td-enabled">
                    <switch-button v-model="item.enabled" theme="bootstrap" type-bold="true" @onChange="onChangeEnabled(item)"></switch-button>
                </td-->                
                <td class="td-actions">
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