<?php

use yii\bootstrap4\ActiveField;
use yii\bootstrap4\ActiveForm;
?>
<?php $form = ActiveForm::begin([
    'id' => 'login-form',
    'action' => Yii::$app->homeUrl . 'site/login',
    'method' => 'post'
]);
$this->title = 'Log in';
?>
<div class="col-12 login-page">
    <div class="row">
        <div class="col-12 text-left mt-30 system-name">
            Lower Management
        </div>
        <div class="offset-lg-4 offset-md-3 offset-sm-1 col-lg-4 col-md-6 col-sm-10 col-12 bg-white login-box text-center">
            <h2 class="mt-20">Log in</h2>
            <div class="col-12 mt-50 login-text">
                <div class="row">
                    <div class="col-4 text-right pt-1">username : </div>
                    <div class="col-8 text-left"><input type="text" name="LoginForm[username]" class="form-control login-input" placeholder="TCF Email" required></div>
                </div>
            </div>
            <div class="col-12 text-left mt-40 login-text">
                <div class="row">
                    <div class="col-4 text-right pt-1">password : </div>
                    <div class="col-8 text-left"><input type="password" name="LoginForm[password]" class="form-control login-input" required></div>
                </div>
            </div>
            <div class="offset-lg-4 offset-md-3 offset-sm-2 offset-2 col-lg-4 col-md-6 col-sm-8 col-8 mt-40">
                <!-- <a class="btn btn-primary btn-login" id="login-btn">Log in</a> -->
                <button type="submit" class="btn btn-primary btn-login" id="login-btn">Log in</button>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>