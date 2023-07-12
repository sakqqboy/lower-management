<?php



$this->title = 'Profile'
?>
<div class="body-content pt-20">
    <div class="col-12">
        <div class="row">
            <div class="col-lg-10">
                <?php
                if (isset($kpi) && count($kpi) > 0) {
                    echo  $this->render('kpi', [
                        "kpi" => $kpi
                    ]);
                }
                ?>
                <?= $this->render('job', [
                    "needs" => $needs,
                    "nearlies" => $nearlies,
                    "inprocess" => $inprocess,
                    "completes" => $completes,
                    "inHand" => $inHand
                ]) ?>

            </div>
            <div class="col-lg-2 profile-site">
                <?= $this->render('user_info', ["employee" => $employee]) ?>
            </div>
        </div>
    </div>
</div>