<?php
namespace frontend\modules\user\models;

use common\models\User;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $password_repeat;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
//            ['username', 'required'],
            ['username', 'unique', 'targetClass'=>'\common\models\User', 'message' => Yii::t('frontend', 'This username has already been taken.')],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass'=> '\common\models\User', 'message' => Yii::t('frontend', 'This email address has already been taken.')],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            ['password_repeat', 'required'],
            ['password_repeat', 'compare', 'compareAttribute'=>'password', 'skipOnEmpty' => false, 'message'=>Yii::t('frontend', 'Passwords don\'t match.')],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username'=>Yii::t('frontend', 'Username'),
            'email'=>Yii::t('frontend', 'Email'),
            'password'=>Yii::t('frontend', 'Password'),
            'password_repeat'=>Yii::t('frontend', 'Confirm Password'),
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->status = User::STATUS_INACTIVE;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->save();
            $user->afterSignup();
            return $user;
        }

        return null;
    }
}
