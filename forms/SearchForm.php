<?php
namespace app\forms;

use app\components\exceptions\ZipInactiveException;
use app\components\exceptions\ZipNotExistsException;
use app\models\Business;
use app\models\Keyword;
use app\models\RequestLog;
use app\models\SaveSearch;
use app\models\Tier;
use app\models\ZipCode;
use Yii;
use yii\base\Model;
use app\models\User;
use yii\data\Pagination;
use yii\db\Query;
use yii\helpers\Html;
use yii\helpers\Url;

class SearchForm extends Model
{
    public $zip;
    public $search;

    public function rules()
    {
        return [
            [
                [
                    'zip',
                    'search'
                ],
                'required',
                'message' => '{attribute} can’t be blank.'
            ],
            [
                'zip',
                'string',
                'max' => 5,
                'min' => 5,
                'tooShort' => '{attribute} must contain {min} digits.',
                'tooLong' => '{attribute} must contain {max} digits.'
            ],
            [
                'zip',
                function ($attribute, $params) {
                    if (!($zip = ZipCode::findOne([
                        'zip' => $this->zip
                    ]))
                    ) {
                        throw new ZipNotExistsException;
                    }
                    if ($zip->active != 1) {
                        throw new ZipInactiveException;
                    }
                }
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'zip' => 'Zip code',
            'search' => 'Search phrase'
        ];
    }

    /**
     * search
     */
    public function makeSearch()
    {
        $zip = ZipCode::find()
            ->where('zip=:zip and active=1', ['zip' => $this->zip])
            ->one();
        $letters = array(
            'a',
            'b',
            'c',
            'd',
            'e',
            'f',
            'g'
        );
        $savedTiers = [];
        $user = Yii::$app->user->identity;
        if (!Yii::$app->user->isGuest) {
            if ($user->getConsumer()) {

                $sTiers = $user->getSavedTiers();

                foreach ($sTiers as $tier) {
                    $savedTiers[] = $tier['id'];
                }
            }
        };
        $saveSearch = SaveSearch::findBySearch($this->search, $zip->id);
        if (!$saveSearch) {

            $saveSearch = new SaveSearch();
            $saveSearch->ip = Yii::$app->request->userIP;
            $saveSearch->zip_id = $zip->id;
            $saveSearch->search = $this->search;
            if ((!Yii::$app->user->isGuest) && ($user->getConsumer())) {
                $saveSearch->cons_id = $user->consumer->id;
            }
            $saveSearch->save();
        }
        $requestLog = new RequestLog();
        $requestLog->ip = Yii::$app->request->userIP;
        if ((!Yii::$app->user->isGuest) && ($user->getConsumer())) {
            $requestLog->cons_id = $user->consumer->id;
        }
        $requestLog->save();
        //if (!User::getSearchLimit(Yii::$app->request->userIP)) { // check have IP  get search Limit
        $busineses = Business::searchByZipcode($zip->zip);
        if (count($busineses) > 0 && ($array = $this->makeSearchInternal($zip, $busineses))) {
            $countQuery = 'select count(*) from (' . $array['query'] . ') s0';
            $countResults = Yii::$app->db->createCommand($countQuery, $array['params'])
                ->queryScalar();
            if ($countResults > 0) {
                $pages = new Pagination([
                    'totalCount' => $countResults,
                    'page' => Yii::$app->request->get('page') - 1,
                    'defaultPageSize' => 8,

                ]);
                $array['query'] = $array['query'] . ' limit ' . $pages->offset . ', ' . $pages->limit;

                $result = Yii::$app->db->createCommand($array['query'], $array['params'])
                    ->queryAll();
                $resultArray = [];
                foreach ($result as $res) {
                    $unique_result[] = $res['dist'];
                }
                $unique_result = array_keys(array_flip($unique_result));
                $unique_result = array_flip($unique_result);
                foreach ($result as $res) {
                    $resultArray[$res['tier']]['sum'] = $res['sum'];
                    $resultArray[$res['tier']]['letter'] = $letters[$unique_result[$res['dist']]];
                }
                $i = 0;
                $tiers = [];
                foreach ($resultArray as $key => $value) {
                    $tier = Tier::find()
                        ->with('service', 'service.category', 'service.category.menu', 'service.category.menu.business')
                        ->where('id=:id', ['id' => $key])
                        ->one();
                    $tiers[$i]['tier'] = $tier;
                    $tiers[$i]['val'] = $value['sum'];
                    $tiers[$i]['letter'] = $value['letter'];
                    $tiers[$i]['service'] = $tier->service;
                    $tiers[$i]['category'] = $tier->service->category;
                    $tiers[$i]['menu'] = $tier->service->category->menu;
                    $tiers[$i]['business'] = $tier->service->category->menu->business;
                    $tiers[$i]['zipCode'] = $tier->service->category->menu->business->zipCode;
                    $tiers[$i]['city'] = $tier->service->category->menu->business->zipCode->city;
                    $tiers[$i]['price'] = $tier->price;
                    $tiers[$i]['promoPrice'] = $tier->getPromoPrice();
                    $i++;

                    $f = $tier->service->fields;
                }
                if (!Yii::$app->user->isGuest) {
                    if ($user->getConsumer()) {
                        $sTiers = $user->getSavedTiers();
                        foreach ($sTiers as $tier) {
                            $savedTiers[] = $tier['id'];
                        }
                    }
                }

                return [
                    'tiers' => $tiers,
                    'zip' => $zip,
                    'savedTiers' => $savedTiers,
                    'pages' => $pages,
                ];
            }
        }
        //$end = microtime(true) - $start;

        return $zip;
    }

    /**
     * search
     */
    protected function makeSearchInternal($zip, $busineses)
    {
        $kwArray = explode(' ', str_replace('  ', ' ', $this->search));
        $count = count($kwArray);
        $ids = [];
        $idsStr = '';
        for ($i = 0; $i < $count; $i++) {
            $kw = Keyword::find()
                ->where('word=:word', ['word' => $kwArray[$i]])
                ->one();
            if ($kw !== null) {
                $ids[$i] = $kw->id;
                $idsStr = $idsStr . $ids[$i] . ',';
            } else {
                $ids[$i] = 0;
            }
        }

        if (strlen($idsStr) > 0) {
            $query = "
                    select
                      s0.*,
                      sqrt((b.latitude-:zip_latitude)*(b.latitude-:zip_latitude)+(b.longitude-:zip_longitude)*(b.longitude-:zip_longitude)) as dist,
                      b.id as bid
                    from
                    (
                      select
                        *
                        from  ";
            $idsStr[strlen($idsStr) - 1] = '';
            $resultArray = [];

            for ($biz = 0; $biz < count($busineses); $biz++) {

                $query = $query . '(
                       select
                            s.id as tier,
                            max(s.value) as flag,
                            sum(s.pro) as sum
                       from
                       (
                            select
                                t.id as id,
                                tkv.value as value,
                ';
                $append = '';
                for ($i = 0; $i < count($ids) - 1; $i++) {

                    $mn = count($ids) - $i;
                    $append = $append . 'if((tkv.kw_id=' . $ids[$i] . '),' . $mn . '*tkv.value, ';

                }
                $append .= 'tkv.value';
                for ($i = 0; $i < count($ids) - 1; $i++) {
                    $append .= ')';
                }

                $query = $query . $append . ' as pro ';
                $query = $query . '
                                from
                                    business b
                                    left join menu m on m.bus_id=b.id
                                    left join custom_category cc on cc.menu_id=m.id
                                    left join custom_service cs on cs.cat_id=cc.id
                                    left join tier t on t.srv_id=cs.id
                                    left join tier_kw_value tkv on tkv.tier_id=t.id
                                where
                                    b.id =:biz_' . $biz . '
                                    and tkv.kw_id in (
                ';
                $append = '';
                for ($j = 0; $j < count($ids); $j++) {
                    $append = $append . ':id_' . $j . ',';

                }
                $append = substr($append, 0, strlen($append) - strlen(','));
                $query = $query . $append;

                $query = $query . ')
                                 )s
                                 group by s.id
                                 order by sum desc
                                 limit 2
                                 )
                ';
                if ($biz == 0) {
                    $query = $query . ' as biz_:biz_' . $biz . ' ';
                }
                if ($biz != count($busineses) - 1) {
                    $query = $query . '
                                    union
                    ';
                }

            }
            $query = $query . '
                           )s0
                           left join tier t on s0.tier=t.id
                           left join custom_service cs on cs.id=t.srv_id
                           left join custom_category  cc on cc.id=cs.cat_id
                           left join menu m on m.id=cc.menu_id
                           left join business b on b.id=m.bus_id

                       order by sum desc,t.char_length desc,dist ,t.price,t.id
            ';

            $paramArray = [];
            $paramArray['zip_latitude'] = $zip->latitude;
            $paramArray['zip_longitude'] = $zip->longitude;
            for ($biz = 0; $biz < count($busineses); $biz++) {
                $paramArray['biz_' . $biz] = $busineses[$biz]->id;
            }

            for ($j = 0; $j < count($ids); $j++) {
                //$append=$append.':id_'.$j.',';
                $paramArray['id_' . $j] = $ids[$j];
            }

            $a['query'] = $query;
            $a['params'] = $paramArray;

            return $a;
        }

        return false;
    }
}

?>
