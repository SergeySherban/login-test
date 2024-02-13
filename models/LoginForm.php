<?php

namespace app\models;

use Yii;
use yii\base\Model;

class LoginForm extends Model
{
    public $username;
    public $password;

    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
        ];
    }

    /**
     * @return bool
     */
    public function login(): bool
    {
        $username = $this->username;

        if ($this->isUserBlocked($username)) {
            $this->addError('time', 'Попробуйте еще раз через ' . $this->getTimeLeft($username) . ' секунд');
            return false;
        }

        $usersFile = Yii::getAlias('@runtime') . '/users.txt';

        if (file_exists($usersFile)) {
            $users = file($usersFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

            foreach ($users as $userData) {
                [$user, $pass] = explode(',', $userData);

                if ($user === $this->username && password_verify($this->password, $pass)) {
                    Yii::$app->session->set('username', $user);
                    $this->resetLoginAttempts($username);

                    return true;
                }
            }
        }

        $this->increaseLoginAttempts($username);
        $this->addError('password', 'Неверные данные');

        return false;
    }

    /**
     * @return bool
     */
    public static function isUserLoggedIn(): bool
    {
        return Yii::$app->session->has('username');
    }

    /**
     * @param $username
     *
     * @return bool
     */
    public function logout($username): bool
    {
        $usersFile = Yii::getAlias('@runtime') . '/users.txt';

        if (file_exists($usersFile)) {
            $users = file($usersFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

            foreach ($users as $userData) {
                [$user, $pass] = explode(',', $userData);

                if ($user === $username) {
                    Yii::$app->session->remove('username');
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param $username
     *
     * @return bool
     */
    protected function isUserBlocked($username): bool
    {
        $blockedUntil = Yii::$app->session->get('blockedUntil_' . $username);

        return $blockedUntil && $blockedUntil > time();
    }

    /**
     * @param $username
     *
     * @return int|mixed
     */
    protected function getTimeLeft($username)
    {
        $blockedUntil = Yii::$app->session->get('blockedUntil_' . $username);

        return $blockedUntil ? $blockedUntil - time() : 0;
    }

    /**
     * @param $username
     *
     * @return void
     */
    protected function increaseLoginAttempts($username)
    {
        $attempts = Yii::$app->session->get('loginAttempts_' . $username, 0);
        Yii::$app->session->set('loginAttempts_' . $username, $attempts + 1);

        if ($attempts >= 1) {
            Yii::$app->session->set('blockedUntil_' . $username, time() + 300);
            Yii::$app->session->remove('loginAttempts_' . $username);
        }

    }

    /**
     * @param $username
     *
     * @return void
     */
    protected function resetLoginAttempts($username)
    {
        Yii::$app->session->remove('loginAttempts_' . $username);
        Yii::$app->session->remove('blockedUntil_' . $username);
    }
}
