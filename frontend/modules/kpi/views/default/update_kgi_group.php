<?php

use common\models\ModelMaster;
use yii\bootstrap4\ActiveForm;

$this->title = 'Create KGI Group';

$form = ActiveForm::begin([
	'options' => [
		'class' => 'panel panel-default form-horizontal',
		'enctype' => 'multipart/form-data',
		'id' => 'kgiGroup',

	],
	'action' => Yii::$app->homeUrl . 'kpi/default/save-update-kgi-group'

]);
?>
<div class="body-content container">
	<div class="row">
		<div class="col-12 border create-empolyee-box pt-40 pb-40 mt-40" style="border-radius: 10px;">
			<div class="col-12 text-center font-size24 font-weight-bold">
				KGI Group
			</div>
			<div class="col-12 mt-10">
				<input type="text" name="kgiGroupName" class="form-control" placeholder="KGI GroupName" required value="<?= $kgiGroup['kgiGroupName'] ?>">
			</div>
			<div class="col-12 mt-10 text-right">
				<input type="hidden" name="kgiGroupId" value="<?= $kgiGroup['kgiGroupId'] ?>">
				<button type="submit" class="btn button-yellow">
					<i class="fa fa-edit mr-2" aria-hidden="true"></i>Submit
				</button>
			</div>

		</div>
	</div>
</div>
<?php ActiveForm::end(); ?>