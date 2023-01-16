<div class="table-responsive">
        <table class="table table-view-info  table-condensed">
            <tbody>
                <tr>
                    <td>Rol</td>
                    <td>(% selectedItem.roles.length > 0 ? selectedItem.roles[0].name : '' %)</td>
                </tr> 
                <tr>
                    <td>Nombre</td>
                    <td>(% selectedItem.nombre %)</td>
                </tr>
                <tr>
                    <td>Apellido</td>
                    <td>(% selectedItem.apellido %)</td>
                </tr>
                <tr>
                    <td>Fecha Nacimiento</td>
                    <td>(% selectedItem.fecha_nac %)</td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>(% selectedItem.email %)</td>
                </tr>    
                <tr>
                    <td>Celular</td>
                    <td>(% selectedItem.nro_celular %)</td>
                </tr>
                <tr>
                    <td>País</td>
                    <td>(% selectedItem.pais ? selectedItem.pais.nombre : '' %)</td>
                </tr>                
                <tr>
                    <td>Actividad</td>
                    <td v-html="$options.filters.booleanLabel(selectedItem.enabled, 'Usuario activo','Usuario inactivo')"></td>
                </tr>

            </tbody>
        </table>
</div>
<div class="table-responsive">
        <div class="table-responsive no-padding">
            <table class="table table-view-info table-condensed">
                <tbody>
                    <tr>
                      <th style="border-bottom: 1px solid #f4f4f4;border-left: 1px solid #f4f4f4;font-weight: bold;">{!! Form::label('perm', 'Permisos') !!}</th>
                      <th class="text-center" style="width: 80px;border-bottom: 1px solid #f4f4f4;border-left: 1px solid #f4f4f4;font-weight: bold;">Editar</th>
                      <th class="text-center" style="width: 80px;border-bottom: 1px solid #f4f4f4;border-left: 1px solid #f4f4f4;border-right: 1px solid #f4f4f4;font-weight: bold;">Ver</th>
                    </tr>
                    <tr v-for="(item,key) in info.permisos">
                      <td>(% key %)</td>
                      <td v-for="perm in item" class="text-center" style="background-color: #fff;">
                        <input type="checkbox" :value="perm" v-model="selectedItem.permissions" :disabled="true">
                      </td>
                    </tr>
              </tbody>
            </table>
        </div>
</div>