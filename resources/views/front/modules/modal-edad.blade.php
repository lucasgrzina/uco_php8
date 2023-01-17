
<!-- Modal Edad -->
<div class="modal fade" id="edadModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog  modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<a class="brand" href="index.php"><img src="{{asset("img/logo.svg")}}" /></a>
			</div>
			<div class="modal-body">
				<h3>{!! trans('front.modulos.ageGate.bienvenido') !!}</h3>
				<p class="mb-5">{!! trans('front.modulos.ageGate.cumple') !!}</p>
				<div class="d-flex justify-content-center select-btns mb-4">
					<button type="button" class="btn btn-primary" id="btnSiEdad">{!! trans('front.modulos.ageGate.btnSi') !!}</button>
					<button type="button" class="btn btn-primary">{!! trans('front.modulos.ageGate.btnNo') !!}</button>
				</div>

				<div class="d-flex justify-content-center w-100">
				<div class="form-check ">
					<label class="container">{!! trans('front.modulos.ageGate.recordarme') !!}
						<input type="checkbox" name="recordarme-mayor"  id="recordarme-mayor">
						<span class="checkmark" id="checkmarkEdad"></span>
					</label>
				</div>
				</div>
			</div>
			<div class="modal-footer">
				<p>{!! trans('front.modulos.ageGate.disclaimer') !!}</p>
			</div>
		</div>
	</div>
</div>
