<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
	<h1><?= Html::encode($this->title) ?></h1>

	<p>Please fill out the following fields to login:</p>

    <?php $form = ActiveForm::begin([
                                        'id' => 'login-form',
                                        'layout' => 'horizontal',
                                        'fieldConfig' => [
                                            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
                                            'labelOptions' => ['class' => 'col-lg-1 control-label'],
                                        ],
                                    ]); ?>

    <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput() ?>

	<div class="form-group">
		<div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
		</div>
	</div>

    <?php ActiveForm::end(); ?>
	<!-- Show error message for invalid login -->
    <?php if ($model->hasErrors()): ?>
		<div class="alert alert-danger">
            <?php foreach ($model->getErrors() as $attribute => $errors): ?>
                <?php foreach ($errors as $error): ?>
					<p><?= $error ?></p>
                <?php endforeach ?>
            <?php endforeach ?>
		</div>
    <?php endif ?>
</div>
