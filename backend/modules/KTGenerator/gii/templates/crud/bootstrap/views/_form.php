<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator common\gii\templates\crud\Generator */

/* @var $model \yii\db\ActiveRecord */
$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\MaskedInput;
use common\models\areawow;
use yii\jui\DatePicker;
<?php
foreach ($generator->getColumnNames() as $attribute) {
    if (in_array($attribute, $safeAttributes)) {
        $tableSchema = $generator->getTableSchema();
        $column = $tableSchema->columns[$attribute];
        if (strpos($column->name, "Id") !== FALSE) {
            echo $generator->generateModelUse($attribute) . " \n";
        }
    }
}
?>

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form">

    <?= "<?php " ?>$form = ActiveForm::begin([
    'options' => ['class' => 'panel panel-default form-horizontal', 'enctype' => 'multipart/form-data'],
    'fieldConfig' => [
    'template' => '{label}<div class="col-sm-9">{input}</div>',
    'labelOptions'=>[
    'class'=>'col-sm-3 control-label'
    ]
    ]
    ]); ?>

    <div class="panel-heading">
        <span class="panel-title"><?= "<?=" ?>$title?></span>
    </div>

    <div class="panel-body">
        <?php
        echo "\t\t<?= \$form->errorSummary(\$model)?>\n\n";
        foreach ($generator->getColumnNames() as $attribute) {
            if (in_array($attribute, $safeAttributes)) {
                $tableSchema = $generator->getTableSchema();
                $column = $tableSchema->columns[$attribute];
                if ($column->name !== 'createDateTime' && $column->name !== 'updateDateTime') {
                    echo "\t\t<?= " . $generator->generateActiveField($attribute) . " ?>\n\n";
                }
            }
        }
        ?>
        <?php if (class_exists($model->className() . "Lang")): ?>
            <?= "<?= " ?> $this->render("/share/_lang", ['model' => $model, 'langModel' => new <?= $model->className(); ?>Lang()]); ?>
        <?php endif; ?>
        <div class="form-group">
            <div class="col-sm-9 col-sm-offset-3">
                <?= "<?= " ?>Html::submitButton($model->isNewRecord ? <?= $generator->generateString('Create') ?> : <?= $generator->generateString('Update') ?>, ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>
    </div>
    <?= "<?php " ?>ActiveForm::end(); ?>

</div>
