<div class="table-responsive">
    <table class="table m-b-0" id="vinos-table">
        <thead>
            <tr>
                <th @click="orderBy('id')" class="td-id" :class="cssOrderBy('id')">ID</th>
                <th>Imagen</th>
                <th>Titulo</th>
                <th>Peso</th>
                <th>Largo</th>
                <th>Ancho</th>
                <th>Alto</th>
                <th class="td-enabled">{{ trans('admin.table.enabled') }}</th>
                <th class="td-actions">{{ trans('admin.table.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="item in list" v-if="paging.total > 0">
                <td>(% item.id %)</td>
                <td><img v-if="item.imagen" :src="item.imagen_url" style="max-width: 50px;"></td>
                <td>(% item.titulo %)</td>
                <td>(% item.peso %)</td>
                <td>(% item.largo %)</td>
                <td>(% item.ancho %)</td>
                <td>(% item.alto %)</td>
                <td class="td-enabled">
                    <switch-button v-model="item.enabled" theme="bootstrap" type-bold="true" @onChange="onChangeEnabled(item)"></switch-button>
                </td>                
                <td class="td-actions">
                    @if(auth()->user()->hasRole('Superadmin') || auth()->user()->can('editar-'.$data['action_perms']))
                        <button-type type="edit-list" @click="edit(item)"></button-type>
                        <a class="btn btn-xs btn-default" @click="editLang(item,'en')">EN</a>
                        <a class="btn btn-xs btn-default" @click="editLang(item,'pt')">PT</a>
                        <button type="button" class="btn btn-xs btn-default" @click="listadoHijos(item)">
                            <i class="fas fa-list"></i>
                        </button>                         
                        <button-type type="remove-list" @click="destroy(item)"></button-type>
                    @endif
                </td>            
            </tr>
        </tbody>
    </table>
</div>