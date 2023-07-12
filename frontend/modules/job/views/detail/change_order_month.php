<?php

use kartik\date\DatePicker;
?>
<div class="modal" id="modal-target-month">
	<div class="modal-content" id="modal-content">
		<div class="close-modal pull-right"><i class="far fa-times"></i></div>
		<div class="col-lg-12 container" style="margin-top: -20px;">
			<div class="row">
				<div class="col-lg-12 text-center" style="color:#0B0B3B;font-weight:bold;font-size:24px;">Change Target Month</div>

				<div class="col-lg-12 text-center mt-20 border" style="min-height: 200px;">
					<div class="row">
						<div class="col-6 pt-10 font-size20 font-weight-bold mt-20 text-right">
							Current Target Month
						</div>
						<div class="col-6 pt-10 font-size20 font-weight-bold mt-20 border-left  pl-40 text-left" id="old-targetMonth">

						</div>
						<div class="col-6 pt-20 font-size20 font-weight-bold text-right mt-20">
							New Target Month
						</div>
						<div class="col-6 pt-10  mt-20 border-left  pl-40 text-left">

							<input type="text" name="startMonth[]" id="startMonth1" placeholder="New Target Month" class="form-control" readonly onclick="javascript:showMonthCalendar(1)" value="" required>
							<div class="col-12 month-calendar-box" id="month-calendar1">
								<?= $this->render('month_calendar', ["i" => 1]) ?>
							</div>
						</div>

					</div>
				</div>
				<input type="hidden" id="jobCateIdTargetMonth" value="">
				<input type="hidden" id="old-targetMonth-input" value="">
				<div class="offset-lg-3 offset-md-2 col-lg-6 col-md-8 mt-40 text-center">
					<a href="javascript:changeTargetMonth()" class="btn button-blue button-md pt-10">
						<i class="fa fa-check" aria-hidden="true"></i>&nbsp;&nbsp;Confirm Change Target Month&nbsp;&nbsp;
					</a>
				</div>
			</div>

		</div>
	</div>

</div>