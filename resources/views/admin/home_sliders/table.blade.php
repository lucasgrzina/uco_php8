<div class="table-responsive">
    <table class="table m-b-0" id="homeSliders-table">
        <thead>
            <tr>
                <th @click="orderBy('id')" class="td-id" :class="cssOrderBy('id')">ID</th>
                <th>Imagen Mobile</th>
                <th>Imagen Desktop</th>
                <th>Video</th>
                <th>Titulo</th>
                <th>Subtitulo</th>
                <th>Boton</th>
                <th>Orden</th>
                <th class="td-enabled">{{ trans('admin.table.enabled') }}</th>
                <th class="td-actions">{{ trans('admin.table.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="item in list" v-if="paging.total > 0">
                <td>(% item.id %)</td>
                <td><img v-if="item.imagen_mobile" :src="item.imagen_mobile_url" style="max-width: 50px;"></td>
                <td><img v-if="item.imagen_desktop" :src="item.imagen_desktop_url" style="max-width: 50px;"></td>
                <td><a v-if="item.video" :href="item.video_url" target="_blank">Ver</a></td>
                <td>(% item.titulo %)</td>
                <td>(% item.subtitulo %)</td>
                <td><a v-if="item.boton_titulo" :href="item.boton_url" target="_blank">(% item.boton_titulo %)</a></td>
                <td>(% item.orden %)</td>
                <td class="td-enabled">
                    <switch-button v-model="item.enabled" theme="bootstrap" type-bold="true" @onChange="onChangeEnabled(item)"></switch-button>
                </td>                
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