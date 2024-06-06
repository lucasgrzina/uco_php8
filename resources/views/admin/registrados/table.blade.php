<div class="table-responsive">
    <table class="table m-b-0" id="newsletters-table">
        <thead>
            <tr>
                <th @click="orderBy('id')" class="td-id" :class="cssOrderBy('id')">ID</th>
                <th>Usuario</th>
                <th>Email</th>
                <th>Alta</th>
                <!--th class="td-enabled">{{ trans('admin.table.enabled') }}</th-->
                <th class="td-actions">{{ trans('admin.table.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="item in list" v-if="paging.total > 0">
                <td>(% item.id %)</td>
                <td>(% item.usuario %)</td>
                <td>(% item.email %)</td>
                <td>(% item.created_at | dateFormat %)</td>
                <td class="td-actions">
                    @if(auth()->user()->hasRole('Superadmin') || auth()->user()->can('editar-'.$data['action_perms']))
                        <!--button-type type="edit-list" @click="edit(item)"></button-type-->
                        <button-type type="remove-list" @click="destroy(item)"></button-type>
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
</div>
