<div class="modal" id="modal-fiscal-year">
	<div class="modal-content" id="modal-content">
		<div class="close-modal pull-right"><i class="far fa-times"></i></div>
		<div class="col-lg-12 container" style="margin-top: -20px;">
			<div class="row">
				<div class="col-lg-12 text-center" style="color:#0B0B3B;font-weight:bold;font-size:24px;">Change Fiscal Year</div>
				<div class="col-lg-12 text-center mt-20 border" style="min-height: 200px;">
					<div class="row">
						<div class="col-6 pt-10 font-size20 font-weight-bold mt-20 text-right">
							Current Fiscal Year
						</div>
						<div class="col-6 pt-10 font-size20 font-weight-bold mt-20 border-left  pl-40 text-left" id="old-fiscalYear">

						</div>
						<div class="col-6 pt-20 font-size20 font-weight-bold text-right mt-20">
							New Fiscal Year
						</div>
						<div class="col- pt-10  mt-20 border-left  pl-40 text-left">
							<input type="text" class="form-control font-size16" placeholder="New fiscal year" id="newFiscalYear">
						</div>

					</div>
				</div>
				<input type="hidden" id="jobCateIdFiscal" value="">
				<input type="hidden" id="old-fiscalYear-input" value="">
				<div class="offset-lg-3 offset-md-2 col-lg-6 col-md-8 mt-40 text-center">
					<a href="javascript:changeFiscalYear()" class="btn button-sky button-md pt-10">
						<i class="fa fa-check" aria-hidden="true"></i>&nbsp;&nbsp;Confirm Change Fiscal Year&nbsp;&nbsp;
					</a>
				</div>
			</div>

		</div>
	</div>

</div>