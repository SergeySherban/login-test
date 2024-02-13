<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class User extends ActiveRecord
{
    public static function findByUsername($username)
    {
        $users = self::getUsers();
        return $users[$username] ?? null;
    }

    // Get all users from users.txt
    private static function getUsers()
    {
        $usersFile = Yii::getAlias('@app') . '/runtime/users.txt';
        $users = [];

        if (file_exists($usersFile)) {
            $lines = file($usersFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                $data = explode(',', $line);
                if (count($data) == 2) {
                    $users[$data[0]] = [
                        'username' => $data[0],
                        'password' => $data[1],
                    ];
                }
            }
        }

        return $users;
    }
}
