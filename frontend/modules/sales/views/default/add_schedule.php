<div class="modal-schedule" id="modal-add-schedule">
	<div class="modal-schedule-content pr-0 pl-0" id="modal-content">
		<div class="col-12">
			<div class="row border-bottom header-modal">
				<div class="col-lg-11 col-10 font-size16 pt-3 font-weight-bold">
					<div class="row">
						<div class="text-center circle-warning">
							<i class="fa fa-angle-right" aria-hidden="true"></i>
						</div>
						<span>Add schedule</span>
					</div>
				</div>
				<div class="col-lg-1 col-2 text-right close-modal-schedule">
					<i class="fa fa-window-close" aria-hidden="true"></i>
				</div>
			</div>
		</div>
		<div class="col-12 mt-10 pb-10 border-bottom">
			<div class="row">
				<div class="col-3 font-size14 text-left font-weight-bold" style="padding-top: 50px;padding-bottom:50px;">Date and Time</div>
				<div class="col-9">
					<div class="col-12 pl-1">
						<img src="<?= Yii::$app->homeUrl ?>images/icon/calendar.png" alt="" class="carlendar-button mr-10">
						<span id="date-text" class="font-size16 mt-20"></span>
						<span class="font-size14 ml-10">
							<input type="checkbox" class="checkbox-xs" id="allDay"><label class="ml-1"> All day</label>
						</span>
					</div>
					<div class="col-12 pl-1 mt-10">
						<div class="row">
							<div class="col-3">
								<select name="" id="" class="form-control sales-select">
									<option>hr</option>
								</select>
							</div>
							<div class="col-2 pl-0 pr-0">
								<select name="" id="" class="form-control sales-select">
									<option>m</option>
								</select>
							</div>
							<div class="col-1 pt-2"> ~ </div>
							<div class="col-2 pl-0 pr-0">
								<select name="" id="" class="form-control sales-select">
									<option>hr</option>
								</select>
							</div>
							<div class="col-3">
								<select name="" id="" class="form-control sales-select">
									<option>m</option>
								</select>
							</div>
						</div>
					</div>
					<div class="col-12 pl-0 mt-10 font-size14">
						<div class="row">
							<div class="col-8">
								<button class="mr-10 btn btn-outline-secondary btn-sm" style="margin-left:4px;">Specify by</button>
								<button class="mr-10 btn btn-outline-secondary  btn-sm">Repeating Period</button>
							</div>
							<div class="col-4 pr-0">
								<select name="" id="" class="form-control sales-select">
									<option>Country</option>
								</select>
							</div>
						</div>


					</div>
					<div class="col-12 pl-0 mt-10 font-size14 pr-0" style="margin-left:4px;">

						<select name="" id="" class="form-control sales-select">
							<option>Client</option>
						</select>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 mt-10 pb-10 border-bottom">
			<div class="row">
				<div class="col-3 font-size14 text-left font-weight-bold pt-2">Title</div>
				<div class="col-9 font-size16 text-left font-weight-bold">
					<input type="text" class="form-control" style="margin-left:4px;">
				</div>
			</div>
		</div>
		<div class="col-12 mt-10 pb-10 border-bottom">
			<div class="row">
				<div class="col-3 font-size14 text-left font-weight-bold pt-2">Place</div>
				<div class="col-9 font-size16 text-left font-weight-bold">
					<input type="text" class="form-control" style="margin-left:4px;">
				</div>
			</div>
		</div>
		<div class="col-12 mt-10 pb-10 border-bottom">
			<div class="row">
				<div class="col-3 font-size14 text-left font-weight-bold pt-40">Content</div>
				<div class="col-9 font-size16 text-left font-weight-bold">
					<textarea name="" id="" class="form-control" style="margin-left:4px;height:100px;"></textarea>
				</div>
			</div>
		</div>
		<div class="col-12 mt-10 pb-10 border-bottom">
			<div class="row">
				<div class="col-3 font-size14 text-left font-weight-bold pt-30">Public category</div>
				<div class="col-9 font-size12 text-left font-size14 pl-0" style="padding-left:4px;">
					<div class="col-12">
						<input type="radio" name="category" id="" class="mr-1"> Publish (Publish your schedule to everyone)
					</div>
					<div class="col-12 ">
						<input type="radio" name="category" id="" class="mr-1"> Private (Only date and time of the schedule is open to everyone)
					</div>
					<div class="col-12">
						<input type="radio" name="category" id="" class="mr-1"> Hide completely (Make all schedules private)
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 mt-10 pb-10 border-bottom">
			<div class="row">
				<div class="col-3 font-size14 text-left font-weight-bold pt-1">Participating users</div>
				<div class="col-9 font-size14 text-left">
					<div class="row">
						<div class="col-6 text-left pt-2">
							Shuhei Takahashi
						</div>
						<div class="col-6 text-right pt-2">
							<a href="" class="btn btn-outline-secondary no-no-underline-black font-size12">Participating user selection </a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 mt-10 pb-10 border-bottom">
			<div class="row">
				<div class="col-3 font-size14 text-left font-weight-bold pt-1">Facility</div>
				<div class="col-9 font-size16 text-left font-weight-bold text-right">
					<a href="" class="btn btn-outline-secondary no-no-underline-black font-size12">Equipment selection</a>
				</div>
			</div>
		</div>
		<div class="col-12 mt-10 text-center">
			<a href="" class="btn btn-add no-no-underline-white font-size16 mr-10">Add & Close</a>
			<a href="" class="btn btn-reset no-no-underline-white font-size16">Reset</a>
		</div>
	</div>
	<input type="hidden" id="year-sale" value="">
	<input type="hidden" id="month-sale" value="">
	<input type="hidden" id="day-sale" value="">
</div>