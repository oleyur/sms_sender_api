<?php

namespace frontend\modules\user\controllers;

use common\models\User;
use common\models\UserProfile;
use frontend\modules\user\models\LoginForm;
use frontend\modules\user\models\PasswordResetRequestForm;
use frontend\modules\user\models\ResetPasswordForm;
use frontend\modules\user\models\SignupForm;
use Yii;
use yii\base\Exception;
use yii\base\InvalidParamException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

class SignInController extends \yii\web\Controller
{

    public function actions()
    {
        return [
            'oauth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'successOAuthCallback'],
            ]
        ];
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['signup', 'success-signup', 'activate', 'login', 'request-password-reset', 'reset-password', 'oauth'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['signup', 'success-signup', 'activate',    'login', 'request-password-reset', 'reset-password', 'oauth'],
                        'allow' => false,
                        'roles' => ['@'],
                        'denyCallback'=>function(){
                            return Yii::$app->controller->redirect(['/user/default/profile']);
                        }
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actionLogin()
    {

        $model = new LoginForm();
        if (Yii::$app->request->isAjax) {
            $model->load($_POST);
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {

                $result = Yii::$app->mailer->compose('accountActivation', ['user' => $user])
                    ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->name])
                    ->setTo($user->email)
                    ->setSubject(Yii::t('frontend', 'User account activation'))
                    ->send();

                // display flash for user
                if (!$result) {
                    Yii::$app->session->setFlash('error', Yii::t("frontend", 'There was an error sending email'));
                }
                return $this->redirect('/user/sign-in/success-signup');

            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionSuccessSignup()
    {
        return $this->render('success-signup');
    }

    public function actionActivate($activation_key)
    {
        // get user
        /* @var User $user */
        $user = User::findOne(["activation_key" => $activation_key]);

        //if wrong activation_key
        if (empty($user)) {
            throw new NotFoundHttpException(Yii::t("frontend", 'Wrong activation key'));
        }

        // check is already activated
        if ($user->status !== User::STATUS_INACTIVE) {
            throw new NotFoundHttpException(Yii::t("frontend", 'Already activated'));
        }

        $user->status = User::STATUS_ACTIVE;
        $user->save();

        if(Yii::$app->user->login($user, 3600 * 24 * 30)){
            Yii::$app->session->setFlash('success', Yii::t('frontend', 'Your account has been successfully activated. Please fill your profile'));
            return $this->redirect('/user/default/profile');
        } else {
            throw new Exception('Unable to login user');
        }

//        return $this->render('success-activation');
    }

    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', Yii::t('frontend', 'Check your email for further instructions.'));
                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', Yii::t('frontend', 'Sorry, we are unable to reset password for email provided.'));
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', Yii::t('frontend', 'New password was saved.'));
            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * @param \yii\authclient\BaseClient $client
     *
     * @return bool
     * @throws Exception
     */
    public function successOAuthCallback($client)
    {
        // use BaseClient::normalizeUserAttributeMap to provide consistency for user attribute`s names
        $attributes = $client->getUserAttributes();
        $isNewUser = false;


        /** @var User $user */
        $user = User::find()->where([
            'oauth_client'          => $client->getName(),
            'oauth_client_user_id'  => ArrayHelper::getValue($attributes, 'id')
        ])->one();

        if(!$user){
            $isNewUser = true;

            $user = new User();
            $user->scenario = 'oauth_create';
            $user->username = sprintf('%s_%s', ArrayHelper::getValue($attributes, 'login', $client->getName()), time().rand(100,999));
            $user->email = ArrayHelper::getValue($attributes, 'email');
            $user->oauth_client = $client->getName();
            $user->oauth_client_user_id = ArrayHelper::getValue($attributes, 'id');
            $password = Yii::$app->security->generateRandomString(8);
            $user->generateAuthKey();
            $user->setPassword($password);

            if($user->save()){
                $user->afterSignup();

                // update profile for facebook oauth
                if($client->getName() == 'facebook'){
                    $this->updateProfileFromFacebook($user, $attributes);
                }

                $sentSuccess = Yii::$app->mailer->compose('oauth_welcome', ['user' => $user, 'password' => $password])
                    ->setSubject(Yii::t('frontend', '{app-name} | Your login information', [
                        'app-name'=>Yii::$app->name
                    ]))
                    ->setFrom([Yii::$app->params["adminEmail"] => Yii::$app->params["adminEmail"]])
                    ->setTo($user->email)
                    ->send();
                if($sentSuccess){
                    Yii::$app->session->setFlash('success', Yii::t('frontend', 'Welcome to {app-name}. Email with your login information was sent to your email.', [
                        'app-name'=>Yii::$app->name
                    ]));
                }
            } else {

                // We already have a user with this email. Do what you want in such case
                if(User::find()->where(['email'=>$user->email])->count()){
                    Yii::$app->session->setFlash('error', Yii::t('frontend', 'We already have a user with email {email}', [
                        'email'=>$user->email
                    ]));
                } else {
                    Yii::$app->session->setFlash('error', Yii::t('frontend', 'Error while oauth process.'));
                }
            };
        }
        if(Yii::$app->user->login($user, 3600 * 24 * 30)){
            if($isNewUser){
                return $this->redirect('/user/default/profile');
            }else{
                return true;
            }
        } else {
            throw new Exception('OAuth error');
        }
    }


    /**
     * @param User $user
     * @param array $attributes
     */
    private function updateProfileFromFacebook($user, $attributes){

        /** @var UserProfile $profile */
        $profile = $user->profile;
        $profile->refresh();

        $profile->firstname = ArrayHelper::getValue($attributes, 'first_name');
        $profile->lastname = ArrayHelper::getValue($attributes, 'last_name');

        // gender
        if(isset($attributes['gender'])){
            if($attributes['gender'] == 'male'){
                $profile->gender = UserProfile::GENDER_MALE;
            }else{
                $profile->gender = UserProfile::GENDER_FEMALE;
            }
        }


        // picture
        if(isset($attributes['picture']['data']['url'])){

            $ext = 'jpg';
            $path = $profile->generateFilePath($ext);

            $file = file_get_contents($attributes['picture']['data']['url']);
            if(!empty($file)){
                file_put_contents($path, $file);

                $profile->generateThumbnails($ext, $path);
            }
        }

        $profile->save(false);
    }

}
