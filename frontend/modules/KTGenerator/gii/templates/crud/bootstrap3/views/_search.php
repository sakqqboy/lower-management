<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->searchModelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-search well">

    <?= "<?php " ?>$form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['data-pjax' => true], //add this line to use ajax search
    ]); ?>

    <div class="input-group">
        <span class="input-group-btn">
            <?='<?= '?>Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        </span>
        <?='<?= '?>Html::activeTextInput($model, 'searchText', ['class'=>'form-control'])?>
    </div>

    <?= "<?php " ?>ActiveForm::end(); ?>

</div>
