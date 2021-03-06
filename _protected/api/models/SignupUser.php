<?php
namespace api\models;

use common\models\User;
use common\models\UserProfile;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupUser extends Model
{
    public $username;
    public $email;
    public $password;

    public $firstname;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => Yii::t('frontend', 'This username has already been taken.')],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['firstname', 'string', 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => Yii::t('frontend', 'This email address has already been taken.')],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => Yii::t('frontend', 'Username'),
            'email'    => Yii::t('frontend', 'E-mail'),
            'password' => Yii::t('frontend', 'Password'),
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
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->save(false);

            $user->afterSignup();

            /* @var $user_profile UserProfile */
            $user_profile = UserProfile::find()->where(['user_id' => $user->id])->one();
            $user_profile->firstname = $this->firstname;
            $user_profile->lastname = $this->username;

            $user_profile->update(false);

            return [
                'user'         => $user,
                'user_profile' => $user_profile,
            ];
        }

        return null;

    }

}


/*

curl -X POST -d username=new_user11 -d email=mikola@dsf.df3 -d password=qwe012 http://craplocally.dev/api/sign-up


 */