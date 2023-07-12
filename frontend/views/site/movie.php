<?php

use common\models\ModelMaster;
?>
<div style="padding-top: 23px;"></div>
<div class="row video-big-box">
	<div class="col-lg-12" style="height: 50px;margin-bottom:5px;">
		<a href="<?= Yii::$app->homeUrl ?>video" style="text-decoration-line: none;color:white">
			<h3><b>MOVIE</b></h3>
		</a>
	</div>

	<div class="container">
		<?php



		$i = 0;
		if (isset($videos) && count($videos) > 0) {
			foreach ($videos as $questvideoionId => $q) : ?>
				<div class="col-lg-12 home-video-box text-left">
					<a href="#" class="question-link">
						<?= $q ?>
						<img src="<?= Yii::$app->homeUrl . $flag[$questionId] ?>" class="flag-head">
					</a>
				</div>
			<?php
			endforeach;
		} else {
			$i = 0;
			while ($i < 10) {
			?>

				<div class="col-lg-12 home-video-box text-left">
					<a href="#" class="question-link">
						Coming Soon . . .
					</a>
				</div>
		<?php
				$i++;
			}
		}
		?>

	</div>

</div>