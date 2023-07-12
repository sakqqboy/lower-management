<div class="modal" id="modal-complain">
	<div class="modal-content" id="modal-content">
		<div class="close-modal pull-right"><i class="far fa-times"></i></div>
		<div class="col-lg-12 container" style="margin-top: -20px;">
			<div class="row">
				<div class="col-lg-12 text-center" style="color:#0B0B3B;font-weight:bold;font-size:24px;">Complain & Mistake</div>
				<div class="col-lg-12 text-center mt-20">
					<textarea class="form-control" id="complain" style="min-height: 200px;font-size:18px;"></textarea>
				</div>
				<input type="hidden" id="jd" value=<?= $jobId ?>>
				<div class="offset-lg-3 offset-md-2 col-lg-6 col-md-8 mt-40 text-center">
					<a href="javascript:complainJob()" class="btn button-red button-md pt-10">
						<i class="fa fa-exclamation" aria-hidden="true"></i>&nbsp;&nbsp;SENT&nbsp;&nbsp;
					</a>
				</div>
			</div>

		</div>
	</div>

</div>