<?php include 'layouts/header.php'; ?>

<div style="padding-left: 30px;min-height: 350px;background-color: white;margin-top:20px;font-size:16px;">

	<div style="margin-top:30px;">
		Dear : <b><?= $data["nickName"] ?></b><br><br>

		Good day! TCF members!<br>
		LMS system updates information to you.<br>
		Let’s work today! Below come from your task list on process.

	</div>
	<div style="margin-top:20px;line-height:35px;">
		<b>Branch :</b> <?= $data["branch"] ?><br>
		<b>Client name :</b> <?= $data["clientName"] ?><br>
		<b>Job name :</b> <?= $data["jobName"] ?><br>
		<b>Job Type :</b> <?= $data["jobType"] ?><br>
		<b>Job Category :</b> <?= $data["category"] ?><br>
		<b>Step Due :</b><?= $data["currentStepDueDate"] ?><br>
		<b>Target Due :</b> <?= $data["currentTargetDate"] ?><br>
		<b>PIC1 :</b> <?= $data["pic1"] ?><br>
		<b>PIC2 :</b> <?= $data["pic2"] ?><br>
		<b>Status :</b> <?= $data["status"] ?>
	</div>
	<div style="margin-top:20px;">
		Please login LMS, and check and update your information!<br>
		<a href="<?= $data["link"] ?>">Click here for check and update.</a>
	</div>
	<div style="margin-top:20px;">
		<hr>
	</div>



</div>
<?php include 'layouts/footer.php'; ?>