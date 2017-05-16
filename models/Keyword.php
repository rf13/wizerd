<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "keyword".
 *
 * @property integer $id
 * @property string $word

 *
 * @property KeywordGroup $kwGroup
 */
class Keyword extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'keyword';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['word'], 'required'],
                
                [['word'], 'string', 'max' => 50],
               
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
                'id' => 'ID',
                'word' => 'Word',
                
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
   

    /*
      public static function createKeywords($text,$keyWordGroupId=0){



      // $wordsArray = explode(' ', str_replace('  ', ' ', trim($text)));
      $wordsArray =array_unique(explode(' ',  trim($text)));

      if (count($wordsArray) > 0) {
      $transaction = Yii::$app->db->beginTransaction();
      try {
      if ($keyWordGroupId!==0)
      {
      $kwGroup=KeywordGroup::find()->where('id=:id',['id'=>$keyWordGroupId])->one();
      }
      if($kwGroup!==null)
      {
      $kwGroup->deleteKeywords();
      }
      else
      {
      $kwGroup = new KeywordGroup();
      if (!$kwGroup->save()) {
      $transaction->rollBack();
      return false;
      }
      }


      $keyWordGroupId=$kwGroup->id;

      foreach ($wordsArray as $word) {
      $word=strtolower($word);
      if (strlen($word )> 1) {
      $countUnsearch = KeywordUnsearch::find()->select('count(*)')->where('word=:word', ['word' => $word])->scalar();
      if ($countUnsearch == 0)
      {
      $keyWord=new Keyword();
      $keyWord->word=$word;
      $keyWord->kw_group_id=$kwGroup->id;
      if(!$keyWord->save())
      {
      $transaction->rollBack();
      return false;
      }
      }
      }

      }
      $transaction->commit();
      return $keyWordGroupId;
      } catch (Exception $e) {
      $transaction->rollBack();
      return false;
      }

      }

      }
     */

    public static function createKeywords($text, $keyWordGroupId = 0) {


$text=preg_replace('/[^a-zA-Z0-9\s]/','',$text);

        $wordsArray = array_unique(explode(' ', trim($text)));

            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($keyWordGroupId !== 0) {
                    $kwGroup = KeywordGroup::find()->where('id=:id', ['id' => $keyWordGroupId])->one();
                }
                if ($kwGroup !== null) {
                    
                    KwgKw::deleteAll('kwg_id=:kwg_id',['kwg_id'=>$kwGroup->id]);
                    //$kwGroup->deleteKeywords();
                    
                } else {
                    $kwGroup = new KeywordGroup();
                    if (!$kwGroup->save()) {
                        $transaction->rollBack();
                        return false;
                    }
                }


                $keyWordGroupId = $kwGroup->id;

                foreach ($wordsArray as $word) {
                    $word = strtolower($word);
                    if (strlen($word) > 1) {
                        $countUnsearch = KeywordUnsearch::find()->select('count(*)')->where('word=:word', ['word' => $word])->scalar();
                        if ($countUnsearch == 0) {
                            $keyWord = Keyword::find()->where('word=:word', ['word' => $word])->one();
                            if ($keyWord == null) {
                                $keyWord = new Keyword();
                                $keyWord->word = $word;
                                // $keyWord->kw_group_id = $kwGroup->id;
                                if (!$keyWord->save()) {

                                    $transaction->rollBack();
                                    return false;
                                }
                            }
                            //print_r($keyWord);
                            $kwgKw = new KwgKw();
                            $kwgKw->kwg_id = $kwGroup->id;
                            $kwgKw->kw_id = $keyWord->id;
                            if (!$kwgKw->save()) {
                                $transaction->rollBack();
                                return false;
                            }
                        }
                    }
                }
                $transaction->commit();

                return $keyWordGroupId;
            } catch (Exception $e) {
                $transaction->rollBack();
                return false;
            }
        }
   // }

}
