    <div class="form-group">
      <button-type type="filter" @click="filter()"></button-type>      
      <button-type v-if="filters.export_xls" type="export" @click="exportTo('xls')"></button-type>
    </div>