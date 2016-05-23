<?php

namespace app\modules\merchant_signup\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\MerchantSignup;
use app\models\MerchantSignupSearch;
use app\models\Company;
use yii\data\ActiveDataProvider;
use yii\web\Response;
use app\models\Tag;

/**
 * Default controller for the `MerchantSignup` module
 * @author Ganjar Setia M <ganjar.setia@ebizu.com>
 */
class DefaultController extends Controller
{
	private $_pageSize = 20;

	public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        
		$model = MerchantSignup::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $model,
            'pagination' => [
                'pageSize' => $this->_pageSize,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionReview($id)
    {
        $model_merchant_signup = $this->findModel($id);

        $model_company = new Company();
        $model_company->com_name = 'Yoolan';

        if ($model_merchant_signup->load(Yii::$app->request->post()) && $post_data_company) {
            
            $post_data_company = Yii::$app->request->post();
            //TODO: isi post data company dari form merchant signup
            $model_company->load($post_data_company);
            
            if ($model_merchant_signup->validate() && $model_company->validate()) {
                $transaction = Yii::$app->db->beginTransaction();

                try {

                    if ($model_company->save()) {
                        return $this->redirect(['index']);
                    } else {
                        $transaction->rollback();
                        $this->setMessage('save','error', 'Something wrong while submit review. Please try again!');
                        return $this->redirect(['index']);
                    }

                } catch (Exception $e) {
                    $transaction->rollback();
                    throw $e;
                }
                
            } else {
                //error validation
                $this->setMessage('save','error', 'Something wrong while submit review. Please try again!');
                return $this->redirect(['index']);
            }
        } else {
            return $this->render('review', [
                'model_merchant_signup' => $model_merchant_signup,
                'model_company' => $model_company,
            ]);
        }


        if ($model_merchant_signup->load(Yii::$app->request->post()) && $model_merchant_signup->save()) {
            return $this->redirect(['view', 'id' => $model_merchant_signup->id]);
        } else {
            return $this->render('review', [
                'model_merchant_signup' => $model_merchant_signup,
                'model_company' => $model_company,
            ]);
        }


        /*if ($model_company->load(Yii::$app->request->post()) && $model_company->save()) {
            return $this->redirect(['view', 'id' => $model_company->id]);
        } else {
            return $this->render('create', [
                'model_company' => $model_company,
            ]);
        }*/
    }

    public function actionTaglist() {
        if (\Yii::$app->request->isAjax) {
            $response = [];
            Yii::$app->response->format = Response::FORMAT_JSON;
            $tag_id = \Yii::$app->request->post('tag_id');
            $category = \Yii::$app->request->post('category');
            $response = Tag::find()->getCategoryTagList($category);
            return $response;
        }
    }

    public function actionSettag() {
        if (\Yii::$app->request->isAjax) {
            $response = ['success' => 0];
            Yii::$app->response->format = Response::FORMAT_JSON;
            $tag_id = \Yii::$app->request->post('tag_id');
            if (!empty($tag_id)) {
                $value = Tag::findOne($tag_id)->tag_name;
                $response = ['success' => 1, 'data' => $tag_id, 'value' => $value];
            } else {
                $response = ['success' => 0];
            }

            return $response;
        }
    }

    public function actionCityList($q = null)
    {

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $query = "
            SELECT a.cit_id, a.cit_name, b.reg_id, b.reg_name, c.cny_id, c.cny_name
            FROM tbl_city a, tbl_region b, tbl_country c
            WHERE a.cit_region_id = b.reg_id
                AND b.reg_country_id = c.cny_id
                AND a.cit_name LIKE '%" . $q . "%'
            ORDER BY a.cit_name
            LIMIT 10";
        $connection = Yii::$app->db;
        $query = $connection->createCommand($query)->queryAll();
        $return = [];
        foreach ($query as $row) {
            $return[]['value'] = $row['cit_name'] . ', ' . $row['reg_name'] . ', ' . $row['cny_name'];
        }

        //the output will automaticaly convert to JSON
        return $return;
    }

    protected function findModel($id)
    {
        if (($model = MerchantSignup::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
