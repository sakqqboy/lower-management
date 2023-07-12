 <!-- css in video -->

 <div class="row" style="margin-bottom: 20px;">
 	<div class="col-lg-12 video-header text-center">VIDEOS</div>
 	<?php
		//throw new Exception(print_r($continentCountry, true));

		if (isset($continentCountry) && count($continentCountry) > 0) {
			foreach ($continentCountry as $continentName => $country) :
				if (count($country) > 0) { ?>
 				<div class="col-lg-12 col-12 continent-head"><u><?= $continentName ?></u></div>
 				<?php
					foreach ($country as $countryId => $c) : ?>

 					<div class="col-lg-3 col-md-6 col-sm-6 col-6 video-country text-center">
 						<a href="<?= $c["link"] ?>" class="flag-a">

 							<img src="<?= Yii::$app->homeUrl . $c["flag"] ?>" class="video-image" />
 							<div class="col-lg-12 video-text text-center">

 								<?= $c["name"]
									?>
 							</div>

 						</a>

 					</div>

 			<?php
					endforeach;
				} ?>
 			<div class="col-lg-12">
 				<hr>
 			</div>
 	<?php
			endforeach;
		} ?>
 </div>