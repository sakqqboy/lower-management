<div class="body-content pt-30">
    <div class="col-12 chart-table-title">
        Charge in the balance of uncollected Fee (by month of occurence)
    </div>
    <div class="col-12 mt-10">
        <table class="table table-hover">
            <thead class="chart-table-head">
                <tr>
                    <th></th>
                    <th colspan="12" class="text-center" style="border-right:lightgray solid thin;"><b><?= $lastYear ?></b></th>
                    <th colspan="12" class="text-center"><b><?= $thisYear ?></b></th>
                </tr>
                <tr>
                    <th class="first-column">Team/month</th>
                    <?php

                    use common\models\ModelMaster;
                    use frontend\models\lower_management\Team;

                    foreach ($month as $year => $yearMonth) :
                        $yearShort = substr($year, -2);
                        foreach ($yearMonth as $monthValue => $monthText) : ?>
                            <th><?= $monthText ?></th>
                    <?php
                        endforeach;
                    endforeach;
                    ?>
                </tr>
            </thead>
            <tbody class="chart-table-body">
                <?php
                if (isset($jobData) && count($jobData) > 0) {
                    $i = 0;
                    foreach ($jobData as $teamId => $jobYear) : ?>
                        <tr>
                            <td class="border-right"><?= Team::teamName($teamId) ?></td>
                            <?php
                            $monthList = ModelMaster::month();
                            foreach ($monthList as $montValue => $montext) :
                                $montInt = (int)$montValue;
                            ?>

                                <td class="border-right text-right">
                                    <?= isset($jobYear[$lastYear][$montInt]) ? number_format($jobYear[$lastYear][$montInt], 2) : 0 ?>
                                </td>
                            <?php

                            endforeach;
                            //throw new Exception(print_r($jobYear, true));
                            foreach ($monthList as $montValue => $montext) :
                                $montInt = (int)$montValue;
                            ?>
                                <td class="border-right text-right">
                                    <?= isset($jobYear[$thisYear][$montInt]) ? number_format($jobYear[$thisYear][$montInt], 2) : 0 ?>
                                </td>
                            <?php

                            endforeach; ?>
                        </tr>
                <?php
                    endforeach;
                }
                ?>

            </tbody>
            <tfoot class="chart-table-footer">
                <tr>
                    <td class="border-right">
                        <b>Total</b>
                    </td>
                    <?php
                    $monthList = ModelMaster::month();
                    $totalLast = 0;
                    $totalThis = 0;
                    foreach ($monthList as $montValue => $montext) :
                        $montInt = (int)$montValue;
                    ?>

                        <td class="border-right text-right">
                            <?= isset($total[$lastYear][$montInt]) ? number_format($total[$lastYear][$montInt], 2) : 0 ?>
                        </td>
                    <?php
                        $totalLast += isset($total[$lastYear][$montInt]) ? $total[$lastYear][$montInt] : 0;
                    endforeach;
                    foreach ($monthList as $montValue => $montext) :
                        $montInt = (int)$montValue;
                    ?>
                        <td class="border-right text-right">
                            <?= isset($total[$thisYear][$montInt]) ? number_format($total[$thisYear][$montInt], 2) : 0 ?>
                        </td>
                    <?php
                        $totalThis += isset($total[$thisYear][$montInt]) ? $total[$thisYear][$montInt] : 0;
                    endforeach;
                    ?>
                </tr>
                <tr>
                    <td><b>Total Year</b></td>
                    <td colspan="12" class="text-right border-right"><?= number_format($totalLast, 2) ?></td>
                    <td colspan="12" class="text-right"><?= number_format($totalThis, 2) ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="col-12 mt-10 mb-40">
        <?= $this->render('line_chart', [
            "chartData" => $chartData,
            "monthText" => $monthTextChart
        ]) ?>
    </div>
    <div class="col-12 mt-10 mb-40">
        <?= $this->render('pie_chart', [
            "chartPie" => $chartPie,
            "monthText" => $monthTextChart
        ]) ?>
    </div>
</div>