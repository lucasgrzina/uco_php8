@extends('layouts.app')
@section('css')
    @parent
	<style>
		#tabla-est td,#tabla-est th {
			text-align: center;
		}
		#tabla-est th {
			background-color: #337ab7;
    		color: #fff;
		}		
		#tabla-est td {
			background-color: #ccc;
		}		

	</style>
@endsection

@section('scripts')
    @parent
    <script type="text/javascript">
		var _data = {!! json_encode($data) !!};

    </script>
@endsection
@section('content')
<div class="container">
    <div class="row">
    	<div class="col-xs-12">
    		<h1>Estadisticas</h1>
    		<p>Estadisticas del evento</p>	
    	</div>
	</div>
    <div class="row">
    	<div class="col-sm-10 col-sm-offset-1">
			<table class="table table-sm table-bordered" id="tabla-est">
				<thead>
					<tr>
						<th rowspan="2"># DE HCPs REGISTRADOS</th>
						<th rowspan="2"># DE HCPs QUE SE DESCONECTARON DESPUES DE LOS PRIMEROS 5 MINUTOS</th>
						<th colspan="2"># DE HCPs QUE FINALIZARON LA TOTALIDAD DEL EVENTO</th>
					</tr>
					<tr>
						<th>(QUE NO RESPONDIERON Q&A)</th>
						<th>(QUE RESPONDIERON Q&A)</th>
					</tr>	
				</thead>	
				<tbody>
					<tr>
						<td>(% estadisticas.total %)</td>
						<td>(% estadisticas.totalMin5 %)</td>
						<td>(% estadisticas.totalFinSinEnc %)</td>
						<td>(% estadisticas.totalFinConEnc %)</td>
					</tr>
				</tbody>		
			</table>	
    	</div>
	</div>	
</div>
@endsection
