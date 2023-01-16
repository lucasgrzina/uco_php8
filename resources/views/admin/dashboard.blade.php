@extends('layouts.app')
@section('css')
    @parent
    <link rel="stylesheet" href="{{ asset('admin/crud/css/show.css') }}"/>
@endsection
@section('scripts')
    @parent
    <script type="text/javascript">
		var _data = {!! json_encode($data) !!};
        console.log(_data);
    </script>

@endsection
@section('content')
<div class="container">
    <div class="row">
    	<div class="col-xs-12">
    		<h1>Bienvenido</h1>
            
    	</div>
	</div>
    <div class="row">
    	<div class="col-xs-6">
    	</div>
	</div>	
</div>
@endsection
