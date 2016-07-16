<?php
namespace common\rbac;

use common\models\User;
use Yii;
use yii\helpers\ArrayHelper;
use yii\rbac\Rule;

/**
 * Checks if authorID matches user passed via params
 */
class UserGroupRule extends Rule
{
    public $name = 'userGroup';

    public function execute($user, $item, $params)
    {
        $user = ArrayHelper::getValue($params, 'user', User::findOne($user));
        if ($user) {
            $role = $user->role;
            if ($item->name === User::ROLE_ADMIN) {
                return $role == User::ROLE_ADMIN;
            }
            elseif ($item->name === User::ROLE_MANAGER) {
                return $role == User::ROLE_ADMIN|| $role == User::ROLE_MANAGER;
            }
            elseif ($item->name === User::ROLE_USER) {
                return $role == User::ROLE_ADMIN || $role == User::ROLE_MANAGER || $role == User::ROLE_USER;
            }
        }
        return false;
    }
}

