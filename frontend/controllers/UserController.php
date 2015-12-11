<?php

namespace frontend\controllers;

use Yii;
use app\models\user;
use app\models\userSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\FriendList;
/**
 * UserController implements the CRUD actions for user model.
 */
class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all user models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new userSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSendrequest(){
        $user_id=Yii::$app->user->identity->id;
        $data=Yii::$app->request->post();
        $model=new FriendList();
        if(!empty($user_id)&&!empty($data['req_id'])){
            $model->from=$user_id;
            $model->to=$data['req_id'];
            $model->save();
        }

        if(!empty($data['req_id'])){
            $res = $model::find()->where(['from'=>$user_id,'to' => $data['req_id'], 'status' => 0])->asArray()->all();
            return json_encode($res);
        }
        else{
            $res = $model::find()->where(['from'=>$user_id, 'status' => 0])->asArray()->all();
            return json_encode($res);
        }

    }
    public function actionCheckrequest(){
        $user_id=Yii::$app->user->identity->id;
        $model= new FriendList();
        $res = $model::find()->where(['to'=>$user_id, 'status' => 0])->asArray()->all();
        foreach($res as $val){
            $res=\frontend\models\Page::find()->select('pic_name')->where(['user_id' => $val['from'], 'status' => 1])->asArray()->one();
            $pic_url = (empty($res)) ? 'uploads/def.jpg' : $res['pic_name'];

            $res2=\app\models\User::find()->select('id,first_name,last_name')->where(['id' => $val['from']])->asArray()->one();
            $fin[]=array('user_id'=>$res2['id'],'first_name'=>$res2['first_name'],'last_name'=>$res2['last_name'],'pic'=>$pic_url);
        }
        //print_r($fin) ;

       // print_r($pic);
        return json_encode($fin);
    }

    public function actionAddfr(){
        $user_id=Yii::$app->user->identity->id;
        $data=Yii::$app->request->post();
        if(!empty($data['add_fr_id'])){
            FriendList::updateAll(['status' => 1], '`from`=' . $data['add_fr_id'] . ' and `to`=' . $user_id . '');
            return true;
        }

    }
    public function actionTest(){
        $sql = 'SELECT * FROM `friend_list` WHERE  `from`=' . Yii::$app->user->identity->id . ' and `to`=11 or   `from`=11 and `to`='.Yii::$app->user->identity->id.'';
        $res2 = \app\models\FriendList::findBySql($sql)->asArray()->all();

        print_r($res2);

    }
    public function actionDelrequest(){
        $user_id=Yii::$app->user->identity->id;
        $data=Yii::$app->request->post();
        if(!empty($data['del_fr_id'])) {
            \Yii::$app->db->createCommand('DELETE FROM `friend_list` WHERE `from`=:del_fr_id and `to`=:user_id')
            ->bindParam(':del_fr_id', $data['del_fr_id'])
            ->bindParam(':user_id', $user_id)
            ->execute();
            return true;
        }
    }
    /**
     * Finds the user model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return user the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = user::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
