<input type="hidden" id="total-client" value="<?= count($clients) ?>">
<?php
if (isset($clients) && count($clients) > 0) {
	$i = 1;
	foreach ($clients as $cli) : ?>
		<div class="client-list-item col-12" onclick="javascript:clientJob(<?= $cli['clientId'] ?>,<?= $i ?>)" id="list-client-<?= $i ?>" style="background-color:<?= $i == 1 ? 'rgb(235, 235, 235)' : '' ?>">
			<?= $cli["clientName"] ?>
		</div>
<?php
		$i++;
	endforeach;
} ?>