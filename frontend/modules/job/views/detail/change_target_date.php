<?php

use kartik\date\DatePicker;
?>
<div class="modal" id="modal-target-date">
	<div class="modal-content" id="modal-content">
		<div class="close-modal pull-right"><i class="far fa-times"></i></div>
		<div class="col-lg-12 container" style="margin-top: -20px;">
			<div class="row">
				<div class="col-lg-12 text-center" style="color:#0B0B3B;font-weight:bold;font-size:24px;">Change Target Date</div>

				<div class="col-lg-12 text-center mt-20 border" style="min-height: 200px;">
					<div class="row">
						<div class="col-6 pt-10 font-size20 font-weight-bold mt-20 text-right">
							Current Target Date
						</div>
						<div class="col-6 pt-10 font-size20 font-weight-bold mt-20 border-left  pl-40 text-left" id="old-targetDate">

						</div>
						<div class="col-6 pt-20 font-size20 font-weight-bold text-right mt-20">
							New Target Date
						</div>
						<div class="col- pt-10  mt-20 border-left  pl-40 text-left">
							<?=
							DatePicker::widget([
								'name' => 'trueDate',
								'id' => 'newTargetDate',
								'type' => DatePicker::TYPE_INPUT,
								'options' => ['placeholder' => 'Select New Target Date'],
								'pluginOptions' => [
									'autoclose' => true,
									'format' => 'yyyy-mm-dd'
								]
							]);
							?>
						</div>

					</div>
				</div>
				<input type="hidden" id="jobCateIdTarget" value="">
				<input type="hidden" id="old-targetDate-input" value="">
				<div class="offset-lg-3 offset-md-2 col-lg-6 col-md-8 mt-40 text-center">
					<a href="javascript:changeTargetDate()" class="btn button-blue button-md pt-10">
						<i class="fa fa-check" aria-hidden="true"></i>&nbsp;&nbsp;Confirm Change Target Date&nbsp;&nbsp;
					</a>
				</div>
			</div>

		</div>
	</div>

</div>