<?php

use common\models\ModelMaster;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;

$this->title = 'Create Job';
?>
<div class="body-content pt-20 container">
	<div class="col-12">
		<?php $form = ActiveForm::begin([
			'id' => 'create-job-form',
			'method' => 'post',
			'options' => [
				'enctype' => 'multipart/form-data',
			],
			'action' => Yii::$app->homeUrl . 'job/clone/save-copy'

		]); ?>

		<div class="col-12 pt-20 create-empolyee-box pb-20">
			<div class="row">
				<div class="col-12 text-center">
					<span class="font-size28 "><b>Copy job to other clients</b></span>
				</div>
				<div class="col-12 border pb-20 mt-20">
					<div class="col-12 mb-20" id="firstCleint">
						<div class="col-12">
							<div class="col-12 mt-20">
								<label class="label-input">Job name</label>
								<input type="text" name="jobName[0]" class="form-control" value="<?= $job['jobName'] ?>" required>
							</div>
							<div class="col-12 mt-10">
								<label class="label-input">Client</label>
								<select type="text" name="client[0]" class="form-control" required>
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
						</div>
					</div>
					<div class="col-12 mt-40">
						<hr>
					</div>
					<div class="col-12" id="otherClient">

					</div>
					<div class="col-12 text-right  mt-20">
						<a href="javascript:addMoreClient(<?= $job['jobId'] ?>)" class="btn button-sky" type="submit"><i class="fa fa-plus" aria-hidden="true"></i></a>
					</div>
					<input id="number" value="1" type="hidden">
					<input id="jId" name="jobId" value="<?= $job['jobId'] ?>" type="hidden">
					<div class="col-12 text-right  mt-20">
						<input type="hidden" id="jobId" value="<?= $job['jobId'] ?>">
						<a href="<?= Yii::$app->homeUrl ?>job/detail/complete-job/<?= ModelMaster::encodeParams(['jobId' => $job['jobId']]) ?>" class="btn button-red mr-10"><i class="fa fa-times mr-10" aria-hidden="true"></i>Cancel</a>
						<button class="btn button-blue" type="submit"><i class="fa fa-clone mr-10" aria-hidden="true"></i>Start copy</button>
					</div>
				</div>



			</div>
		</div>
		<?php ActiveForm::end(); ?>
	</div>
</div>