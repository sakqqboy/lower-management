<?php

use yii\bootstrap4\ActiveForm;

$this->title = 'Category';
?>
<div class="body-content pt-20 container">
	<div class="row">
		<div class="col-12">
			Ex. monthly, yearly, quaterly, ..
		</div>
	</div>
	<table class="table table-hover">
		<tr class="text-center table-head">
			<td>No.</td>
			<td>Category</td>
			<td>Action</td>
		</tr>
		<?php $form = ActiveForm::begin([
			'id' => 'login-form',
			'action' => Yii::$app->homeUrl . 'setting/job-structure/create-category',
			'method' => 'post'
		]); ?>
		<tr>
			<td>
				<div class="text-success mt-10">
					<i class="fa fa-plus" aria-hidden="true"></i>
				</div>
			</td>
			<td class="border-right">
				<input type="text" name="categoryName" class="form-control" placeholder="Category Name" required>

			</td>
			<td class="text-center">
				<button class="button-blue button-md" tyle="submit">Create</button>
			</td>
		</tr>
		<?php ActiveForm::end(); ?>
		<?php
		if (isset($categories) && count($categories) > 0) {
			$i = 1;
			foreach ($categories as $category) :
		?>
				<tr id="category<?= $category["categoryId"] ?>">
					<td><?= $i ?></td>
					<td id="categoryName<?= $category["categoryId"] ?>"><?= $category["categoryName"] ?></td>
					<td class="text-center">
						<button class="button-yellow button-xs" onclick='javascript:toggleTr(<?= $category["categoryId"] ?>)'>
							<i class="fa fa-edit" aria-hidden="true"></i>
						</button>
						<button class="button-red button-xs" onclick='javascript:disableCategory(<?= $category["categoryId"] ?>)'>
							<i class="fa fa-times" aria-hidden="true"></i>
						</button>
					</td>
				</tr>
				<tr id="tr-edit<?= $category["categoryId"] ?>" style="display:none;">
					<td>
						<div class="mt-10">
							<i class="fa fa-edit" aria-hidden="true"></i>
						</div>
					</td>
					<td>
						<input type="text" id="categoryNameInput<?= $category["categoryId"] ?>" class="form-control" placeholder="Category Name" value="<?= $category["categoryName"] ?>">
					</td>

					<td class="text-center">
						<button class="button-green button-xs mt-10" onclick='javascript:updateCategory(<?= $category["categoryId"] ?>)'>
							<i class="fa fa-check" aria-hidden="true"></i>
						</button>
						<button class="button-red button-xs mt-10" onclick='javascript:toggleTr(<?= $category["categoryId"] ?>)'>
							<i class="fa fa-minus" aria-hidden="true"></i>
						</button>
					</td>
				</tr>
			<?php
				$i++;
			endforeach;
		} else { ?>
			<tr class="tr-no-data">
				<td colspan="3">Not set</td>
			</tr>
		<?php
		}
		?>
	</table>
</div>