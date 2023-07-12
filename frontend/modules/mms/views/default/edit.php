<div class="row mt-20">
	<div class="col-lg-6 col-md-6 col-12 mt-10 ">
		<label>Graph Name</label>
		<input type="input" class="form-control" placeholder="Graph name" name="title" value="<?= $chart['chartName'] ?>" required>
	</div>
	<div class="col-lg-6 col-md-6 col-12 mt-10 ">
		<label>Country</label>
		<select name="country" class="form-control">


			<?php

			use frontend\models\lower_management\Country;

			if ($chart["countryId"] != null) { ?>
				<option value="<?= $chart['countryId'] ?>"><?= Country::countryName($chart['countryId']) ?></option>
			<?php
			} else { ?>
				<option value="">All country</option>
				<?php }
			if (isset($country) && count($country) > 0) {
				foreach ($country as $c) : ?>
					<option value="<?= $c["countryId"] ?>"><?= $c["countryName"] ?></option>
			<?php
				endforeach;
			}
			?>
		</select>
	</div>
</div>
<!-- <div class="row col-12  mt-20">
	<label>Choose Graph style</label>
</div>
<div class="col-12 border" style="min-height:80px;padding-bottom:20px;">
	<div class="row">

		<div class="col-4 font-size16" style="padding-top:30px;">
			<input type="radio" name="graphType" value="1" class="mr-10 radio-md" id="type-line" required> Line Graph
		</div>
		<div class="col-4 font-size16" style="padding-top:30px;">
			<input type="radio" name="graphType" value="2" class="mr-10 radio-md" id="type-pie" required> Pie Graph
		</div>
		<div class="col-4 font-size16" style="padding-top:30px;">
			<input type="radio" name="graphType" value="3" class="mr-10 radio-md" id="type-bar" required> Bar Graph
		</div>
	</div>
</div> -->
<?php
if (!isset($pie)) {
?>
	<div class="col-12 row mt-20">
		<label>Graph setting</label>
	</div>
	<div class="col-12 border mt-10" style="height:150px;" id="other-chart">
		<div class="row">
			<div class="col-lg-3 col-4 mt-20 font-size16 text-right pt-10">
				Verical axis (Y)
			</div>
			<div class="col-lg-6 col-5 mt-20 font-size16">
				<input type="text" class="form-control" placeholder="Axis ( Y ) name" name="yName">

			</div>
			<div class="col-3 mt-20 font-size16">
				<select class="form-control" name="yUnit">
					<option value="">unit</option>
					<option value="1">1</option>
					<option value="2">1k</option>
					<option value="3">10k</option>
					<option value="4">100k</option>
				</select>
			</div>


		</div>
		<div class="row">
			<div class="col-lg-3 col-4 mt-20 font-size16 text-right pt-10">
				Herizontal axis (X)
			</div>
			<div class="col-lg-6 col-5 mt-20 font-size16">
				<select class="form-control" name="term" id="termType">
					<option value="">Term</option>
					<option value="1">Everyday</option>
					<option value="2">Every 5 days</option>
					<option value="3">Month</option>
					<option value="4">Year</option>
				</select>

			</div>
			<div class="col-3 mt-20 font-size16">
				<input type="text" class="form-control" name="startYear" id="first-year" placeholder="Start year" style="display:none;">
			</div>
		</div>
	</div>
<?php
}
?>
<div class="row col-12 mt-20">
	<label>Data type</label>
</div>
<div class="col-12 border mt-10 pt-20 pb-20">
	<input type="radio" name="dataType" value="1" class="mr-10 radio-md" required onchange="javascript:setDataType(1)" <?= $chart["dataType"] == 1 ? 'checked' : '' ?>> Value
	<input type="radio" name="dataType" value="2" class="mr-10 radio-md ml-20" required onchange="javascript:setDataType(2)" <?= $chart["dataType"] == 2 ? 'checked' : '' ?>> Percentage
	<input type="hidden" id="dataType" value="0">
</div>
<div class="col-12 border mt-10 pb-10" id="pie-chart" style="display:none;">
	<div class="col-lg-3 col-md-4 col-6 mt-10">
		<label>Number of pieces</label>
		<input type="text" value="" id="pie-piece" class="form-control" name="totalPiece">
	</div>
	<div class="col-12" id="show-pie">

	</div>
</div>