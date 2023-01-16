<div class="table-responsive">
    <table class="table m-b-0" id="roles-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th class="td-actions">{{ trans('admin.table.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="item in list" v-if="paging.total > 0">
                <td>(% item.id %)</td>
                <td>(% item.name %)</td>
                <td class="td-actions">
                    <!--button-type type="show-list" @click="show(item)"></button-type-->
                    @if(auth()->user()->hasRole('Superadmin') || auth()->user()->can('editar-'.$data['action_perms']))
                    <button-type type="edit-list" @click="edit(item)"></button-type>
                    <button-type type="remove-list" @click="destroy(item)"></button-type>
                    @endif
                </td>            
            </tr>
        </tbody>
    </table>
</div>