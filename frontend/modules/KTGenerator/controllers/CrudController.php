<?php

namespace backend\modules\KTGenerator\controllers;

use backend\modules\KTGenerator\models\CrudGenerator;
//use yii\gii\generators\crud\Generator;
use backend\modules\KTGenerator\gii\templates\crud\Generator;
use Yii;

class CrudController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = new CrudGenerator();
        $tables = [];

        if (isset($_POST['CrudGenerator'])) {

            $handle = fopen('/tmp/crud_costfit', 'w+');
            fwrite($handle, print_r($_POST['CrudGenerator'], true));
            fclose($handle);

            $modelPath = \Yii::getAlias($_POST['CrudGenerator']['modelPath']);
            $modelNameSpace = $_POST['CrudGenerator']['modelNamespace'];
            $controllerNameSpace = $_POST['CrudGenerator']['controllerNamespace'];
            $files = scandir($modelPath);
            foreach ($files as $file) {
                if ($file == '.' || $file == '..' || substr($file, -4, 4) != '.php') {
                    continue;
                }
                $modelName = substr($file, 0, -4);

//                if ($modelName == 'ContactForm' || $modelName == 'LoginForm' || $modelName == 'ModelMaster' || $modelName == 'User')
//                    continue;

                $modelClass = $modelNameSpace . '\\' . $modelName;
                $controllerClass = $controllerNameSpace . '\\' . $modelName . 'Controller';
                $searchModelClass = $modelNameSpace.'\search\\'.$modelName;
//                echo $modelClass . '<br />';
//                echo $controllerClass . '<br />';

                $generator = new Generator();
                $generator->modelClass = $modelClass;
                $generator->controllerClass = $controllerClass;
                $generator->baseControllerClass = $_POST['CrudGenerator']['baseControllerClass'];
                $generator->enablePjax = 1;
                $generator->searchModelClass = $searchModelClass;
                $generator->templates['backend'] = Yii::getAlias('@backend/modules/KTGenerator/gii/templates/crud/backend');
                $generator->template = 'backend';

                $fs = $generator->generate();
                $answers = [];
                foreach ($fs as $f) {
                    $answers[$f->id] = 1;
                }

                $result = '';
                if ($generator->save($fs, $answers, $result)) {
                    $tables[] = [
                        'modelClass' => $modelClass,
                        'controllerClass' => $controllerClass,
                        'searchModelClass' => $searchModelClass,
                    ];
                }


            }
        }

        return $this->render('index', compact('model', 'tables'));
    }

}
