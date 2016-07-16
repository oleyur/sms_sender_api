<?php
namespace console\controllers;

use common\models\User;
use common\rbac\UserGroupRule;
use Yii;
use yii\console\Controller;
use yii\helpers\Console;

class RbacController extends Controller{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        $userRule = new UserGroupRule;
        $auth->add($userRule);

        $user = $auth->createRole(User::ROLE_USER);
        $user->ruleName = $userRule->name;
        $auth->add($user);

        $manager = $auth->createRole(User::ROLE_MANAGER);
        $manager->ruleName = $userRule->name;
        $auth->add($manager);
        $auth->addChild($manager, $user);

        $admin = $auth->createRole(User::ROLE_ADMIN);
        $admin->ruleName = $userRule->name;
        $auth->add($admin);
        $auth->addChild($admin, $manager);

        Console::output('Success! RBAC roles has been added.');
    }
} 