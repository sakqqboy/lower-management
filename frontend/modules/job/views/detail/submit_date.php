<?php

use kartik\date\DatePicker;
?>
<div class="modal" id="modal-submit-date">
	<div class="modal-content" id="modal-content">
		<div class="close-modal pull-right"><i class="far fa-times"></i></div>
		<div class="col-lg-12 container" style="margin-top: -20px;">
			<div class="row">
				<div class="col-lg-12 text-center" style="color:#0B0B3B;font-weight:bold;font-size:24px;">Submit Date</div>

				<div class="col-lg-12 text-center mt-20 border" style="min-height: 200px;">
					<div class="row">
						<div class="col-12 pt-20 font-size20 font-weight-bold text-center mt-20">
							Actual submit date
						</div>
						<div class="offset-3 col-6 pt-10  mt-20 text-center">
							<?=
							DatePicker::widget([
								'name' => 'trueDate',
								'id' => 'submit-date',
								'type' => DatePicker::TYPE_INPUT,
								'value' => date('Y-m-d'),
								'options' => ['placeholder' => 'Select New Target Date', 'class' => 'text-center'],
								'pluginOptions' => [
									'autoclose' => true,
									'format' => 'yyyy-mm-dd'
								]
							]);
							?>
						</div>

					</div>
				</div>
				<input type="hidden" id="jobId-submit-date" value="">
				<input type="hidden" id="jobCatId-submit-date" value="">
				<div class="offset-lg-3 offset-md-2 col-lg-6 col-md-8 mt-40 text-center">
					<a href="javascript:saveSubmitDate()" class="btn button-blue button-md pt-10">
						<i class="fa fa-check" aria-hidden="true"></i>&nbsp;&nbsp;Save&nbsp;&nbsp;
					</a>
				</div>
			</div>

		</div>
	</div>

</div>