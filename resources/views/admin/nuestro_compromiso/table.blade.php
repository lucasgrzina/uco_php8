<div class="table-responsive">
    <table class="table m-b-0" id="legados-table">
        <thead>
            <tr>
                <!--th @click="orderBy('id')" class="td-id" :class="cssOrderBy('id')">ID</th-->
                <th>Titulo</th>
                <th>Imagen</th>
                <th class="td-actions">{{ trans('admin.table.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="item in list" v-if="paging.total > 0">
                <td>(% item.titulo %)</td>
                <td><img v-if="item.imagen_home" :src="item.imagen_home_url" style="max-width: 50px;"></td>

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
