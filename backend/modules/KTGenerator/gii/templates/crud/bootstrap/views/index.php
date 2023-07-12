<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

echo "<?php\n";
?>

use yii\helpers\Html;
use <?= $generator->indexWidgetType === 'grid' ? "yii\\grid\\GridView" : "yii\\widgets\\ListView" ?>;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>;
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHeader'] = Html::encode($this->title);
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-index">

    <?php if (!empty($generator->searchModelClass)): ?>
        <?= "    <?php " . ($generator->indexWidgetType === 'grid' ? "// " : "") ?>echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php endif; ?>

    <?= "<?php Pjax::begin(['id' => 'employee-grid-view']); ?>\n" ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-6"><?= "<?=" ?>$this->title?></div>
                <div class="col-md-6">
                    <div class="btn-group pull-right">
                        <?= "<?= " ?>Html::a(<?= $generator->generateString('<i class=\'glyphicon glyphicon-plus\'></i> Create {modelClass}', ['modelClass' => Inflector::camel2words(StringHelper::basename($generator->modelClass))]) ?>, ['create'], ['class' => 'btn btn-success btn-xs']) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <?php if ($generator->indexWidgetType === 'grid'): ?>
                <?= "<?= " ?>GridView::widget([
                'layout' => "{summary}\n{pager}\n{items}\n{pager}\n",
                'dataProvider' => $dataProvider,
                'pager' => [
                'options' => ['class' => 'pagination pagination-xs']
                ],
                'options' => [
                'class' => 'table-light'
                ],
                <?= !empty($generator->searchModelClass) ? "'filterModel' => \$searchModel,\n        'columns' => [\n" : "'columns' => [\n"; ?>
                ['class' => 'yii\grid\SerialColumn'],

                <?php
                $count = 0;
                if (($tableSchema = $generator->getTableSchema()) === false) {
                    foreach ($generator->getColumnNames() as $name) {
                        if (++$count < 6):
                            echo "\t\t\t\t\t'" . $name . "',\n";
                        else:
                            echo "\t\t\t\t\t// '" . $name . "',\n";
                        endif;
                    }
                } else {
                    foreach ($tableSchema->columns as $column) {
                        $format = $generator->generateColumnFormat($column);
                        if (++$count < 6):
                            ?>
                            <?php echo "\t\t\t\t\t'" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n"; ?>
                            <?php
                        else:
                            echo "\t\t\t\t\t// '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
                        endif;
                    }
                }
                ?>
                ['class' => 'yii\grid\ActionColumn',
                'header'=>'Actions',
                <?php
//                throw new \yii\base\Exception(print_r($tableSchema->foreignKeys, true));
                $templateStr = '{view} {update} {delete}';
                $btnStr = "";
//                echo print_r($tableSchema->foreignKeys, true);
                if (isset($tableSchema->foreignKeys) && count($tableSchema->foreignKeys) > 0) {
                    foreach ($tableSchema->foreignKeys as $refs) {
                        $refTable = $refs[0];
                        $refTableArray = explode("_", $refTable);
                        $refClassName = "";
                        $i = 0;
                        foreach ($refTableArray as $refName) {
                            if ($i != 0) {
                                $refClassName.= ucfirst($refName);
                            } else {
                                $refClassName.= $refName;
                            }
                            $i++;
                        }
                        $templateStr.= " {" . $refClassName . "}";
                        $controllerName = (class_exists($refClassName . "Controller")) ? $refClassName . "Controller" : "manage";
                        $btnStr .= "\n'$refClassName' => function(\$url, \$model) {
                            return Html::a('<br><u>" . ucfirst($refClassName) . "</u>', \n['/$refClassName/" . $controllerName . "', '" . $tableSchema->primaryKey[0] . "' => \$model->" . $tableSchema->primaryKey[0] . "], \n[
                                'title' => Yii::t('app', 'Change today\'s lists'),]);},";
                    }
                }
                ?>
                'template' => '<?= $templateStr; ?>',
                'buttons'=> [<?= $btnStr ?>]
                ],
                ],
                ]); ?>
            <?php else: ?>
                <?= "<?= " ?>ListView::widget([
                'dataProvider' => $dataProvider,
                'itemOptions' => ['class' => 'item'],
                'itemView' => function ($model, $key, $index, $widget) {
                return Html::a(Html::encode($model-><?= $nameAttribute ?>), ['view', <?= $urlParams ?>]);
                },
                ]) ?>
            <?php endif; ?>
        </div>
    </div>
    <?= "<?php Pjax::end(); ?>\n" ?>
</div>
