<?php

namespace app\controllers;

use app\models\SaveWaitEmail;
use Yii;

use yii\web\Controller;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\widgets\ActiveForm;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\base\Exception;
use yii\base\Model;
use app\models\Business;
use app\models\Consumer;
use app\models\User;
use app\models\ZipCode;
use app\forms\RegistrationForm;
use app\forms\LoginForm;
use app\forms\RecoveryForm;
use app\forms\ResendForm;

class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['account', 'login', 'logout', 'register', 'recovery', 'reset', 'resend'],
                'rules' => [
                    [
                        'actions' => ['account', 'logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['login', 'register', 'recovery', 'reset', 'resend'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    'confirm' => ['get'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    /**
     * Redirect to main page of user account.
     */
    protected function goToMainPage()
    {
        return $this->redirect(['site/index']);
    }

    /**
     * Performs ajax validation.
     * @param Model $model
     */
    protected function performAjaxValidation(Model $model)
    {
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            echo json_encode(ActiveForm::validate($model));
            Yii::$app->end();
        }
    }

    /**
     * @param string $zip
     * @return int
     */
    public function actionCheckZip($zip) {
        $zipCode = new ZipCode();
        $rows = $zipCode->checkZipCode($zip);
        return $rows;
    }

    public function actionZipAddress() {
        if (Yii::$app->request->isPost) {
            $zip = ZipCode::getZipAddress(Yii::$app->request->post('zip'));
            if (empty($zip)) return null;
            try {
                return json_encode(array(
                    'city'  => $zip->city->name,
                    'state' => $zip->city->state->name
                ));
            } catch (Exception $e) {
                return null;
            }
        }
        return null;
    }

    /**
     * Confirmation email
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionConfirm()
    {
        $request = Yii::$app->request;
        $id = intval($request->get('id'));
        $user = User::findIdentity($id);
        if ($user === null) {
            throw new NotFoundHttpException();
        }

        if ($user->checkConfirmCode($id, $request->get('code'))) {
            $roles = Yii::$app->authManager->getRolesByUser(Yii::$app->user->id);
            foreach ($roles as $role => $obj) {
                if ($role == User::ROLE_CONS) {
                    //return $this->redirect(['user/promo-saved']);
                }
                if ($role == User::ROLE_BUSN) {
                    return $this->redirect(['user/account']);
                }
            }
        }
        return $this->goToMainPage();
    }

    public function actionRegister()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $waitEmail = new SaveWaitEmail();
        $waitEmail->scenario=SaveWaitEmail::SCENARIO_REGISTER;
        $model = new RegistrationForm();
        $this->performAjaxValidation($model);
        if ($model->load(Yii::$app->request->post()) && $model->register()) {
           // return $this->goToMainPage();
            return $this->render('register', ['waitEmail' => $waitEmail,'model'  => $model, 'singlePage' => true]);
        }
        return $this->render('register', ['waitEmail' => $waitEmail,'model'  => $model, 'singlePage' => true]);
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        $this->performAjaxValidation($model);
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $user = User::findIdentity(Yii::$app->user->id);
            if ($user === null) {
                throw new NotFoundHttpException();
            }
            $user = $user->getAccountByRole();
            if (is_string($user) && ($user === User::ROLE_ADMIN)) {
                return $this->redirect(['/admin/panel/index']);
            } else if ($user instanceof Business) {
                return $this->redirect(['user/account']);
            }
            return $this->goBack();

        }
        return $this->render('login', ['model' => $model]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionRecovery()
    {
        $model = new RecoveryForm(['scenario' => RecoveryForm::SCENARIO_FORGOT]);
        $this->performAjaxValidation($model);
        if ($model->load(Yii::$app->request->post()) && $model->sendRecoveryMessage()) {
            Yii::$app->session->setFlash(
                'info',
                'An email has been sent with instructions for resetting your password'
            );
            return $this->goToMainPage();
        }
        return $this->render('recovery', ['model'  => $model]);
    }

    public function actionReset()
    {
        $request = Yii::$app->request;
        $id = intval($request->get('id'));
        $user = User::findIdentity($id);
        if ($user === null) {
            throw new NotFoundHttpException();
        }
        $model = new RecoveryForm(['scenario' => RecoveryForm::SCENARIO_RESET]);
        $this->performAjaxValidation($model);
        if (Yii::$app->getRequest()->post()) {
            if ($model->load(Yii::$app->getRequest()->post()) && $model->resetPassword($user)) {
                Yii::$app->session->setFlash(
                    'success',
                    'Your password has been changed successfully.'
                );
                return $this->redirect(['user/login']);
            } else {
                Yii::$app->session->setFlash(
                    'danger',
                    'An error occurred and your password has not been changed. Please try again later.'
                );
                return $this->goToMainPage();
            }
        } else {
            if (!$user->checkResetCode($id, $request->get('code'))) {
                $this->goToMainPage();
            }
        }
        return $this->render('reset', ['model' => $model]);
    }

    public function actionResend()
    {
        $model = new ResendForm();
        $this->performAjaxValidation($model);
        if ($model->load(Yii::$app->getRequest()->post()) && $model->resendConfirmation()) {
            Yii::$app->session->setFlash(
                'info',
                'A message has been sent to your email address.'
            );
            //return $this->redirect(['user/login']);
        }
        return $this->render('resend', ['model' => $model]);
    }

    /**
     * @param string $active
     * @param string $param
     * @return mixed
     * @throws \yii\web\NotFoundHttpException
     * Redirect to main page of user account.
     */
    public function actionAccount($active = null, $param=null)
    {

        $user = User::findIdentity(Yii::$app->user->id);
        $name = $user->getUsername();
        if ($user === null) {
            throw new NotFoundHttpException();
        }
        $user = $user->getAccountByRole();
        if ($user instanceof Business) {
            $business = true;
            return $this->render('account', [
                'business' => $business,
                'username' => $name,
                'active'   => (!empty($active)) ? $active : 'profile',
                'param' =>$param,
            ]);
        } else if ($user instanceof Consumer) {
            $business = false;
            Yii::$app->session->setFlash('tooltip', 'You currently don\'t have any promotions saved. First, use the Wizerd search engine and view business profiles that are running promotions. Then click on the star icon to save any promotion. When you\'re ready to redeem a promotion just show the merchant a copy on your phone. Remember, it\'s completely free to use everything Wizerd offers.');
            return $this->render('account', [
                'business' => $business,
                'username' => $name,
                'active'   => (!empty($active)) ? $active : 'promo-d',
            ]);
        } else {
            throw new NotFoundHttpException();
        }
    }
}
