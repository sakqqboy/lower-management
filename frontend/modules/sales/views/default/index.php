<?php
$this->title = 'Schedule'
?>
<div class="body-content pt-20 mb-20">
    <?= $this->render('filter', [
        "selectDate" => $selectDate,
        "branch" => $branch,
        "year" => $year,
        "month" => $month,
        "selectDate" => $selectDate,
        "salesActivity" => isset($salesActivity) ? $salesActivity : null,
        "existingMeeting" => isset($existingMeeting) ? $existingMeeting : null,
        "internalMeeting" => isset($internalMeeting) ? $internalMeeting : null,
        "other" =>  isset($other) ? $other : null,
    ]) ?>
</div>
<div class="col-12">
    <div class="row text-center mb-10">
        <div class="title-day">Monday</div>
        <div class="title-day">Tuesday</div>
        <div class="title-day">Wednesday</div>
        <div class="title-day">Thursday</div>
        <div class="title-day">Friday</div>
        <div class="title-day">Saturday</div>
        <div class="title-day">Sunday</div>
    </div>
</div>
<div class="col-12" id="result-date">
    <?php
    if (isset($dateValue) && count($dateValue) > 0) {
        $totalCount = 0;
        $day = 1;
        $other = '';
        foreach ($dateValue as $index => $value) :
            $dateArr = explode('-', $value["date"]);
            $day = (int)$dateArr[2];
            $month = $dateArr[1];
            $year = $dateArr[0];
            if ((int)$month != (int)$selectMonth) {
                $other = "other-month";
            } else {
                $other = '';
            }
            if (($totalCount % 7) == 0) { ?>
                <div class="row">
                <?php
            }
            if ($value["date"] == date('Y-m-d 00:00:00')) {
                $box = 'sub-box-today';
            } else {
                $box = 'sub-box';
            }
                ?>
                <div class="big-box-day" onclick="javasript:showAddSchedule(<?= $year . ',' . $month . ',' . $day ?>)">
                    <div class="<?= $box ?> <?= $other ?>">
                        <div class="date-number text-right"><?= $day ?></div>

                    </div>
                </div>
                <?php
                $totalCount++;
                if (($totalCount % 7) == 0) { ?>
                </div>
    <?php
                }
            endforeach;
        }
    ?>
</div>
<?= $this->render('add_schedule') ?>