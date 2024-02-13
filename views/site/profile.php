<?php

use yii\helpers\Html;

$this->title = 'User Profile';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-profile">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Добрый день, <?= Html::encode($username) ?>!</p>

    <p><?= Html::a('Logout', ['site/logout'], ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>
</div>
