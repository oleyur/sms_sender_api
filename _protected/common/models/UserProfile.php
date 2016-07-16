<?php

namespace common\models;

use common\components\behaviors\UploadFileBehavior;
use common\components\Site;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "user_profile".
 *
 * @property integer $user_id
 * @property integer $locale
 * @property string $firstname
 * @property string $middlename
 * @property string $lastname
 * @property string $picture
 * @property integer $gender
 *
 * @property User $user
 */
class UserProfile extends \yii\db\ActiveRecord
{
    const GENDER_MALE = 1;
    const GENDER_FEMALE = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_profile}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'gender'], 'integer'],
            [['gender'], 'in', 'range'=>[self::GENDER_FEMALE, self::GENDER_MALE]],
            [['firstname', 'middlename', 'lastname'], 'string', 'max' => 255],
            ['locale', 'default', 'value' => Yii::$app->language],
            //['locale', 'in', 'range' => array_keys(Site::getLangs())],
            ['picture', 'image', 'extensions' => 'jpg, jpeg, gif, png', 'skipOnEmpty' => true],
        ];
    }

    public function behaviors()
    {
        return [
            'imageUpload' => [
                'class'         => UploadFileBehavior::className(),
                'attributeName' => 'picture',
                'savePath'      => "@root/uploads/avatars",
                'url'           => "/uploads/avatars",
                "baseUrl"       => Yii::$app == "app-frontend"?Yii::$app->urlManager->baseUrl:"",
                'thumbnails' => [
                    "small"  => [215, 215],
                ]
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('common', 'User ID'),
            'firstname' => Yii::t('common', 'Firstname'),
            'middlename' => Yii::t('common', 'Middlename'),
            'lastname' => Yii::t('common', 'Lastname'),
            'picture' => Yii::t('common', 'Picture'),
            'gender' => Yii::t('common', 'Gender'),
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        Yii::$app->session->setFlash('forceUpdateLocale');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getFullName()
    {
        if($this->firstname || $this->lastname){
            return implode(' ', [$this->firstname, $this->lastname]);
        }
        return null;
    }

    /**
     * Returns genter type
     *
     * @param bool $param
     *
     * @return array|mixed
     */
    public static function getGenders($param = false){
        $params = [
            self::GENDER_MALE       => Yii::t('common', 'Male'),
            self::GENDER_FEMALE     => Yii::t('common', 'Female'),
        ];
        return $param !== false ? ArrayHelper::getValue($params, $param) : $params;
    }

}
