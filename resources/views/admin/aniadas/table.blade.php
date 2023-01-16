<div class="table-responsive">
    <table class="table m-b-0" id="aniadas-table">
        <thead>
            <tr>
                <th @click="orderBy('id')" class="td-id" :class="cssOrderBy('id')">ID</th>
                <th>Anio</th>
                <th>Ficha</th>
                <th>Vino</th>
                <th>Stock</th>
                <th>Precio Pesos</th>
                <th>Precio Usd</th>
                <th>Sku</th>
                <!--th class="td-enabled">{{ trans('admin.table.enabled') }}</th-->
                <th class="td-actions">{{ trans('admin.table.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="item in list" v-if="paging.total > 0">
                <td>(% item.id %)</td>
                <td>(% item.anio %)</td>
                <td><a v-if="item.ficha" :href="item.ficha_url" target="_blank">Ver</a></td>
                <td>(% item.vino ? item.vino.titulo : '' %)</td>
                <td>(% item.stock %)</td>
                <td>(% item.precio_pesos %)</td>
                <td>(% item.precio_usd %)</td>
                <td>(% item.sku %)</td>
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