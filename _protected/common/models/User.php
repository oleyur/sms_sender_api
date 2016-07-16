<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $oauth_client
 * @property string $oauth_client_user_id
 * @property string $email
 * @property string $auth_key
 * @property string $activation_key
 * @property integer $role
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property string $access_token For connection via rest api basic auth
 *
 * @property UserProfile $profile
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE   = 1;

    const ROLE_USER     = 'user';
    const ROLE_MANAGER  = 'manager';
    const ROLE_ADMIN    = 'admin';

    const EVENT_AFTER_SIGNUP = 'afterSignup';
    const EVENT_AFTER_LOGIN  = 'afterLogin';

    private $_password;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @return array
     */
    public function scenarios(){
        return ArrayHelper::merge(
            parent::scenarios(),
            [
                'oauth_create'=>[
                    'oauth_client', 'oauth_client_user_id', 'email', 'username', '!status', '!role'
                ]
            ]
        );
    }


    /**
      * @inheritdoc
      */
     public function rules()
     {
         return [
             [['username', 'email'], 'unique'],
             ['status', 'default', 'value' => self::STATUS_ACTIVE],
             ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE]],

             ['role', 'default', 'value' => self::ROLE_USER],
             ['role', 'in', 'range' => array_keys(self::getRoles())],
         ];
     }

    public function attributeLabels()
    {
        return [
            'username'   => Yii::t('common', 'Username'),
            'role'       => Yii::t('common', 'Role'),
            'email'      => Yii::t('common', 'E-mail'),
            'status'     => Yii::t('common', 'Status'),
            'created_at' => Yii::t('common', 'Created at')
        ];
    }

    public function getProfile(){
        return $this->hasOne(UserProfile::className(), ['user_id'=>'id']);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @param string $password
     * @return static|null
     */
    public static function findByUsernamePassword($username, $password)
    {
        $user = static::findOne([
            'username'      => $username,
            'status'        => self::STATUS_ACTIVE,
            'access_token'  => md5($username.$password),
        ]);

        return $user;
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        $expire = 60 * 60 * 24; // 1 day
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        if ($timestamp + $expire < time()) {
            // token expired
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status'               => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->getSecurity()->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->getSecurity()->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->getSecurity()->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * Returns user roles list
     * @param bool $role
     * @return array|mixed
     */
    public static function getRoles($role = false){
        $roles = [
            self::ROLE_USER    => Yii::t('common', 'User'),
            self::ROLE_MANAGER => Yii::t('common', 'Manager'),
            self::ROLE_ADMIN   => Yii::t('common', 'Admin'),
        ];
        return $role !== false ? ArrayHelper::getValue($roles, $role) : $roles;
    }

    /**
     * Returns user statuses list
     * @param bool $status
     * @return array|mixed
     */
    public static function getStatuses($status = false){
        $statuses = [
            self::STATUS_ACTIVE   => Yii::t('common', 'Active'),
            self::STATUS_INACTIVE => Yii::t('common', 'Inactive')
        ];
        return $status !== false ? ArrayHelper::getValue($statuses, $status) : $statuses;
    }

    /**
     * Creates user profile and application event
     */
    public function afterSignup(){
        SystemEvent::log('user', self::EVENT_AFTER_SIGNUP, [
            'username'   => $this->username,
            'email'      => $this->email,
            'created_at' => $this->created_at
        ]);
        $profile = new UserProfile();
        $profile->locale = Yii::$app->language;
        $this->link('profile', $profile);
        $this->trigger(self::EVENT_AFTER_SIGNUP);
    }

    /**
     *  Get any name for user
     * @return null|string
     */
    public function getPublicIdentity()
    {
        if($this->profile && $this->profile->getFullname()){
            return $this->profile->getFullname();
        }
        if($this->username){
            return $this->username;
        }
        return $this->email;
    }


    public function beforeSave($insert)
    {
        if($insert){
            $this->access_token   = Yii::$app->getSecurity()->generateRandomString();
            $this->activation_key = Yii::$app->getSecurity()->generateRandomString();
        }
        return parent::beforeSave($insert);
    }
}
