<?php

namespace app\controllers;

use common\models\City;
use common\models\Country;
use common\models\Region;
use common\models\Member;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\helpers\Json;

/**
 * CityController implements the CRUD actions for City model.
 */
class CityController extends BaseController
{
	// public function behaviors()
	// {
	// 	return [
	// 		'access' => [
	//             'class' => AccessControl::className(),
	//             'rules' => [
	//                 ['allow' => true, 'roles' => ['@']],
	//             ],
	//         ],
	// 	];
	// }

	/**
	 * Lists all City models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$model = City::find()->list;
		$dataProvider = new ActiveDataProvider([
            'query' => $model,
            'pagination' => [
                'pageSize' => 20
            ],
        ]);
		return $this->render('index', [
			'dataProvider' => $dataProvider
		]);
	}

	public function actionSetting() {
		return $this->render('setting', [

		]);
	}

    public function actionList($q = null)
    {
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
        return Json::encode($return);
    }
    
	// ============== REGION ====================

	public function actionRegionlist($id) {
		$connection = Yii::$app->db;
		$query = "SELECT reg_id, reg_name FROM tbl_region WHERE reg_country_id = ".$id." ORDER BY reg_name";
		$model = $connection->createCommand($query)->queryAll();
		$html = '';
		foreach($model as $row) {
			$html .= '<option value="'.$row['reg_id'].'">'.$row['reg_name'].'</option>';
		}
		return $html;
	}

	public function actionRegionsave() {
		$model = new Region;
		$model->attributes = $_POST['Region'];
		$model->save();
		return 'saved';
	}

	// ============== COUNTRY ==================

	public function actionCountrylist() {
		$connection = Yii::$app->db;
		$query = "SELECT cny_id, cny_name FROM tbl_country ORDER BY cny_name";
		$model = $connection->createCommand($query)->queryAll();
		$html = '';
		foreach($model as $row) {
			$html .= '<option value="'.$row['cny_id'].'">'.$row['cny_name'].'</option>';
		}
		return $html;
	}

	public function actionCountrysave() {
		$model = new Country;
		$model->attributes = $_POST['Country'];
		$model->cny_name = strtoupper($model->cny_name);
		$model->cny_shortcode2 = strtoupper($model->cny_shortcode2);
		$model->cny_shortcode3 = strtoupper($model->cny_shortcode3);
		$model->cny_prefix = strtoupper($model->cny_prefix);
		$model->save();
		return 'saved';
	}

	public function actionCountry($id)
    {
        $model = (new yii\db\Query())
            ->select('reg_id AS id, reg_name AS text')
            ->from('tbl_region')
            ->where('reg_country_id = :id', [':id' => $id])
            ->all();
        $arr = [
            'id' => $id,
            'data' => $model
        ];
        return Json::encode($arr);
    }

    public function actionSelect2($search = null, $id = null)
    {
        $out = ['more' => false];
        if (!is_null($search)) {
            $query = new \yii\db\Query;
            $query->select('reg_id AS id, reg_name AS text')
                ->from('tbl_region')
                ->where('reg_name LIKE "%' . $search . '%"')
                ->limit(20);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        } else {
            $out['results'] = ['id' => 0, 'text' => 'No matching records found'];
        }
        echo json_encode($out);
    }

	/**
	 * Creates a new City model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new City;
		$model->setScenario('newcity');
		if ($model->load(Yii::$app->request->post())) {
			if($model->save()) {
				return $this->redirect(['index']);
			}
		} else {
			return $this->render('form', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Updates an existing City model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);
		$model->setScenario('newcity');
		if ($model->load(Yii::$app->request->post())) {
			var_dump($model->attributes);
			exit;
			if($model->save()) {
				return $this->redirect(['index']);
			}
		} else {
			return $this->render('form', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Deletes an existing City model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		// $this->findModel($id)->delete();
		$model = $this->findModel($id);
		if($model->save())
			return $this->redirect(['index']);
		else
			var_dump($model->getErrors());
	}

	/**
	 * Finds the City model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return City the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = City::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
