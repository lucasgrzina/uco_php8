    <div class="form-group">
      <input type="text" class="form-control input-sm" v-model="filters.search"  placeholder="{{ trans('admin.search') }}" @keyup.enter="filter">
    </div>