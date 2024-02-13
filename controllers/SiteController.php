<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\models\LoginForm;

class SiteController extends Controller
{
    public $blockedUntil;
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (LoginForm::isUserLoggedIn()) {
            return $this->redirect(['profile']);
        }

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            Yii::$app->session->set('username', $model->username);
            return $this->redirect(['profile']);
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Profile action.
     *
     * @return string
     */
    public function actionProfile()
    {
        if (!LoginForm::isUserLoggedIn()) {
            return $this->redirect(['login']);
        }

        $username = Yii::$app->session->get('username');

        return $this->render('profile', [
            'username' => $username,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout(): Response
    {
        $model = new LoginForm();
        $username = Yii::$app->session->get('username');

        if ($model->logout($username)) {
            Yii::$app->user->logout();
        }

        return $this->goHome();
    }
}
