<?php
use yii\widgets\ActiveForm;
?>
<p>
    <a href="<?=Yii::$app->homeUrl?>kt-generator" class="btn btn-default">&lt;&lt; Go Back To KT Generator</a>
</p>
<div class="ModelGenerator-default-index">
    <h1 class="page-header">Model Generator</h1>

    <div class="panel panel-default">
        <div class="panel-heading">Form</div>
        <div class="panel-body">
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'dbName')->hint('db is default value'); ?>
            <?= $form->field($model, 'nameSpace')->hint('Ex. app\models'); ?>
            <?= $form->field($model, 'baseClass')->hint('Ex. app\models\ModelMaster'); ?>
            <?= $form->field($model, 'folderName')->hint('all model files will save in this folder'); ?>

            <?= \yii\helpers\Html::submitButton('Generate', ['class' => 'btn btn-primary', 'name' => 'preview']) ?>

            <?php ActiveForm::end(); ?>
        </div>
    </div>


    <?php if ($tables !== []): ?>

        <div class="panel panel-default">
            <div class="panel-heading"></div>
            <div class="panel-body">

                <table class="table table-striped table-bordered">
                    <tr>
                        <th>Table</th>
                        <th>Master Model</th>
                        <th>Model</th>
                    </tr>
                    <?php foreach ($tables as $table): ?>
                        <tr>
                            <td><?= $table['name'] ?></td>
                            <td><?= $table['master'] ?></td>
                            <td><?= $table['model'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>

            </div>
        </div>

    <?php endif; ?>
</div>
