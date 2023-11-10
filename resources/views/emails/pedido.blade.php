<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>

<head>

	<meta charset="utf-8">

	<title>Magia de Uco</title>

	<meta name="viewport" content="width=device-width, initial-scale=1">

	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<script type="text/javascript" src="/dtagent634_23hjprtx_1034.js" data-dtconfig="rid=RID_1487864261|rpid=2060065574|domain=smiles.com.br|rt=100|tp=500,50,3,1,10|reportUrl=dynaTraceMonitor"></script>

	<style type="text/css">

		.dataSaldoHeader a{color:#828484; text-decoration:none;}
		.dataHeader a{color:#555555; text-decoration:none;}
		.dataReferentes a{color:#828484; text-decoration:none;}
		.dataExpiracao a{color:#ff7119; text-decoration:none;}
		.applelinkFooter a{color:#F2F0DE; text-decoration:none;}

		@media screen and (max-width: 600px){

			.full-width{
				width: 100% !important;
			}

			.img-responsive {
				width: 100% !important;
				height: auto !important;
			}

			.text-center {
				text-align: center !important;
			}
		}

		.mobile-only{ display:none;}

		img {
			border: 0 none;
		}


		@media screen and (max-width:480px){

			.txt-480 {font-size: 16px !important;}

			.margen-box {width: 95% !important;}

			.width-foto {width: 97% !important; image-rendering: -webkit-optimize-contrast;}

			.logo-gris {width: 33% !important; image-rendering:-webkit-optimize-contrast; text-align: center !important; margin:auto !important; padding-left:0px !important;}

			.ancho-div-1{width:97% !important; margin: auto !important; text-align: center !important; padding-left:2% !important; padding-right: 2% !important; }

			.txt-aling{ text-align: center !important;}

			.centrar { margin: auto !important; padding-bottom: 7%;}

		}



		@media only screen and (max-width:480px) {

			table[class="mobile-only"]{ display:block !important; text-align: center !important;  overflow: visible !important;float: none !important;line-height:100% !important;}

		}

	</style>

</head>

<body style="margin:0; padding:0;">

	<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" style="background-color:#e0e0e0;">

		<tr>

			<td>
				<table border="0" cellpadding="0" cellspacing="0" style="background-color:#F2F0DE; margin:0 auto;" class="full-width" width="600" align="center">

					<tbody>

						<tr>

							<td>

								<table border="0" cellpadding="0" cellspacing="0" style="background-color:#ffffff;" class="full-width" width="600" align="center">

								<tr>

									<td width="600" class="full-width" align="center"><img src="{{asset('img/mailings/'.trans('emails.pedido.imagenes.gracias'))}}" width="600" class="full-width" border="0" style="display:block; max-width: 100%;">

									</td>

								</tr>

							</table>

							</td>

						</tr>

					</tbody>

				</table>


				<table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
					<tbody>
						<tr>
							<td style="direction:ltr;font-size:0px;padding:0;text-align:center;vertical-align:top; ">

								<div class="mj-column-per-100 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width: 100%; max-width: 100%;">

									<table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top; background-color: #f2f1df;" width="100%">


										<tr>
											<td>
												<div style="font-family: Helvetica, Arial, sans-serif;font-size: 21px; line-height: 20px; color: #FF5A00; border-color: #f2f1df; border-width:0;     border-style: solid; font-weight: 400; padding:10px 10px; background-color: #f2f1df; text-align: center;">
													<img src="{{asset('img/mailings/camion.jpg')}}" width="124" height="79" border="0" style="display:block; margin: 0 auto; width: 124px; height: 79px;">

													<div style="max-width: 350px; width: 100%; font-family: Helvetica, Arial, sans-serif; font-size:21px; line-height:24px; text-align:left; color:#8E8063; font-weight: normal; text-align: center; margin: 0 auto; padding: 5px;">
														{!! trans('emails.pedido.info') !!}
													</div>

													<div style="width: 100%; font-family: Helvetica, Arial, sans-serif; font-size:13px; line-height:24px; text-align:left; color:#000000; font-weight: normal; text-align: center; padding: 0; margin-top: 5px; margin-bottom: 0px; text-align: center;">
														{!! trans('emails.pedido.nro') !!}<strong>{{$pedido->id}}</strong>
													</div>

													<div style="max-width: 350px; width: 100%; font-family: Helvetica, Arial, sans-serif; font-size:13px; line-height:24px; text-align:left; color:#000000; font-weight: normal; text-align: center; margin: 0 auto; padding: 15px;">
														@foreach ($pedido->items as $item)
                                                        {!! trans('emails.pedido.producto') !!}: <strong>{{$item->aniada->vino->titulo}}</strong><br>
                                                        @endforeach
                                                        {!! trans('emails.pedido.fecha') !!}: {{$pedido->created_at->format('d/m/Y')}}<br>
                                                        {!! trans('emails.pedido.hora') !!}: <strong>{{$pedido->created_at->format('H:i')}}</strong><br>
                                                        {!! trans('emails.pedido.subtotal') !!}: <strong>{{formatoImporte($pedido->total_carrito)}}</strong><br>
                                                        {!! trans('emails.pedido.envio') !!}: <strong>{{formatoImporte($pedido->total_envio)}}</strong><br>
                                                        {!! trans('emails.pedido.total') !!}: <strong>{{formatoImporte($pedido->total)}}</strong>
													</div>


												</div>
											</td>
										</tr>

										<tr>
											<td align="center">
												<div style="font-family: Helvetica, Arial, sans-serif;font-size: 21px; line-height: 20px; color: #FF5A00; border-color: #ffffff; border-width:0;     border-style: solid; font-weight: 400; padding:10px 10px; background-color: #ffffff; text-align: center;"><img src="{{asset('img/mailings//lupa.jpg')}}" width="124" height="111" border="0" style="display:block; margin: 0 auto; width: 124px; height: 111px;">
													<div style="max-width: 350px; width: 100%; font-family: Helvetica, Arial, sans-serif; font-size:21px; line-height:24px; text-align:left; color:#8E8063; font-weight: normal; text-align: center; margin: 0 auto; padding: 5px;">
														{!! trans('emails.pedido.infoEnvio') !!}
													</div>
													<div style="width: 100%; font-family: Helvetica, Arial, sans-serif; font-size:13px; line-height:24px; text-align:left; color:#000000; font-weight: normal; text-align: center; padding: 0; margin-top: 5px; margin-bottom: 0px; text-align: center;">
														{!! trans('emails.pedido.nro') !!}<strong>{{$pedido->id}}</strong>
													</div>

													<div style="max-width: 350px; width: 100%; font-family: Helvetica, Arial, sans-serif; font-size:16px; line-height:24px; text-align:left; color:#000000; font-weight: normal; text-align: center; margin: 0 auto; padding: 15px;">
														{!! str_replace('_lnk_cuenta_',routeIdioma('miCuenta'),trans('emails.pedido.conocer')) !!}
													</div>
												</div>
											</td>
										</tr>
										<tr><td height="36px;" style="border-color: #ffffff; background-color:#ffffff;">&nbsp;</td></tr>
									</table>
								</div>
							</td>
						</tr>
					</tbody>
				</table>

				<table border="0" cellpadding="0" cellspacing="0" style="background-color:#f5f5f5; margin:0 auto;" class="full-width" width="600" align="center">

					<tbody>

						<tr>

							<td>

								<table border="0" cellpadding="0" cellspacing="0" style="background-color:#f5f5f5; margin:0 auto;" class="full-width" width="600" align="center">

									<tbody>

										<tr>

											<td style="text-align: center; font-size: 0;" align="center">

												<div style="display:inline-block; vertical-align:top;" class="full-width">

													<table align="left" width="230" cellspacing="0" cellpadding="0" border="0" class="full-width">

														<tbody>

															<tr>

																<td>

																	<table align="center" width="230" cellspacing="0" cellpadding="0" border="0" class="full-width" style="max-width: 230px;">

																		<tbody>

																			<tr>

																				<td height="15" style="line-height: 0px; font-size: 0px; height: 15px;">&nbsp;</td>

																			</tr>

																			<tr>

																				<td>

																					<a href="#" target="_blank">

																						<img src="{{asset('img/mailings//logo-magia-uco-nuevo.png')}}" width="190" height="76" border="0" style="display:block; margin: 0 auto; width: 190px; height: 76px;">

																					</a>
																				</td>
																			</tr>
																			<tr>
																				<td height="15" style="line-height: 0px; font-size: 0px; height: 15px;">&nbsp;</td>
																			</tr>
																		</tbody>
																	</table>
																</td>
															</tr>
														</tbody>
													</table>

												</div>



											</td>

										</tr>

									</tbody>

								</table>

							</td>

						</tr>

					</tbody>

				</table>

				<table border="0" cellpadding="0" cellspacing="0" style="background-color:#f5f5f5; margin:0 auto;" class="full-width" width="600" align="center">

					<tbody>

						<tr>

							<td>

								<table border="0" cellpadding="0" cellspacing="0" style="background-color:#f5f5f5; margin:0 auto;" class="full-width" width="550" align="center">

									<tbody>

										<tr>

											<td style="text-align: center; font-size: 0;" align="center">




												<div style="display:inline-block; vertical-align:top;" class="full-width">

													<table align="left" width="150" valign="middle" height="36" cellspacing="0" cellpadding="0" border="0" style="height: 32px; vertical-align: middle;" class="full-width">

														<tbody>

															<tr>

																<td>

																	<table align="right" width="150" valign="middle" height="36" cellspacing="0" cellpadding="0" border="0" style="height: 32px; vertical-align: middle;" class="full-width">

																		<tbody>

																			<tr>

																				<td>

																					<table align="center" width="75" valign="middle" height="36" cellspacing="0" cellpadding="0" border="0" style="height: 32px; vertical-align: middle; max-width: 75px; background-color:#f5f5f5;" class="full-width ">

																						<tbody>

																							<tr>

																								<td height="15" style="line-height: 0px; font-size: 0px; height: 15px;">&nbsp;</td>

																							</tr>

																							<tr>

																								<td>
																									<a href="https://www.facebook.com/magiadeluco" target="_blank">
																										<img src="{{asset('img/mailings//ico_facebook.png')}}" width="14" height="32" border="0" style="display:block; margin: 0 auto; width: 14px; height: 32px;">
																									</a>
																								</td>

																								<td>

																									<a href="https://www.instagram.com/magiadeluco/" target="_blank">
																										<img src="{{asset('img/mailings//ico_instagram.png')}}" width="27" height="32" border="0" style="display:block; margin: 0 auto; width: 27px; height: 32px;">
																									</a>

																								</td>


																							</tr>

																							<tr>

																								<td height="15" style="line-height: 0px; font-size: 0px; height: 15px;">&nbsp;</td>

																							</tr>

																						</tbody>

																					</table>

																				</td>

																			</tr>

																		</tbody>

																	</table>

																</td>

															</tr>

														</tbody>

													</table>

												</div>

											</td>

										</tr>

									</tbody>

								</table>

							</td>
						</tr>
					</tbody>
				</table>
				<table border="0" cellpadding="0" cellspacing="0" style="background-color:#f5f5f5;" class="full-width" width="600" align="center">

					<tr>

						<td width="600" class="full-width" align="center"><img src="{{asset('img/mailings//filete.png')}}" width="600" class="full-width" border="0" style="display:block; max-width: 100%;">

						</td>

					</tr>

				</table>
				<table border="0" cellpadding="0" cellspacing="0" style="background-color:#f5f5f5;" class="full-width" width="600" align="center">

					<tr>

						<td width="600" class="full-width" align="center">
							<div style="font-family: Helvetica, Arial, sans-serif;font-size:15px; color:#363126; line-height:24px;text-align:center;color:#363126;padding:15px; background-color:#f5f5f5; ">
                                <a href="{{routeIdioma('home')}}" style="color:#363126; text-decoration: none; font-weight: normal;">{{trans('emails.general.desuscribirme')}}</a> |
                                <a href="{{routeIdioma('login')}}" style="color:#363126; text-decoration: none; font-weight: normal;">{{trans('emails.general.perfil')}}</a> |
                                <a href="{{routeIdioma('terminosCondiciones')}}" style="color:#363126; text-decoration: none; font-weight: normal;">{{trans('emails.general.tyc')}}</a>
                            </div>
						</td>

					</tr>

				</table>
			</td>
		</tr>
	</table>
</body>
</html>
