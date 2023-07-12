<?php

use frontend\models\lower_management\Branch;
use frontend\models\lower_management\Client;

?>
<div class="body-content pt-20">
	<div class="col-12 text-start client-detail-box">
		<div class="col-12">
			<h3>
				<b><?= $client["clientName"] ?></b>
			</h3>
			<hr>
		</div>
		<div class="row client-detail mt-40">
			<div class="col-lg-2 col-3 ">Branch</div>
			<div class="col-lg-10 col-9"><?= Branch::branchName($client["branchId"]) ?></div>
			<div class="col-lg-2 col-3 mt-20">Email</div>
			<div class="col-lg-10 col-9 mt-20"><?= $client["email"] ?></div>
			<div class="col-lg-2 col-3 mt-20">Tel 1</div>
			<div class="col-lg-10 col-9 mt-20"><?= $client["clientTel1"] ?></div>
			<div class="col-lg-2 col-3 mt-20">Tel 2</div>
			<div class="col-lg-10 col-9 mt-20"><?= $client["clientTel2"] ?></div>
			<div class="col-lg-2 col-3 mt-20">TaxId</div>
			<div class="col-lg-10 col-9 mt-20"><?= $client["taxId"] ?></div>
			<div class="col-lg-2 col-3 mt-20">Address</div>
			<div class="col-lg-10 col-9 mt-20"><?= $client["clientAddress"] ?></div>
			<div class="col-lg-2 col-3 mt-20">Status</div>
			<div class="col-lg-10 col-9 mt-20"><?= Client::clientStatus($client["status"]) ?></div>
		</div>

	</div>

</div>