<div class="col-12" id="add-more-<?= $number ?>">
	<div class="col-12 text-right text-danger">
		<a onclick="javascript:closeAddmore(<?= $number ?>)" style="cursor:pointer;"><i class="fa fa-times" aria-hidden="true"></i></a>
	</div>
	<div class="col-12">
		<label class="label-input">Job name</label>
		<input type="text" name="jobName[<?= $number ?>]" class="form-control" value="<?= $job['jobName'] ?>" required>
	</div>
	<div class="col-12 mt-10 mb-40">
		<label class="label-input">Client</label>
		<select type="text" name="client[<?= $number ?>]" class="form-control" required>
			<?php
			if (isset($clients) && count($clients) > 0) {
				foreach ($clients as $client) : ?>
					<option value="<?= $client['clientId'] ?>"><?= $client["clientName"] ?></option>
			<?php
				endforeach;
			}
			?>
		</select>
	</div>
	<hr>
</div>