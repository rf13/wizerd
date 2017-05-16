<?php

namespace app\controllers;

use app\forms\PhotoAddForm;
use app\forms\VanityNameForm;
use app\models\Business;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\base\Model;
use app\models\Consumer;
use app\models\CustomCategory;
use app\models\CustomService;
use app\models\Menu;
use app\models\Photo;
use app\models\Promo;
use app\models\Staff;
use app\models\User;
use app\models\Field;
use app\models\Tier;
use app\models\FieldValue;
use app\models\WishList;
use app\forms\ProfilePublicForm;
use app\forms\ProfileOperationForm;
use app\forms\ProfileReviewForm;
use app\forms\ProfileSettingsForm;
use yii\widgets\ActiveForm;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;

class AccountController extends Controller
{

    private $_business = false;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'photo-delete' => ['post'],
                    'photo-main' => ['post'],
                    'staff-delete' => ['post'],
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

    public function beforeAction($event)
    {
        Yii::$app->getUser()->setReturnUrl(Yii::$app->request->url);
        if ($event->actionMethod == 'actionPromoGroupDelete') {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($event);
    }

    /**
     * Redirect to main page of user account.
     */
    protected function getBusiness()
    {
        if ($this->_business === false) {
            $user = User::findIdentity(Yii::$app->user->id);
            $user = $user->getBusiness();
            $this->_business = $user;
        }
        return $this->_business;
    }

    /**
     * Redirect to main page of user account.
     * @param string $active
     * @return mixed
     */
    protected function goToUserAccount($active = null)
    {
        return $this->redirect(['user/account', 'active' => $active]);
    }

    /**
     * Redirect to partial page of user account.
     * @param array $param
     * @param string $url
     * @return mixed
     */
    protected function goToBusinessPartial($url, $param = [])
    {
        if (!Yii::$app->request->isAjax)
            return $this->goToUserAccount();
        return $this->renderAjax($url, $param);
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
     * Finds the Staff model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Staff the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findStaff($id)
    {
        $id = intval($id);
        if (($model = Staff::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Staff does not exist.');
        }
    }

    /**
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findPromo($id)
    {
        $id = intval($id);
        if (($model = Promo::find()->with('groups.category')->where(['id' => $id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Promo does not exist.');
        }
    }

    /**
     * @param integer $id
     * @return Mixed Menu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findMenu($id)
    {
        $id = intval($id);
        $model = Menu::find()->with('categories.services')
            ->where('id=:id')->addParams([':id' => $id])->one();
        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Menu category does not exist.');
        }
    }

    /**
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findCustomCategory($id)
    {
        $id = intval($id);
        $model = CustomCategory::find()->with('menu')->with('services')->where(['id' => $id])->one();
        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Custom category does not exist.');
        }
    }

    /**
     * @param integer $id
     * @return CustomService the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findCustomService($id)
    {
        $id = intval($id);
        if (($model = CustomService::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Custom service not exist.');
        }
    }

    public function actionMenu()
    {
        $user = $this->getBusiness();
        $model = new Menu(['scenario' => Menu::SCENARIO_UPDATE]);
        if (Yii::$app->request->isPost) {

            if ($model->load(Yii::$app->request->post()) && $model->changeDesc()) {
                Yii::$app->session->setFlash('success', 'The menu was updated successfully');
                $this->goToUserAccount('menu');
            }
        }
        $menus = Menu::findByBusId($user->id);
        return $this->renderPartial('menu', [
            'business' => $user,
            'menus' => $menus,
            'model' => $model
        ]);
    }

    public function actionMenuAdd()
    {
        $user = $this->getBusiness();
        $count = Menu::find()->select(['count(*)'])->where('bus_id=bus_id', ['bus_id' => $user->id])->andWhere('nonamed>0')->scalar();
        if ($count > 0)
            $model = new Menu(['scenario' => Menu::SCENARIO_WITH_TITLE]);
        else $model = new Menu();
        $model->bus_id = $user->id;
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->addToBus()) {
                Yii::$app->session->setFlash('success', 'The menu was added successfully');
                Yii::$app->session->set('current-menu', $model->id);
                $this->goToUserAccount('menu');
            }
        } else if (Yii::$app->request->isAjax) {

            return $this->renderAjax('menu/_add', [
                'model' => $model,
            ]);
        }

        return false;
    }

    public function actionMenuCategoryAddModal()
    {


        $user = $this->getBusiness();
        $model = new CustomCategory(['scenario' => CustomCategory::SCENARIO_DYNAMIC]);
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->attachCustom()) {
                Yii::$app->session->setFlash('success', 'The category was added successfully');
            } else {
                Yii::$app->session->setFlash('error', 'An error occurred while adding category');
            }
            $this->goToUserAccount('menu');
        } else if (Yii::$app->request->isAjax) {
            return $this->renderAjax('menu/_custom_category', [
                'model' => $model,
                'menu' => $user->getAddedMenus()
            ]);
        }
        return false;
    }

    public function actionMenuCategoryAdd()
    {
        if (Yii::$app->request->isPost) {
            $menu_id = Yii::$app->request->post('menu_id');
            if (!empty($menu_id)) {
                $menu = Menu::find()->where('id=:id', ['id' => $menu_id])->one();
                if ($menu !== null) {

                    $category = new CustomCategory();
                    $category->menu_id = $menu->id;
                    if ((!$category->validate()) || (!$category->save())) {
                        print_r($category->errors);
                        return false;
                    }

                    /*  $service = new CustomService();

                      $service->cat_id = $category->id;
                      if ((!$service->validate()) || (!$service->save()) || (!$service->createTier()))
                          return false;
                  */
                    return $this->renderAjax('menu/_detail_categories', [
                        'menu' => $menu,
                        'id' => $menu->id,
                        'categories' => $menu->categories,
                        'title' => $menu->title,
                    ]);
                }
            }
        }
        return false;
    }

    /**
     * @param int $cat
     * @param string $title
     * @param string $menu
     * @return mixed

    public function actionMenuServiceAdd($cat = null, $title = null, $menu = null) {
     * $user = $this->getBusiness();
     * $model = new CustomService();
     * if (Yii::$app->request->isPost) {
     * if ($model->load(Yii::$app->request->post()) && $model->attachCustom()) {
     * Yii::$app->session->setFlash('success', 'The service was added successfully');
     * } else {
     * Yii::$app->session->setFlash('error', 'An error occurred while adding service');
     * }
     * $this->goToUserAccount('menu');
     * } else if (Yii::$app->request->isAjax) {
     * if (!empty($cat)) {
     * $model->scenario = CustomService::SCENARIO_WITHOUT_CAT;
     * $model->cat_id = $cat;
     * return $this->renderAjax('menu/_custom_service', [
     * 'model' => $model,
     * 'category' => $title,
     * 'menu' => $menu
     * ]);
     * } else {
     * $model->scenario = CustomService::SCENARIO_WITH_INDUSTRY;
     * return $this->renderAjax('menu/_custom_service', [
     * 'model' => $model,
     * 'menu' => $user->getAddedMenus(),
     * 'category' => false,
     * 'category_by_menu' => Menu::getCategoriesById(Yii::$app->session->get('current-menu'))
     * ]);
     * }
     * }
     * return false;
     * }
     */
    public function actionMenuServiceAddModal($cat = null, $title = null, $menu = null)
    {
        $user = $this->getBusiness();
        $model = new CustomService();
        if (Yii::$app->request->isPost) {
            //$model->scenario = CustomService::SCENARIO_WITH_INDUSTRY;
            if ($model->load(Yii::$app->request->post()) && $model->attachCustom()) {
                Yii::$app->session->setFlash('success', 'The service was added successfully');
            } else {
                Yii::$app->session->setFlash('error', 'An error occurred while adding service');
            }
            $this->goToUserAccount('menu');
        } else if (Yii::$app->request->isAjax) {
            if (!empty($cat)) {
                $model->scenario = CustomService::SCENARIO_WITHOUT_CAT;
                $model->cat_id = $cat;
                return $this->renderAjax('menu/_custom_service', [
                    'model' => $model,
                    'category' => $title,
                    'menu' => $menu
                ]);
            } else {
                $model->scenario = CustomService::SCENARIO_WITH_TITLE;
                $menus = $user->getAddedMenus();
                //if (count($menus)>1) 
                $current_menu = Yii::$app->session->get('current-menu');
                // else $current_menu= $menus->id;
                //else print_r($menus);
                return $this->renderAjax('menu/_custom_service', [
                    'model' => $model,
                    'menu' => $menus,
                    'category' => false,
                    //'category_by_menu' => Menu::getCategoriesById(Yii::$app->session->get('current-menu'))
                    // 'category_by_menu' => Menu::getCategoriesById($current_menu, false),
                    'category_by_menu' => Menu::getCategoriesById($current_menu, true),
                    'qw' => $current_menu,
                ]);
            }
        }
        return false;
    }

    public function actionMenuServiceAdd()
    {

        if (Yii::$app->request->isPost) {
            $cat_id = Yii::$app->request->post('cat_id');
            if (!empty($cat_id)) {
                $category = CustomCategory::find()->where('id=:id', ['id' => $cat_id])->one();
                if ($category !== null) {

                    $service = new CustomService();
                    $service->cat_id = $category->id;
                    if ((!$service->validate()) || (!$service->save()) || (!$service->createTier()))
                        return false;

                    return $this->renderAjax('menu/_detail_services', [
                        'category' => $category,
                        'services' => $category->services,
                        'menu_id' => $category->menu->id,
                    ]);
                }
            }
        }
        return true;
    }

    /**
     * @param integer $id
     * @param boolean $up
     * @return boolean
     */
    public function actionMenuSort($id, $up)
    {
        if (Yii::$app->request->isPost) {
            $menu = $this->findMenu($id);
            $result = $menu->changeSort($up);
            if ($result) {
                $menu = $this->findMenu($id);
                $this->goToUserAccount('menu');
            }
        }
        return false;
    }

    /**
     * @param integer $id
     * @param boolean $up
     * @return boolean
     */
    public function actionMenuCatSort($id, $up)
    {
        if (Yii::$app->request->isPost) {
            $category = $this->findCustomCategory($id);
            $result = $category->changeSort($up);
            if ($result) {

                $menu = $category->menu;
                return $this->goToBusinessPartial('menu/_detail_categories', [
                    'menu' => $menu,
                    'id' => $menu->id,
                    'categories' => $menu->categories,
                    'title' => $menu->title,
                ]);
            }
        }
        return false;
    }

    /**
     * @param integer $id
     * @param boolean $up
     * @return boolean
     */
    public function actionMenuSrvSort($id, $up, $menu_id, $attr_type = null, $change_template = null)
    {
        if (Yii::$app->request->isPost) {
            $service = $this->findCustomService($id);
            $result = $service->changeSort($up);
            if ($result) {
                $cat = $this->findCustomCategory($service->cat_id);
                return $this->goToBusinessPartial('menu/_detail_services', [
                    'category' => $cat,
                    'services' => $cat->services,
                    'menu_id' => $menu_id,
                ]);
            }
        }
        return false;
    }

    public function actionGetCategory()
    {
        if (Yii::$app->request->isPost) {
            $menu_id = Yii::$app->request->post('menu');

            if (!empty($menu_id)) {


                $categories = Menu::getCategoriesById($menu_id);
                if (count($categories) > 1) {
                    foreach ($categories as $cat) {
                        if($cat['title']!=='')
                        echo '<option value="' . $cat['id'] . '">' . $cat['title'] . '</option>';
                        else echo '<option value="' . $cat['id'] . '">' . CustomCategory::NONAME_CATEGORY . '</option>';
                    }
                } else {
                    return false;
                }
            } else {
                echo '<option value="" disabled>-Choose a category-</option>';
            }
        }
    }

    public function actionPromoSingle()
    {
        return $this->renderPartial('promo_single', [
            'model' => true
        ]);
    }

    public function actionPromoDouble()
    {
        $user = $this->getBusiness();
        $model = new Promo();
        $model->bus_id = $user->id;
        $model->start = date('n/j/Y');
        $this->performAjaxValidation($model);
        if (Yii::$app->request->isPost) {

            if ($model->load(Yii::$app->request->post()) && $model->addPromoGroup()) {
                Yii::$app->session->setFlash('success', 'The promo group was added successfully');
            } else {
                Yii::$app->session->setFlash('error', 'An error occurred while adding promo group');
            }
            return $this->goToUserAccount('promo-d');
        }
        $promo = Promo::getBusinessPromos($user->id);

        $new_promo = (count($promo) == 0);
        if ($new_promo) {
            return $this->goToBusinessPartial('promo_double', [
                'new_promo' => $new_promo,
                'model' => $model,
                'menu_service' => $user->getMenuForPromo()
            ]);
        } else {
            return $this->goToBusinessPartial('promo_double', [
                'new_promo' => $new_promo,
                'model' => $model,
                'promos' => $promo,
                'menu_service' => $user->getMenuForPromo()
            ]);
        }
    }

    /**
     *  returning a base Add form,
     * if set ID -> terurning a clear form except TERMS
     * @param type $id
     * @return boolean
     */

    public function actionPromoGroupAdd($id = null)
    {
        $user = $this->getBusiness();
        $model = new Promo();
        if (isset($id)) {
            $template_model = $this->findPromo($id);
            $model->terms = $template_model->terms;
        }
        $model->bus_id = $user->id;
        $this->performAjaxValidation($model);
        if (Yii::$app->request->isAjax) {
            $model->start = date('n/j/Y');

            return $this->renderAjax('promo/_add', [
                'model' => $model,
                'menu_service' => $user->getMenuForPromo(),
                //'popup' => true
                'popup' => false
            ]);
        } else if (Yii::$app->request->isPost) {
            $model->start = date('Y-m-d', strtotime($model->start));
            if ($model->load(Yii::$app->request->post()) && $model->addPromoGroup()) {
                Yii::$app->session->setFlash('success', 'The promo group was added successfully');
            } else {
                Yii::$app->session->setFlash('error', 'An error occurred while adding promo group');
            }
            return $this->goToUserAccount('promo-d');
        }
        return false;
    }

    /**
     * Updating Promos by id
     * if set $clear - means that need to get clear form
     * @param integer $id
     * @return mixed|boolean
     */

    public function actionPromoGroupUpdate($id, $clear = null)
    {
        if (!isset($clear)) {
            $model = $this->findPromo($id);
            $services = [];
            foreach ($model->groups as $promo_group) {
                $services[] = $promo_group->category->id;
            }
            $model->start = date('n/j/Y', strtotime($model->start));
            $model->end = date('n/j/Y', strtotime($model->end));
        } else {
            $old_model = $this->findPromo($id);
            $model = new Promo();
            $model->id = $old_model->id;
            $model->start = date('n/j/Y');
            $model->end = null;
            $services = [];
        }
        $this->performAjaxValidation($model);
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->updatePromoGroup($services)) {
                Yii::$app->session->setFlash('success', 'The promotion was updated successfully');
                return $this->goToUserAccount('promo-d');
            }
        } else if (Yii::$app->request->isGet) {
            $user = $this->getBusiness();
            $model->services = $services;


            return $this->goToBusinessPartial('promo/_update', [
                'model' => $model,
                'menu_service' => $user->getMenuForPromo()
            ]);
        }
        return false;
    }

    /**
     * @param integer $id
     * @return mixed|boolean
     */

    public function actionPromoGroupPublish($id)
    {
        if (Yii::$app->request->isPost) {
            $promo = $this->findPromo($id);
            if ($promo->publishPromoGroup()) {
                Yii::$app->session->setFlash('success', 'The promotion was published successfully');
                return $this->goToUserAccount('promo-d');
            }
        }
        return false;
    }

    /**
     * @param integer $id
     * @return mixed|boolean
     */

    public function actionPromoGroupEnd($id)
    {
        if (Yii::$app->request->isPost) {
            $promo = $this->findPromo($id);
            if ($promo->endNowGroup()) {
                Yii::$app->session->setFlash('success', 'The promotion was ended successfully');
                return $this->goToUserAccount('promo-d');
            }
        }
        return false;
    }

    /**
     * @param integer $id
     * @return mixed
     */

    public function actionPromoGroupGet($id)
    {
        $user = $this->getBusiness();
        $model = $this->findPromo($id);
        return $this->goToBusinessPartial('promo/_group', [
            'promo' => $model,
            'menu_service' => $user->getMenuForPromo(),
        ]);
    }

    /**
     * @param integer $id
     * @return mixed|boolean
     */

//    public function actionPromoGroupDelete($id) {
//        if (Yii::$app->request->isPost) {
//            $promo = $this->findPromo($id);
//            if ($promo->delete()) {
//                Yii::$app->session->setFlash('success', 'The promotion was deleted successfully');
//                return $this->goToUserAccount('promo-d');
//            }
//        }
//        return false;
//    }

    public function actionPromoGroupDelete($id)
    {
        if (Yii::$app->request->isPost) {
            $promo = $this->findPromo($id);
            $services = [];
            foreach ($promo->groups as $promo_group) {
                $services[] = $promo_group->category->id;
            }
            if (count($promo->wishLists) != 0) {
                if ($promo->markDeleted()) {
                    Yii::$app->session->setFlash('success', 'The promotion was deleted successfully');
                    return $this->goToUserAccount('promo-d');
                }
            } else {
                if ($promo->delete()) {
                    Yii::$app->session->setFlash('success', 'The promotion was deleted successfully');
                    return $this->goToUserAccount('promo-d');
                }
            }
        }
        return false;
    }

    public function actionPromoSaved()
    {
        $resultData = [];
        if (!Yii::$app->user->isGuest) {
            $user = User::findIdentity(Yii::$app->user->id);
            if ($user->getConsumer()) {
                $savedTiersData = $user->getSavedTiersData();
                foreach ($savedTiersData as $data) {
                    $resultData[$data['business_id']]['tiers'][] = $data;
                    $resultData[$data['business_id']]['business']['id'] = $data['business_id'];
                    $resultData[$data['business_id']]['business']['name'] = $data['name'];
                    $resultData[$data['business_id']]['business']['address'] = $data['address'];
                    $resultData[$data['business_id']]['business']['phone'] = $data['phone'];
                    $resultData[$data['business_id']]['business']['vanity_name'] = $data['vanity_name'];
                    $resultData[$data['business_id']]['business']['is_home'] = $data['is_home'];
                    $resultData[$data['business_id']]['business']['zip'] = $data['zip'];
                    $resultData[$data['business_id']]['business']['city_name'] = $data['city_name'];
                    $resultData[$data['business_id']]['business']['state_code'] = $data['state_code'];
                    $resultData[$data['business_id']]['business']['website'] = $data['website'];
                    $resultData[$data['business_id']]['business']['contact_email'] = $data['contact_email'];
                }
            }
        }
        return $this->renderPartial('promo_saved', [
            'promo' => true,
            'savedTiersData' => $resultData
        ]);
    }

    public function actionPhotoGoto()
    {
        Yii::$app->session->set('photo', 'photo');
        $this->redirect(['/user/account', 'active' => 'photo']);
    }
    public function actionPhotoProfileGoto()
    {
        Yii::$app->session->set('photo', 'profile');
        $this->redirect(['/user/account', 'active' => 'photo']);
    }

    public function actionPhotoProfile()
    {
        $model = new Photo();
        $new_model = new PhotoAddForm();
        $model->scenario = Photo::SCENARIO_ADD;
        $business = $this->getBusiness();
        $photo = Photo::find()->where('main>0 and main<10 and bus_id=:bus_id', ['bus_id' => $business->id])->one();

        return $this->renderAjax('photo_profile', [
            'photo' => $photo,
            'model' => $model,
            'new_model' => $new_model]);
    }


    public function actionPhotoMakeAsProfile()
    {
        $business = $this->getBusiness();
        if (Yii::$app->request->isAjax) {

            $id = Yii::$app->request->post('id');
            if ($id !== null) {

                $photo = Photo::find()->where('id=:id and bus_id=:bus_id', ['id' => $id, 'bus_id' => $business->id])->one();
                if ($photo !== null) {
                    $photo->main = 1;
                    $photo->croped = 0;
                    Photo::removeMain($business->id, $id);
                    $photo->save();


                }

            }


            $model = new Photo();
            $new_model = new PhotoAddForm();
            $model->scenario = Photo::SCENARIO_ADD;

            $photo = Photo::find()->where('main>0 and main<10 and bus_id=:bus_id', ['bus_id' => $business->id])->one();

            return $this->renderAjax('photo_profile', [
                'photo' => $photo,
                'model' => $model,
                'new_model' => $new_model]);


        }

    }

    public function actionPhotoProfileAddNew()
    {
        if (Yii::$app->request->isAjax) {
            $business = $this->getBusiness();
            $model = new PhotoAddForm();
            $model->scenario = PhotoAddForm::SCENARIO_ONE;
            $model->bus_id = $business->id;
            if (Yii::$app->request->isPost) {
                $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
                if ($model->uploadProfilePhoto()) {

                    $photo = Photo::find()->where('main=10 and bus_id=:bus_id', ['bus_id' => $business->id])->one();
                    $clear = new Photo();
                    $new_model = new PhotoAddForm();
                    return $this->renderAjax('photo_profile', [
                        'photo' => $photo,
                        'model' => $clear,
                        'new_model' => $new_model]);


                } else {
                    print_r($model->errors);
                }
            }
        } else {
            return $this->goToUserAccount('photo');
        }
        return false;


    }

    public function actionPhotoProfileCancel()
    {
        if (Yii::$app->request->isAjax) {
            $business = $this->getBusiness();
            $id = Yii::$app->request->post('id');
            if ($id !== null) {
                $photo = Photo::find()->where('id=:id and bus_id=:bus_id', ['id' => $id, 'bus_id' => $business->id])->one();
                if ($photo !== null) {
                    if ($photo->main == 10) {
                        $photo->delete();
                    }

                    $model = new Photo();
                    $new_model = new PhotoAddForm();
                    $model->scenario = Photo::SCENARIO_ADD;
                    $business = $this->getBusiness();
                    $photo = Photo::find()->where('main>0 and main<10 and bus_id=:bus_id', ['bus_id' => $business->id])->one();

                    return $this->renderAjax('photo_profile', [
                        'photo' => $photo,
                        'model' => $model,
                        'new_model' => $new_model]);
                }
            }
        }

    }

    public function actionPhotoGetProfileOrigin()
    {
        if (Yii::$app->request->isAjax) {
            $business = $this->getBusiness();
            $id = Yii::$app->request->post('id');
            if ($id !== null) {
                $photo = Photo::find()->where('id=:id and bus_id=:bus_id', ['id' => $id, 'bus_id' => $business->id])->one();
                if ($photo !== null) {
                    return Html::img($photo->getWebPathProfileSmallOriginImage(),
                        [
                            'id' => 'original_image',
                            'class' => 'img-responsive',
                            'alt' => $photo->title
                        ]);
                }
            }

        }

    }

    public function actionPhotoProfileSaveNew()
    {

        if (Yii::$app->request->isAjax) {
            $business = $this->getBusiness();
            $id = Yii::$app->request->post('id');
            $crop = Yii::$app->request->post('crop');
            if ($crop !== null) {
                $photo = Photo::find()->where('id=:id and bus_id=:bus_id', ['id' => $id, 'bus_id' => $business->id])->one();
                //  Photo::removeMain($business->id);

                $ar = explode(',', $crop);
                $crop_arr['x'] = $ar[0];
                $crop_arr['y'] = $ar[1];
                $crop_arr['width'] = $ar[2];
                $crop_arr['height'] = $ar[3];
                if (!isset($ar[4])) $crop_arr['rotate'] = 0;
                else  $crop_arr['rotate'] = $ar[4];
                $photo->crop_params = $crop_arr;

                if (($photo->main == 10) || ($photo->main == 2)) {
                    $photo->main = 2;
                } else {
                    $photo->main = 1;
                }
                $photo->croped = 1;
                Photo::removeMain($business->id, $id);
                $photo->generateProfileThumb();


                if (!$photo->save())
                    print_r($photo->errors);


                $photo = Photo::find()->where('main>0 and main<=2 and bus_id=:bus_id', ['bus_id' => $business->id])->one();
                $clear = new Photo();
                $new_model = new PhotoAddForm();
                return $this->renderAjax('photo_profile', [
                    'photo' => $photo,
                    'model' => $clear,
                    'new_model' => $new_model]);


            }


        } else {
            return $this->goToUserAccount('photo');
        }
        return false;
    }


    public function actionPhoto()
    {
        $model = new Photo();
        $new_model = new PhotoAddForm();
        $model->scenario = Photo::SCENARIO_ADD;
        $user = $this->getBusiness();
        $photos = Photo::findByBusId($user->id);
        $unsaved = Photo::hasUnsaved($user->id);
        return $this->goToBusinessPartial('photo', ['photos' => $photos, 'model' => $model, 'new_model' => $new_model, 'unsaved' => $unsaved]);
    }

    public function actionGetOnePhotoEdit()
    {

        if (Yii::$app->request->isAjax) {
            $business = $this->getBusiness();
            $id = Yii::$app->request->post('id');
            $photo = Photo::find()->where('id=:id and bus_id=:bus_id', ['id' => $id, 'bus_id' => $business->id])->one();
            return $this->renderAjax('photo/_one_photo_edit', [
                'photo' => $photo
            ]);

        }
        return true;

    }


    public function actionPhotoEdit()
    {
        $model = new Photo();
        $biz = $this->getBusiness();
        if (Yii::$app->request->isPost) {
            $id = Yii::$app->request->post('id');
            $title = Yii::$app->request->post('title');
            $description = Yii::$app->request->post('description');
            $main = Yii::$app->request->post('main');
            $photo = Photo::findPhotoOfBiz($id, $biz->id);
            $photo->scenario = Photo::SCENARIO_EDIT;
            if ($photo !== null) {
                $photo->title = $title;
                $photo->description = $description;

                if ($photo->validate()) {
                    $photo->save(false);
                    if ($main == 1)
                        $photo->makeMain();

                }
            }
        }
        $photos = Photo::findByBusId($biz->id);
        return $this->renderAjax('photo/_manage', ['photos' => $photos, 'model' => $model]);
    }

    public function actionPhotoManage()
    {
        $model = new Photo();
        $new_model = new PhotoAddForm();
        $user = $this->getBusiness();
        $model->bus_id = $user->id;
        $this->performAjaxValidation($model);
        $model->scenario = Photo::SCENARIO_ADD;
        $unsaved = Photo::hasUnsaved($user->id);
        $photos = Photo::findByBusId($user->id);
        return $this->goToBusinessPartial('photo/_manage', ['photos' => $photos, 'model' => $model, 'new_model' => $new_model, 'unsaved' => $unsaved]);
    }

    /*
        public function actionPhotoCreateNew()
        {



            $model = new Photo();
            $user = $this->getBusiness();
            $model->bus_id = $user->id;
            $this->performAjaxValidation($model);
            $model->scenario = Photo::SCENARIO_ADD;
            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('photo/_new', ['model' => $model]);
            }
        }
    */

    public function actionPhotoGetForCrop($id)
    {
        $user = $this->getBusiness();
        $model = Photo::find()->where('bus_id=:bus_id and id=:id', ['bus_id' => $user->id, 'id' => (int)$id])->one();

        if ($model !== null) {
            if ($model->saved == 0)
                return $this->renderAjax('photo/_one_photo', ['model' => $model]);
            if ($model->saved == 1)
                return $this->renderAjax('photo/_one_photo_edit_crop', ['model' => $model]);

        }
        return false;

    }

    public function actionPhotoSaveGroup()
    {

        $this->redirect(['/user/account', 'active' => 'photo']);
        $user = $this->getBusiness();
        if (Yii::$app->request->isAjax) {
            $array = Yii::$app->request->post('photos');
            if ($array) {
                $result = [];
                for ($i = 0; $i < count($array); $i++) {
                    $id = (int)substr($array[$i]['name'], strpos($array[$i]['name'], '-') + 1);

                    if (!isset($result[$id]))
                        $result[$id] = Photo::find()->where('id=:id and bus_id=:bus_id', ['id' => $id, 'bus_id' => $user->id])->one();


                    if ($result[$id] !== null) {

                        if (stristr($array[$i]['name'], 'photo_title-')) {
                            $result[$id]->title = $array[$i]['value'];
                        }
                        if (stristr($array[$i]['name'], 'photo_description-')) {
                            $result[$id]->description = $array[$i]['value'];
                        }
                        if (stristr($array[$i]['name'], 'crop_params-')) {
                            if (!empty($array[$i]['value'])) {
                                $ar = explode(',', $array[$i]['value']);
                                $crop_arr['x'] = $ar[0];
                                $crop_arr['y'] = $ar[1];
                                $crop_arr['width'] = $ar[2];
                                $crop_arr['height'] = $ar[3];
                                if (!isset($ar[4])) $crop_arr['rotate'] = 0;
                                else  $crop_arr['rotate'] = $ar[4];
                                $result[$id]->crop_params = $crop_arr;
                            }
                        }
                    }
                }
                foreach ($result as $key => $value) {
                    if ($value !== null) {
                        $value->saved = 1;
                        $value->generateNewThumb();
                        $value->save();
                    }
                }
            }
        }

        /*

                $model = new Photo();
                $new_model = new PhotoAddForm();
                $user = $this->getBusiness();
                $model->bus_id = $user->id;
                $this->performAjaxValidation($model);
                $model->scenario = Photo::SCENARIO_ADD;
                $unsaved = Photo::hasUnsaved($user->id);
                $photos = Photo::findByBusId($user->id);
                return $this->renderAjax('photo/_manage', ['photos' => $photos, 'model' => $model, 'new_model' => $new_model, 'unsaved' => $unsaved]);

        */

        $this->redirect(['/user/account', 'active' => 'photo']);
        return true;


    }


    public function actionPhotoAddNew()
    {


        $model = new PhotoAddForm();
        $user = $this->getBusiness();
        $model->bus_id = $user->id;
        $this->performAjaxValidation($model);
        if (Yii::$app->request->isPost) {
            $success = false;

            if ($model->load(Yii::$app->request->post())) {
                $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');
                if (!$model->upload()) {
                    return false;
                }
            }
            Yii::$app->session->setFlash($success, $success ? 'The photo was added successfully' : 'An error occurred while adding photo'
            );
            return $this->goToUserAccount('photo');
        }

        return false;
    }


    /*
        public function actionPhotoAdd()
        {
            $model = new Photo();
            $user = $this->getBusiness();
            $model->bus_id = $user->id;
            $this->performAjaxValidation($model);
            if (Yii::$app->request->isPost) {
                $success = false;
                if ($model->load(Yii::$app->request->post())) {
                    $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
                    if ($model->addPhoto())
                        $success = true;

                }
                Yii::$app->session->setFlash($success, $success ? 'The photo was added successfully' : 'An error occurred while adding photo'
                );
                return $this->goToUserAccount('photo');
            } else if (Yii::$app->request->isAjax) {
                if (Photo::findByBusId($model->bus_id))
                    return $this->goToBusinessPartial('photo/_add', ['model' => $model]);
                else
                    return $this->goToBusinessPartial('photo/_add', ['model' => $model, 'main' => true]);
            }
            return false;
        }
    */
    /**
     * @param integer $id
     * @return boolean
     */
    public function actionPhotoMain($id)
    {
        return Photo::findPhoto($id)->makeMain();
    }

    /**
     * @param integer $id
     * @return boolean
     */
    /*
     public function actionPhotoDelete($id)
     {
         return Photo::findPhoto($id)->delete();
     }
 */

    public function actionPhotoDelete()
    {
        $business = $this->getBusiness();
        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post('id');
            if ($id !== null) {
                $photo = Photo::find()
                    ->where('id=:id and bus_id=:bus_id', ['id' => $id, 'bus_id' => $business->id])
                    ->one();
                if ($photo !== null) {
                    return $photo->delete();
                }
            }
        }
        return false;
    }


    /**
     * @param boolean $min
     * @return mixed
     */

    public function actionPhotoSort($id, $up)
    {
        $biz = $this->getBusiness();
        $model = new Photo();
        $new_model = new PhotoAddForm();
        if (Yii::$app->request->isPost) {
            $photo = Photo::findPhotoOfBiz($id, $biz->id);

            if ($photo !== null) {
                $photo->scenario = Photo::SCENARIO_EDIT;
                $photo->changeSort($up);
            }

        }
        $photos = Photo::findByBusId($biz->id);
        $unsaved = Photo::hasUnsaved($biz->id);
        return $this->renderAjax('photo/_manage', ['photos' => $photos, 'model' => $model, 'new_model' => $new_model, 'unsaved' => $unsaved]);

    }

    /*
    public function actionMakePhotoForm(){
        $user = $this->getBusiness();
        if($user)
        {
            $model=new PhotoAddForm();
            if (Yii::$app->request->isAjax)
            {
                $count=Yii::$app->request->post('count');



                if(($count)&&($count>0)){

                    return $this->renderAjax('photo/_one_photo', ['model'=>$model,'count'=>$count]);

                }
            }


        }
        return false;

    }
    */
    public function actionPhotoDeleteUnsaved()
    {
        $user = $this->getBusiness();
        if (Yii::$app->request->isGet) {
            $unsaved = Photo::hasUnsaved($user->id);
            foreach ($unsaved as $photo) {
                $photo->delete();

            }
            return true;
        } else {
            return $this->goToUserAccount('photo');
        }
    }


    public function actionStaff($min = false)
    {


        $user = $this->getBusiness();
        $staff = Staff::findByBusId($user->id);
        if ($min) {
            return $this->goToBusinessPartial('staff/_manage', ['staff' => $staff]);
        }
        return $this->goToBusinessPartial('staff', ['staff' => $staff]);
    }


    public function actionGetOneStaffEdit($id, $file_input_id = false)
    {

        $business = $this->getBusiness();
        //$id = Yii::$app->request->post($id);
        $model = Staff::find()->where('id=:id and bus_id=:bus_id', ['id' => $id, 'bus_id' => $business->id])->one();
        $model->scenario = Staff::SCENARIO_ADD;
        $this->performAjaxValidation($model);

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('staff/_one_staff_edit', [
                'model' => $model,
                'file_input_id' => $file_input_id
            ]);

        }
        if (Yii::$app->request->isPost) {

        }


        return true;

    }

    public function actionStaffPhotoAddNew()
    {
        if (Yii::$app->request->isAjax) {
            $business = $this->getBusiness();
            $model = new Staff();
            $model->scenario = Staff::SCENARIO_PHOTO;
            $model->bus_id = $business->id;
            if (Yii::$app->request->isPost) {
                $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
                if ($model->uploadTempPhoto()) {

                    $photo = Photo::find()->where('main=10 and bus_id=:bus_id', ['bus_id' => $business->id])->one();
                    $clear = new Photo();
                    $new_model = new PhotoAddForm();
                    return $this->renderAjax('photo_profile', [
                        'photo' => $photo,
                        'model' => $clear,
                        'new_model' => $new_model]);


                } else {
                    print_r($model->errors);
                }
            }
        } else {
            return $this->goToUserAccount('photo');
        }
        return false;


    }


    public function actionStaffGetForCrop($id)
    {
        $user = $this->getBusiness();
        $model = Staff::find()->where('bus_id=:bus_id and id=:id', ['bus_id' => $user->id, 'id' => (int)$id])->one();

        if ($model !== null) {
            if ($model->saved == 0)
                return $this->renderAjax('staff/_one_staff', ['model' => $model]);
            if ($model->saved == 1)
                return $this->renderAjax('staff/_one_staff_edit_crop', ['model' => $model]);

        }
        return false;

    }


    public function actionStaffPhotoAdd($id)
    {
        $model = $this->findStaff($id);
        $model->scenario = Staff::SCENARIO_EDIT;
        if (Yii::$app->request->isPost) {

            if ($model->load(Yii::$app->request->post())) {

                $file = UploadedFile::getInstance($model, 'imageFile');
                if ($model->updateEmployee($file)) {
                    Yii::$app->session->setFlash('success', 'The employee was added successfully');
                }
            }
        }
        return $this->goToUserAccount('staff');
    }


    public function actionStaffAdd()
    {
        $model = new Staff(['scenario' => Staff::SCENARIO_ADD]);
        $user = $this->getBusiness();
        $model->bus_id = $user->id;
        $this->performAjaxValidation($model);
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post())) {
                $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
                if ($model->addEmployee()) {
                    Yii::$app->session->setFlash('success', 'The employee was added successfully');
                } else {
                    Yii::$app->session->setFlash('error', 'An error occurred while adding staff');
                }
            } else {
                Yii::$app->session->setFlash('error', 'An error occurred while creating employee');
            }
            return $this->goToUserAccount('staff');
        }
        return $this->goToBusinessPartial('staff/_add', ['model' => $model]);
    }

    /*
        public function actionStaffNewAdd()
        {
            $model = new Staff(['scenario' => Staff::SCENARIO_NEW]);
            $user = $this->getBusiness();
            $model->bus_id = $user->id;
            $this->performAjaxValidation($model);
            if (Yii::$app->request->isPost) {
                if ($model->load(Yii::$app->request->post())) {


                    $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
                    if ($model->imageFile) {


                        if ($model->addEmployee()) {
                            Yii::$app->session->setFlash('success', 'The employee was added successfully');
                        } else {
                            Yii::$app->session->setFlash('error', 'An error occurred while adding staff');
                        }
                    } else {
                        if ($model->validate())
                            $model->save(false);
                    }
                    return $this->goToUserAccount('staff');
                } else {
                    Yii::$app->session->setFlash('error', 'An error occurred while creating employee');
                }
                // return $this->goToUserAccount('staff');
            }
            return $this->goToBusinessPartial('staff/_new', ['model' => $model]);
        }
    */

    public function actionStaffNewAdd()
    {

        $model = new Staff(['scenario' => Staff::SCENARIO_NEW]);
        $user = $this->getBusiness();
        $model->bus_id = $user->id;
        $this->performAjaxValidation($model);

        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post('id');
            if ($id !== null) {
                $model = Staff::find()->where('id=:id and bus_id=:bus_id', ['id' => $id, 'bus_id' => $user->id])->one();
                $model->scenario = Staff::SCENARIO_NEW;

                return $this->renderAjax('staff/_one_staff_edit', [
                    'model' => $model
                ]);

            }
            return $this->renderAjax('staff/_one_staff_add', ['model' => $model]);
        }
        if (Yii::$app->request->isPost) {

            $id = Yii::$app->request->post('id');


            if ($id !== null) {
                $model = Staff::find()->where('id=:id and bus_id=:bus_id', ['id' => $id, 'bus_id' => $user->id])->one();
                if ($model !== null) {
                    //$model->id=$id;
                    if ($model->load(Yii::$app->request->post())) {
                        $crop = Yii::$app->request->post('crop_params');
                        if ($crop !== '') {
                            $ar = explode(',', $crop);
                            $model->crop_params['x'] = $ar[0];
                            $model->crop_params['y'] = $ar[1];
                            $model->crop_params['width'] = $ar[2];
                            $model->crop_params['height'] = $ar[3];
                            $model->crop_params['rotate'] = $ar[4];
                            foreach ($model->crop_params as $key => $value)
                                if ($value < 0) $model->crop_params[$key] = 0;


                        }

                        $file = null;
                        $nophoto = Yii::$app->request->post('nophoto');
                        if (($nophoto !== null) && ($nophoto == 1)) {
                            $file = null;
                        }
                        if (($nophoto !== null) && ($nophoto == 2)) {
                            //$file = UploadedFile::getInstance($model, 'imageFile');
                            $file = UploadedFile::getInstance($model, 'imageFile');
                            if ($file === null) {

                                //   echo "by name";
                                $file = UploadedFile::getInstanceByName('imageFile');
                            }

                        }

                        /*
                                                echo "POST<br>";
                                                print_r(Yii::$app->request->post());
                                                echo "FILES<br>";
                                                print_r($_FILES);
                                                echo "file<br>";
                                                print_r($file);
                                                echo "model<br>";
                                                print_r($model);
                                                exit;
                        */
                        if ($model->save(false) && $model->updateEmployee($file)) {


                            return $this->goToUserAccount('staff');
                        }


                    }
                }
                return $this->goToUserAccount('staff');
            }


            if ($model->load(Yii::$app->request->post())) {
                $crop = Yii::$app->request->post('crop_params');
                if ($crop !== '') {
                    $ar = explode(',', $crop);
                    $model->crop_params['x'] = $ar[0];
                    $model->crop_params['y'] = $ar[1];
                    $model->crop_params['width'] = $ar[2];
                    $model->crop_params['height'] = $ar[3];
                    $model->crop_params['rotate'] = $ar[4];
                    foreach ($model->crop_params as $key => $value)
                        if ($value < 0) $model->crop_params[$key] = 0;
                }
                $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
                if ($model->imageFile === null)
                    $model->imageFile = UploadedFile::getInstanceByName('imageFile');

                if ($model->imageFile) {
                    if ($model->addEmployee()) {
                        Yii::$app->session->setFlash('success', 'The employee was added successfully');
                    } else {
                        Yii::$app->session->setFlash('error', 'An error occurred while adding staff');
                    }
                } else {
                    if ($model->validate())
                        $model->save(false);
                }
                return $this->goToUserAccount('staff');
            } else {
                Yii::$app->session->setFlash('error', 'An error occurred while creating employee');
            }


        }
        return $this->goToUserAccount('staff');
        // return $this->goToBusinessPartial('staff/_new', ['model' => $model]);
    }


    /**
     * @param integer $id
     * @return mixed|boolean
     */
    public function actionStaffUpdate($id)
    {
        $model = $this->findStaff($id);
        $model->scenario = Staff::SCENARIO_EDIT;
        if (Yii::$app->request->isPost) {

            if ($model->load(Yii::$app->request->post())) {

                $file = UploadedFile::getInstance($model, 'imageFile');
                if ($model->updateEmployee($file)) {
                    return $this->goToUserAccount('staff');

                }
            }

        } else if (Yii::$app->request->isGet) {
            $model->imageFile = $model->url;
            return $this->goToBusinessPartial('staff/_update', ['model' => $model]);
        }
        // return false;
        return $this->goToUserAccount('staff');
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionStaffGet($id)
    {
        $model = $this->findStaff($id);
        $model->imageFile = $model->url;
        return $this->goToBusinessPartial('staff/_employee', ['emp' => $model]);
    }

    /**
     * @param integer $id
     * @param boolean $up
     * @return mixed|boolean
     */
    public function actionStaffSort($id, $up)
    {
        if (Yii::$app->request->isPost) {
            $staff = $this->findStaff($id);
            return $staff->changeSort($up);
        }
        return false;
    }

    /**
     * @param integer $id
     * @return boolean|int
     */
    /*
    public function actionStaffDelete($id)
    {
        $staff = $this->findStaff($id);
        if ($staff->delete()) {
            return Staff::findCountByBusId($staff->bus_id);
        }
        return false;
    }
    */
    public function actionStaffDelete()
    {
        $business = $this->getBusiness();
        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post('id');
            if ($id !== null) {
                $staff = Staff::find()->where('id=:id and bus_id=:bus_id', ['bus_id' => $business->id, 'id' => $id])->one();
                if ($staff !== null) {
                    if ($staff->delete()) {
                        return Staff::findCountByBusId($staff->bus_id);
                    }
                }
            }
        }
        return false;
    }

    public function actionPublic()
    {
        $business = $this->getBusiness();
        $model = new ProfilePublicForm();
        $model->loadDefaultValues();
        $this->performAjaxValidation($model);
        if (Yii::$app->request->post()) {
            if ($model->load(Yii::$app->request->post()) && $model->changePublic()) {

                Yii::$app->session->setFlash('success', 'The public profile were updated successfully');
                return $this->goToUserAccount('public');
            } else {
                Yii::$app->session->setFlash('error', 'An error occurred while change public profile');
            }
        }
        return $this->goToBusinessPartial('profile/_public', ['model' => $model, 'business' => $business]);
    }

    public function actionGetAgency()
    {
        $business = $this->getBusiness();
        $result = [];
        if (($business !== null) && (Yii::$app->request->isAjax)) {
            $url = Yii::$app->request->post('url');
            $zip = Yii::$app->request->post('zip');
            $address = Yii::$app->request->post('address');
            $businesses = Business::getBusinessesByUrlOrAddress($url, $zip, $address);


            foreach ($businesses as $biz) {
                $result[$biz->id] = [
                    'id' => $biz->id,
                    'name' => $biz->name,
                    'address' => $biz->address,
                    'suite' => $biz->suite,
                    'zip' => $biz->zipCode->zip,
                    'city' => $biz->zipCode->city->name,
                    'state' => $biz->zipCode->city->state->name,
                    'title' => $biz->makeAddressForDropdown(),

                ];
                //$result.="<option value='".$biz->id."' >".$biz->name."</option>";
            }

            //if (count($businesses)>0)
            //return  json_encode($result);

        }
        //return false;
        return json_encode($result);
    }

    public function actionOperation()
    {
        $model = new ProfileOperationForm();
        $model->loadDefaultValues();


        $this->performAjaxValidation($model);

        if (Yii::$app->request->post()) {
            if ($model->load(Yii::$app->request->post()) && $model->changeOperation()) {
                Yii::$app->session->setFlash('success', 'Hours of operation were updated successfully');
                return $this->goToUserAccount('operation');
            } else {
                Yii::$app->session->setFlash('error', 'An error occurred while change hours of operation');
            }
        }

        return $this->goToBusinessPartial('profile/_hours', ['model' => $model, 'edit' => 1]);
    }

    /**
     * Return the base operation view
     * @param type $edit
     * @return type
     */
    public function actionGetOperation($edit)
    {
        $model = new ProfileOperationForm();
        $model->loadDefaultValues();

        return $this->goToBusinessPartial('profile/_hours_base', ['model' => $model, 'edit' => $edit]);
    }

    public function actionReview()
    {
        $business = $this->getBusiness();
        $model = new ProfileReviewForm();
        $model->loadDefaultValues();
        $this->performAjaxValidation($model);
        if (Yii::$app->request->post()) {
            $data = Yii::$app->request->post();
            if ($model->load($data) && $model->changeReview()) {
                Yii::$app->session->setFlash('success', 'Yelp profile URL was added successfully');
            } else {
                Yii::$app->session->setFlash('error', 'An error occurred while adding Yelp profile URL');
            }
            return $this->goToUserAccount('review');
        }
        return $this->goToBusinessPartial('profile/_reviews', ['model' => $model, 'biz' => $business]);
    }

    public function actionSite()
    {
        $model = new VanityNameForm();
        $this->performAjaxValidation($model);

        $user = User::findIdentity(Yii::$app->user->id);
        $user = $user->getAccountByRole();

        if ($user instanceof Business) {
            return $this->goToBusinessPartial('profile/_site', ['business' => $user, 'vanity' => $model,]);
        }

    }

    public function actionSettingsVanity()
    {


        $vanity = new VanityNameForm();
        $this->performAjaxValidation($vanity);
        if (Yii::$app->request->isPost) {
            $user = User::findIdentity(Yii::$app->user->id);
            $user = $user->getAccountByRole();

            if ($user instanceof Business) {
                if (($vanity->load(Yii::$app->request->post())) && ($vanity->validate())) {
                    $user->vanity_name = $vanity->vanity_name;
                    $user->vanity_changed++;
                    $user->save(false);
                }
                //return $this->goToUserAccount('settings');
            }
        }
        return $this->goToUserAccount('settings');
    }


    public function actionSettings()
    {
        $model = new ProfileSettingsForm();
        $vanity = new VanityNameForm();
        $user = User::findIdentity(Yii::$app->user->id);
        $old_email = $user->email;
        $user = $user->getAccountByRole();
        $consumer = false;
        if ($user instanceof Consumer) {
            $consumer = true;
            $model->scenario = ProfileSettingsForm::SCENARIO_CONSUMER;
        } else if ($user instanceof Business) {
            $vanity->vanity_name = $user->vanity_name;
            $vanity->vanity_changed = $user->vanity_changed;

        }
        $model->is_consumer = $consumer;
        $model->loadDefaultValues();
        $this->performAjaxValidation($model);
        $this->performAjaxValidation($vanity);

        if (Yii::$app->request->post()) {

            if ($model->load(Yii::$app->request->post()) && $model->changeSettings()) {
                return $this->goToUserAccount('settings');
            }
            return $this->goToUserAccount('settings');
        } else

            $model->current_email = $old_email;
        return $this->goToBusinessPartial('profile/_account', [
            'model' => $model,
            'consumer' => $consumer,
            'vanity' => $vanity,

        ]);
    }

    public function actionSetup()
    {
        $user = $this->getBusiness();
        $complete = $user->getAccountComplete();
        $data = array();
        $data['Public profile']['value'] = empty($complete['phone']) ? false : true;
        $data['Public profile']['url'] = Url::to(['user/account', 'active' => 'public']);
        $data['Profile photo']['value'] = ($complete['photo'] > 0) ? true : false;
        $data['Profile photo']['url'] = Url::to('/account/photo-profile-goto');
        $data['Hours of operation']['value'] = ($complete['operation'] > 0) ? true : false;
        $data['Hours of operation']['url'] = Url::to(['user/account', 'active' => 'operation']);
        $data['Wizerd URL']['value'] = empty($complete['vanity_name']) ? false : true;
        $data['Wizerd URL']['url'] = Url::to(['user/account', 'active' => 'site']);
        $data['Menu']['value'] = ($complete['menu'] > 0) ? true : false;
        $data['Menu']['url'] = Url::to(['user/account', 'active' => 'menu']);
        $mandatory_info = array(
            'header' => 'Mandatory',
            'class' => 'panel-success',
            'data' => $data
        );
        $data = array();
        // $data['Promotion'] = false;
        $data['Photo']['value'] = ($complete['photo_dif'] > 0) ? true : false;
        $data['Photo']['url'] = Url::to('/account/photo-goto');
//        $data['Staff']['value'] = ($complete['staff'] > 0) ? true : false;
//        $data['Staff']['url'] = Url::to(['user/account', 'active' => 'staff']);
        $data['Review']['value'] = empty($complete['yelp_url']) ? false : true;
        $data['Review']['url'] = Url::to(['user/account', 'active' => 'review']);
        $highly_info = array(
            'header' => 'Recommended',
            'class' => 'panel-danger',
            'data' => $data
        );
        $info = array($mandatory_info, $highly_info);
        unset($data);
        unset($mandatory_info);
        unset($highly_info);
        return $this->renderPartial('setup', ['info_blocks' => $info]);
    }

    public function actionSetCurrentMenu()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->session->set('current-menu', Yii::$app->request->post('menu'));
        }
    }

    public function actionMenuDelete()
    {
        if (Yii::$app->request->isAjax) {
            if (Yii::$app->request->post('menu_id')) {
                $menu_id = Yii::$app->request->post('menu_id');
                $menu = $this->findMenu($menu_id);
                if ($menu == null) {
                    return false;
                }
                $menu->delete();
                $this->goToUserAccount('menu');
            }
        }
        return false;
    }

    public function actionDeleteCategory()
    {
        if (Yii::$app->request->isAjax) {
            if (Yii::$app->request->post('category_id')) {
                $category_id = Yii::$app->request->post('category_id');
                $category = $this->findCustomCategory($category_id);
                if ($category == null)
                    return false;
                /* if ($category->type != CustomCategory::TYPE_CUSTOM)
                  return false;
                 */
                $category->delete();
                $this->goToUserAccount('menu');
            }
        }
        return false;
    }

    public function actionCancelCategory()
    {
        $response = array();
        $response["success"] = 0;
        if (Yii::$app->request->isAjax) {
            if (Yii::$app->request->post('category_id')) {
                $category_id = Yii::$app->request->post('category_id');
                $category = $this->findCustomCategory($category_id);
                if ($category != null) {
                    $category->delete();
                    $response["success"] = 1;
                }
            }
        }
        echo Json::encode($response);
    }

    public function actionUpdateCategoryParam()
    {
        if (Yii::$app->request->isAjax) {

            $category_id = Yii::$app->request->post('category_id');
            $category = $this->findCustomCategory($category_id);
            if ($category !== null) {
                if (Yii::$app->request->post('type') == 'disclaimer')
                    $category->disclaimer = Yii::$app->request->post('value');
                else if (Yii::$app->request->post('type') == 'description')
                    $category->description = Yii::$app->request->post('value');
                else if (Yii::$app->request->post('type') == 'title') {
                    $category->title = Yii::$app->request->post('value');
                    if (($category->validate()) && ($category->save()))
                        $this->goToUserAccount('menu');
                    else
                        return false;
                }
                if (($category->validate()) && ($category->save())) {
                    print_r($category->errors);
                    return true;
                }
            }
        }


        return false;
    }

    public function actionUpdateCategoryName()
    {
        if (Yii::$app->request->isAjax) {
            if (Yii::$app->request->post('category_id')) {
                $category_id = Yii::$app->request->post('category_id');
                $category = $this->findCustomCategory($category_id);
                if (Yii::$app->request->post('title'))
                    $category->title = Yii::$app->request->post('title');
                else
                    $category->title = null;

                $category->save();
            }
        }
        return false;
    }

    public function actionUpdateCategoryDescription()
    {
        if (Yii::$app->request->isAjax) {
            if (Yii::$app->request->post('category_id')) {
                $category_id = Yii::$app->request->post('category_id');
                $category = $this->findCustomCategory($category_id);
                if (Yii::$app->request->post('description'))
                    $category->description = Yii::$app->request->post('description');
                else
                    $category->description = null;

                $category->save();
            }
        }
        return false;
    }

    public function actionUpdateCategoryDisclaimer()
    {
        if (Yii::$app->request->isAjax) {
            if (Yii::$app->request->post('category_id')) {
                $category_id = Yii::$app->request->post('category_id');
                $category = $this->findCustomCategory($category_id);
                if (Yii::$app->request->post('disclaimer'))
                    $category->disclaimer = Yii::$app->request->post('disclaimer');
                else
                    $category->disclaimer = null;

                $category->save();
            }
        }


        return false;
    }

    /*
      public function actionUpdateMenuDescription() {
      if (Yii::$app->request->isAjax) {
      if (Yii::$app->request->post('menu_id')) {
      $menu_id = Yii::$app->request->post('menu_id');
      $menu = $this->findMenu($menu_id);
      if (Yii::$app->request->post('param'))
      $menu->description = Yii::$app->request->post('param');
      else
      $menu->description = null;

      $menu->save();
      }
      }


      return false;
      }

      public function actionUpdateMenuDisclaimer() {
      if (Yii::$app->request->isAjax) {
      if (Yii::$app->request->post('menu_id')) {
      $menu_id = Yii::$app->request->post('menu_id');
      $menu = $this->findMenu($menu_id);
      if (Yii::$app->request->post('param'))
      $menu->disclaimer = Yii::$app->request->post('param');
      else
      $menu->disclaimer = null;

      $menu->save();
      }
      }


      return false;
      }
     */

    public function actionUpdateMenuParam()
    {
        if (Yii::$app->request->isAjax) {
            if (Yii::$app->request->post('menu_id')) {
                $menu_id = Yii::$app->request->post('menu_id');
                $menu = $this->findMenu($menu_id);
                if (Yii::$app->request->post('type') == 'disclaimer')
                    $menu->disclaimer = Yii::$app->request->post('value');
                else if (Yii::$app->request->post('type') == 'description')
                    $menu->description = Yii::$app->request->post('value');
                else if (Yii::$app->request->post('type') == 'title') {
                    $menu->title = Yii::$app->request->post('value');
                    if (($menu->validate()) && ($menu->save())) {
                        $this->goToUserAccount('menu');
                        return true;
                    } else
                        return false;
                }

                if (($menu->validate()) && ($menu->save()))
                    return true;
            }
        }


        return false;
    }

    public function actionGetCustomCategoryParam()
    {
        if (Yii::$app->request->isAjax) {
            if ((Yii::$app->request->post('category_id')) && (Yii::$app->request->post('param'))) {
                $category = $this->findCustomCategory(Yii::$app->request->post('category_id'));
                $param = Yii::$app->request->post('param');

                return $category->$param;
            }
        }
        return false;
    }

    /*
      public function actionMenuPriceTemplateAdd($menu_id, $service_id) {
      $user = $this->getBusiness();

      $attr = AttributeValue::getAttributeValuesByMenu($menu_id);
      $additional = AdditionalAttribute::getAdditionalAttributeByMenu($menu_id);

      if (Yii::$app->request->isPost) {
      if ($model->load(Yii::$app->request->post()) && $model->attachCustom()) {
      Yii::$app->session->setFlash('success', 'The template was added successfully');
      } else {
      Yii::$app->session->setFlash('error', 'An error occurred while adding template');
      }
      $this->goToUserAccount('menu');
      } else if (Yii::$app->request->isAjax) {

      $model = Template::getTemplatesByMenu($menu_id);

      return $this->renderAjax('menu/_custom_template', [
      'model' => $model,
      'menu_id' => $menu_id,
      'service_id' => $service_id,
      'attr' => $attr,
      'additional' => $additional
      //'user'=>   $attr
      ]);
      }
      return false;
      }

      public function actionMenuPriceTemplateDelete($service_id) {
      $service = CustomService::find()->where('id=:id')->addParams([':id' => $service_id])->one();
      if ($service == null) {
      return false;
      }
      $service->fill_status = CustomService::STATUS_NEW;
      if ($service->temp_id != 1) {
      Pricing::clearPricingsByCustomService($service_id);
      $templates = $service->category->menu->industry->priceTemplate;
      if (count($templates) > 1)
      $service->temp_id = 1;
      else
      $service->temp_id = $templates[0]->id;
      if ($service->validate())
      $service->save();
      }

      if ($service->type == CustomService::TYPE_FIXED) {
      $service->title = null;
      if ($service->validate())
      $service->save();
      }
      $this->goToUserAccount('menu');
      return true;
      }

      public function actionSelectPriceTemplate($service_id, $template_id) {
      $service = CustomService::find()->where('id=:id')->addParams([':id' => $service_id])->one();
      if ($service != null) {
      $service->fill_status = CustomService::STATUS_NEW;
      if ($service->temp_id != 1) {
      Pricing::clearPricingsByCustomService($service_id);
      }
      // clearing service title
      //if ($service->type!=CustomService::TYPE_DEFAULT)
      //     $service->title="";

      $service->temp_id = $template_id;
      if (($service->save()) && (Pricing::setDefaults($service_id, $template_id))) {
      Yii::$app->session->setFlash('success', 'price template selected');
      $this->goToUserAccount('menu');
      }
      } else {
      Yii::$app->session->setFlash('error', 'error when selecting template');
      $this->goToUserAccount('menu');
      }
      }

      public function actionMakePricingForService($service_id, $template_id) {

      $service = CustomService::find()->where('id=:id')->addParams([':id' => $service_id])->one();
      if ($service == null)
      return false;

      $pricing = new Pricing();
      $pricing->ser_id = $service_id;
      //$pricing->price = 0;
      $pricing->id_attr_val = 0;
      if (!$pricing->save())
      return false;

      $this->goToUserAccount('menu');
      return true;
      }

      public function actionSavePricing($service_id) {


      $service = CustomService::find()->where('id=:id')->addParams([':id' => $service_id])->one();
      if ($service != null) {
      if (Yii::$app->request->isPost) {
      if (Yii::$app->request->post('params')) {
      $params = Yii::$app->request->post('params');
      $description = null;

      $result = array();
      for ($i = 0; $i < count($params); $i++) {
      $id = split('-', $params[$i]['name'])[1];

      if (stristr($params[$i]['name'], 'title_pricing'))
      $service_title = $params[$i]['value'];

      if (stristr($params[$i]['name'], 'price_pricing')) {
      $result[$id]['price'] = str_replace(',', '', $params[$i]['value']);
      }

      if (stristr($params[$i]['name'], 'description_pricing')) {
      $result[$id]['description'] = $params[$i]['value'];
      if (($service->template->desc_type == 1) && ($params[$i]['value']))
      $description = $params[$i]['value'];
      }

      if (stristr($params[$i]['name'], 'time_pricing'))
      if ((isset($params[$i]['value'])) && ($params[$i]['value'] != ''))
      $result[$id]['id_attr_val'] = $params[$i]['value'];
      //return true;
      else
      $result[$id]['id_attr_val'] = 0;
      }

      if (isset($service_title) && ($service->type != CustomService::TYPE_DEFAULT)) {
      $service->title = $service_title;
      if (!$service->validate())
      return false;
      if (!$service->save()) {
      // Yii::$app->session->setFlash('error', 'error saving service');
      //$this->goToUserAccount('menu');
      return false;
      }
      }

      foreach ($result as $key => $current) {
      $pricing = Pricing::find()->where('id=:id')->addParams([':id' => $key])->one();
      if ($pricing !== null) {
      $pricing->price = $current['price'];
      if (isset($current['id_attr_val']))
      $pricing->id_attr_val = $current['id_attr_val'];
      if ($service->template->desc_type == 1) {
      $pricing->description = $description;
      } else {
      $pricing->description = $current['description'];
      }
      if (!$pricing->validate())
      return false;
      if (!$pricing->save()) {
      return false;
      }
      }
      }

      $service->fill_status = CustomService::STATUS_FILLED;
      if (!$service->validate())
      return false;
      if (!$service->save())
      return false;


      // return true;
      }
      }
      } else {
      Yii::$app->session->setFlash('error', 'error' . $service_id . "=" . $template_id);
      $this->goToUserAccount('menu');
      }
      return true;
      }
     */
    /*
      public function actionMenuServiceDelete($service_id) {
      $service = CustomService::find()->where('id=:id')->addParams([':id' => $service_id])->one();

      if ($service === null) {
      return false;
      }
      $service->delete();
      $this->goToUserAccount('menu');
      return true;
      }
     */

    public function actionMenuServiceDelete($service_id)
    {
        if (Yii::$app->request->isPost) {
            $srv_id = Yii::$app->request->post('srv_id');
            if (isset($srv_id)) {
                $service = CustomService::find()->where('id=:id')->addParams([':id' => $service_id])->one();

                if ($service === null) {
                    return false;
                }
                $service->delete();

                return $this->renderAjax('menu/_detail_services', [
                    'category' => $service->category,
                    'services' => $service->category->services,
                    'menu_id' => $service->category->menu->id,
                ]);
            }
        }
        return false;
    }

    /**
     *
     * @return boolean
     */
    public function actionSavePromo()
    {
        if (Yii::$app->request->isAjax) {

            if (Yii::$app->user->isGuest) {
                $this->redirect(Url::to(['site/login']));
            }
            $user = User::findIdentity(Yii::$app->user->id);
            if ($consumer = $user->getConsumer()) {
                $srv_id = Yii::$app->request->post('srv');
                $tier_id = Yii::$app->request->post('tier');
                $promo_id = Yii::$app->request->post('promo');


                $srv = CustomService::find()->where('id=:id', ['id' => $srv_id])->one();
                $tier = Tier::find()->where('id=:id', ['id' => $tier_id])->one();
                $promo = Promo::find()->where('id=:id', ['id' => $promo_id])->one();
                if (($srv !== null) && ($tier !== null) && ($promo !== null)) {
                    if (!WishList::find()->where('user_id=:user_id', ['user_id' => $consumer->id])->andWhere('tier_id=:tier_id', ['tier_id' => $tier_id])->one()) {
                        $wish = new WishList();

                        $wish->user_id = $consumer->id;
                        $wish->promo_id = $promo_id;
                        $wish->tier_id = $tier_id;
                        $wish->standart_price = $tier->price;
                        $wish->promo_price = $tier->getPromoPrice();
                        $wish->promo_cat_title = $srv->category->title;
                        $wish->promo_srv_title = $srv->title;

                        if ($wish->save()) {
                            return true;
                        }
                    }
                }
            }
            return false;
        }
        // print_r(\yii\helpers\Url::previous());
        //print_r(Yii::$app->request);
        $this->goHome();
    }

    public function getWishlist()
    {
        if (!Yii::$app->user->isGuest) {
            $user = User::findIdentity(Yii::$app->user->id);
            if ($user->getConsumer()) {
                return $user->getWishlist();
            }
        }
    }

    public function actionFieldAdd()
    {
        if (Yii::$app->request->ispost) {
            $field = new Field();
            if (($field->load(Yii::$app->request->post())) && ($field->validate())) {

                if ($field->createCustomField()) {
                    $this->goToUserAccount('menu');
                } else
                    $this->goToUserAccount('menu');
            }
        }

        if (Yii::$app->request->isAjax) {
            $user = User::findIdentity(Yii::$app->user->id);

            $cat_id = Yii::$app->request->post('cat_id');

            $field = new Field();
            $field->cat_id = $cat_id;
            return $this->renderAjax('menu/_field', ['model' => $field,]);
        }
    }

    public function actionMenuSrvFieldDelete($field_id)
    {

        $field = Field::find()->where('id=:id', ['id' => $field_id])->one();

        if ($field->delete())
            $this->goToUserAccount('menu');
    }

    public function actionMenuServiceTierAdd()
    {
        if (Yii::$app->request->isPost) {
            $srv_id = Yii::$app->request->post('srv_id');
            $service = CustomService::find()->where('id=:id', ['id' => $srv_id])->one();
            $service->createTier();
            //$service->fill_status=0;

            return $this->renderAjax('menu/_detail_services', [
                'category' => $service->category,
                'services' => $service->category->services,
                'menu_id' => $service->category->menu->id,
            ]);
            //$this->goToUserAccount('menu');
        }
    }

    public function actionMenuServiceTierDelete()
    {
        if (Yii::$app->request->isPost) {
            $tier_id = Yii::$app->request->post('tier_id');
            $tier = Tier::find()->where('id=:id', ['id' => $tier_id])->one();
            $tier->delete();

            return $this->renderAjax('menu/_detail_services', [
                'category' => $tier->service->category,
                'services' => $tier->service->category->services,
                'menu_id' => $tier->service->category->menu->id,
            ]);
            //$this->goToUserAccount('menu');
        }
    }

    public function actionMenuServiceTiersEmptyDelete()
    {
        if (Yii::$app->request->isPost) {
            $srv_id = Yii::$app->request->post('srv_id');
            $service = CustomService::find()->where('id=:id', ['id' => $srv_id])->one();
            if (!$service->deleteEmptyTiers())
                return false;


            return $this->renderAjax('menu/_detail_services', [
                'category' => $service->category,
                'services' => $service->category->services,
                'menu_id' => $service->category->menu->id,
            ]);
            //$this->goToUserAccount('menu');
        }
        return false;
    }

    public function actionMenuSrvFieldSave()
    {
        //print_r(Yii::$app->request->post('field_id'));
        if (Yii::$app->request->isAjax) {
            $field_id = Yii::$app->request->post('field_id');
            $field = Field::find()->where('id=:id', ['id' => $field_id])->one();
            $field->visible = Yii::$app->request->post('visible');
            $field->title = Yii::$app->request->post('title');
            if (($field->validate()) && ($field->save())) {
                print_r($field);
                return true;
            }
        }

        return false;
    }

    public function actionMenuSrvCatFieldSave()
    {
        if (Yii::$app->request->isAjax) {
            $cat_id = Yii::$app->request->post('cat_id');
            $category = CustomCategory::find()->where('id=:id', ['id' => $cat_id])->one();
            $param = Yii::$app->request->post('param');
            if ($param == 'price') {
                $category->price_title = Yii::$app->request->post('title');
                $category->price_title_vis = Yii::$app->request->post('visible');
            }
            if ($param == 'service') {
                $category->srv_title = Yii::$app->request->post('title');
                $category->srv_title_vis = Yii::$app->request->post('visible');
            }
            if (($category->validate()) && ($category->save()))
                return true;
        }
        return false;
    }

    public function actionServiceAdditionalFieldSort($id, $up)
    {
        if (Yii::$app->request->isPost) {
            $field = Field::find()->where('id=:id', ['id' => $id])->one();
            $menu = $field->category->menu;
            if ($field !== null) {
                $result = $field->changeSort($up);
                if ($result) {
                    return $this->goToBusinessPartial('menu/_detail_categories', [
                        'menu' => $menu,
                        'id' => $menu->id,
                        'categories' => $menu->categories,
                        'title' => $menu->title,
                    ]);
                }
            } else {
                throw new NotFoundHttpException('Menu category does not exist.');
            }
        }
        return false;
    }

    public function actionMenuServiceSave()
    {
        if (Yii::$app->request->isAjax) {
            $srv_id = Yii::$app->request->post('srv_id');
            $service = CustomService::find()->where('id=:id', ['id' => $srv_id])->one();
            if ($service !== null) {
                $srvTitle = Yii::$app->request->post('service');
                $prices = Yii::$app->request->post('prices');
                $fields = Yii::$app->request->post('fields');
                $service->title = $srvTitle;
                if ($service->validate())
                    $service->save();


                $tiers = [];

                foreach ($prices as $price) {

                    if ($array = explode('tier-price_', $price['name'])) {

                        $tiers[$array[1]] = Tier::find()->where('id=:id', ['id' => $array[1]])->one();
                        $tiers[$array[1]]->price = str_replace(',', '', $price['value']);
                        if ((!$tiers[$array[1]]->validate()) || (!$tiers[$array[1]]->save())) {

                            return false;
                        }
                    }
                }
                if (count($fields) > 0) {
                    foreach ($fields as $field) {
                        if ($array = explode('_', $field['name'])) {
                            $tier_id = $array[1];

                            if (isset($tiers[$tier_id])) {

                                $field_id = $array[2];
                                $value = $field['value'];
                                $fieldValue = FieldValue::find()->where('id=:id AND tier_id=:tier_id', ['id' => $field_id, 'tier_id' => $tier_id])->one();
                                if ($fieldValue !== null) {
                                    $fieldValue->value = $value;
                                    if ((!$fieldValue->validate()) || (!$fieldValue->save())) {

                                        return false;
                                    }
                                }
                            }
                        }
                    }
                }
                //need!!!!
                $service->title = $srvTitle;
                if ($service->validate())
                    $service->save();
            }
        }
        return true;
    }

    public function actionDeleteConsumerPromo()
    {
        $response = [];
        $response['status'] = 'error';
        $response['message'] = 'Error. Please try again.';
        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->get('id');
            $item = WishList::find()->where('id=:id', ['id' => $id])->one();
            if ($item->delete()) {
                $response['status'] = 'success';
                $response['message'] = 'The promotion was deleted successfully.';
            }
        }
        Yii::$app->session->setFlash($response['status'], $response['message']);
        return $this->goToUserAccount('promo-d');
    }


}
