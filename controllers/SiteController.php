<?php

namespace app\controllers;

use Yii;
use app\components\exceptions\ZipInactiveException;
use app\components\exceptions\ZipNotExistsException;
use app\forms\RegistrationForm;
use app\forms\SearchForm;
use app\models\SaveWaitEmail;
use yii\helpers\Url;
use yii\web\Controller;
use yii\helpers\Html;
use yii\base\Model;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\models\Page;
use app\forms\ContactForm;
use app\models\Business;
use app\models\User;
use app\models\ZipCode;

class SiteController extends Controller
{

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST
                    ? 'testme'
                    : null,
            ],
        ];
    }

    public function beforeAction($event)
    {
        Yii::$app->getUser()
            ->setReturnUrl(Yii::$app->request->url);

        return parent::beforeAction($event);
    }

    /**
     * Performs ajax validation.
     *
     * @param Model $model
     *
     * @throws \yii\base\ExitException
     */
    protected function performAjaxValidation(Model $model)
    {
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            echo json_encode(ActiveForm::validate($model));
            Yii::$app->end();
        }
    }

    public function actionIndex()
    {
        $waitEmail = new SaveWaitEmail();
        $model = new SearchForm();
        if (((Yii::$app->request->isGet) && ($model->load(Yii::$app->request->get())))) {
            try {
                if ($model->validate()) {
                    $result = $model->makeSearch();
                    if ($result instanceof ZipCode) {
                        return $this->render('index', [
                            'waitEmail' => $waitEmail,
                            'model' => $model,
                            'message' => true,
                            'message_city' => $result->city,
                        ]);
                    } elseif (is_array($result)) {
                        $this->view->params['head_form'] = $this->renderAjax('search/_head_form', ['model' => $model]);

                        return $this->render('search/_search', [
                            'tiers' => $result['tiers'],
                            'zip' => $result['zip'],
                            'savedTiers' => $result['savedTiers'],
                            'pages' => $result['pages'],
                            'message' => false,
                        ]);
                    }
                }
            } catch (ZipNotExistsException $ex) {
                return $this->render('index', [
                    'waitEmail' => $waitEmail,
                    'model' => $model,
                    'message' => 'ziperror'
                ]);
            } catch (ZipInactiveException $ex) {
                return $this->render('index', [
                    'waitEmail' => $waitEmail,
                    'model' => $model,
                    'message' => 'zipinactive'
                ]);
            }
        }

        return $this->render('index', [
            'waitEmail' => $waitEmail,
            'model' => $model,
            'message' => false
        ]);
    }

    public function actionFaq()
    {
        return $this->render('faq');
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['supportEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }

        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout()
    {
        $page = Page::findBySlug('about');

        return $this->render('about');
    }

    public function actionInstruction()
    {
        $waitEmail = new SaveWaitEmail();
        $waitEmail->scenario = SaveWaitEmail::SCENARIO_REGISTER;
        $model = new RegistrationForm();
        $this->performAjaxValidation($model);
        if ($model->load(Yii::$app->request->post()) && $model->register()) {
            return $this->render('//user/register', [
                'waitEmail' => $waitEmail,
                'model' => $model
            ]);
        }

        return $this->render('instruction', [
            'waitEmail' => $waitEmail,
            'model' => $model,
            'singlePage' => false
        ]);
    }

    public function actionPrivacy()
    {
        return $this->render('privacy');
    }

    public function actionSales()
    {
        return $this->render('sales');
    }

    public function actionSupport()
    {
        return $this->render('support');
    }

    public function actionTerms()
    {
        return $this->render('terms');
    }

    public function actionBusinessSupport()
    {
        if ((!Yii::$app->user->isGuest) && (Business::findByUserId(Yii::$app->user->id))) {
            return $this->render('business_support');
        } else {
            $this->redirect(Url::to('/site/index'));
        }
    }

    public function actionBusinessWelcome()
    {
        $waitEmail = new SaveWaitEmail();
        $waitEmail->scenario = SaveWaitEmail::SCENARIO_REGISTER;
        $model = new RegistrationForm();
        $this->performAjaxValidation($model);
        if ($model->load(Yii::$app->request->post()) && $model->register()) {
            return $this->render('//user/register', [
                'waitEmail' => $waitEmail,
                'model' => $model
            ]);
        }

        return $this->render('business_welcome', [
            'waitEmail' => $waitEmail,
            'model' => $model,
            'singlePage' => false
        ]);
    }

    /**
     * Search
     *
     * @param type $zip
     * @return boolean
     */
    public function actionGetBizByZipcode($zip)
    {
        if (Yii::$app->request->isAjax) {
            if ($business = Business::searchByZipcode($zip)) {


                echo Html::input('text', 'keywords', null, ['class' => 'form-control']);
                /* return $this->renderAjax('search/_industry', [
                  'industrys' => $industrys
                  ]); */
            }
        }

        return false;
    }

    public function actionGetBizByInd($zip, $ind)
    {
        if (Yii::$app->request->isAjax) {
            $industry = \app\models\Industry::find()
                ->where('id=:id', ['id' => $ind])
                ->one();

            $func = 'getForSearchTemplate' . $industry->base_template;

            return $this->{$func}();
        }

        return false;
    }

    public function actionBusinessPage()
    {
        $user = User::findIdentity(Yii::$app->user->id);
        if ($user !== null) {
            $business = $user->getBusiness();
            if ($business !== null) {
                return $this->render('business', [
                    'model' => $business,
                    'savedIds' => []
                ]);
            }
        }
        $this->redirect(Url::to('/log-in'));
    }
    /*
        public function actionBusinessPage($id)
        {
            $model = Business::find()->where('id=:id', ['id' => $id])->one();
            if ($model == null) {
                return false;
            }

            if (!Yii::$app->user->isGuest) {
                $user = User::findIdentity(Yii::$app->user->id);
                if ($user->getConsumer()) {

                    $savedTiers = $user->getSavedTiers();
                    $savedIds = [];
                    foreach ($savedTiers as $tier) {
                        $savedIds[] = $tier['id'];
                    }
                    return $this->render('business', ['model' => $model, 'savedIds' => $savedIds]);
                }
            }

            return $this->render('business', ['model' => $model, 'savedIds' => []]);
        }
    */
    /*
        public function actionCity($city)
        {
            return $this->render('business/city', ['city' => $city]);
        }

        public function actionCityIndustry($city, $industry)
        {
            return $this->render('business/city_industry', ['city' => $city, 'industry' => $industry]);
        }
    */

    public function actionBusiness($business)
    {

        if ($business == null) {
            $this->redirect(Url::to('site/index'));
        }
        $savedIds = [];
        if (!Yii::$app->user->isGuest) {
            $user = User::findIdentity(Yii::$app->user->id);
            if ($user->getConsumer()) {
                $savedTiers = $user->getSavedTiers();
                foreach ($savedTiers as $tier) {
                    $savedIds[] = $tier['id'];
                }

            }
        }

        return $this->render('business', [
            'model' => $business,
            'savedIds' => $savedIds
        ]);
    }




    /*
     * Action to Biz Page Using Wizerd.com/city/industry/vanity-name
     * @param null $city
     * @param null $industry
     * @param null $business
     * @return bool|string

    public function actionCityIndustryBusiness($city = null, $industry = null, $business = null)
    {

        if ($business == null) {
            return false;
        }
        $savedIds = [];
        if (!Yii::$app->user->isGuest) {
            $user = User::findIdentity(Yii::$app->user->id);
            if ($user->getConsumer()) {
                $savedTiers = $user->getSavedTiers();
                foreach ($savedTiers as $tier) {
                    $savedIds[] = $tier['id'];
                }

            }
        }
        return $this->render('business', ['model' => $business, 'savedIds' =>  $savedIds]);
    }
*/
    /*
     * Action to make 301 redirect to biz Page
     * @param $business
     */
    /*
    public function actionBiz301($business)
    {
        $city = $business->zipCode->city;
        $industry = $business->industry;
        $this->redirect(Url::to(strtolower($city->name) . '/' . strtolower($industry->search) . '/' . $business->vanity_name), 301);
    }
    */

    public function actionSaveWaitEmail()
    {
        $model = new SaveWaitEmail();
        if ((Yii::$app->request->isPost) && ($model->load(Yii::$app->request->post()))) {
            $saved = false;
            if ($model->industry !== null) {
                $model->scenario = SaveWaitEmail::SCENARIO_REGISTER;
            }
            if (($model->validate()) && ($model->save())) {
                $saved = true;

            }
            if ($saved) {
                if ($model->scenario == SaveWaitEmail::SCENARIO_REGISTER) {
                    Yii::$app->session->setFlash('warning',
                        'We will send you an email once Wizerd is active in your city.', true);
                    $this->redirect(Url::to(['/user/register']));
                } else {
                    $this->redirect(Url::to(['site/index']));
                }
            }
        }

        return false;
    }

    public function actionBusinessNoAccess()
    {
        return $this->render('business_no_access');
    }
}
