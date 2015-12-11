<?php
namespace frontend\controllers;

use Yii;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\UploadForm;
use yii\web\UploadedFile;
use frontend\models\Page;
use app\models\Message;
use app\models\TimeLine;
use app\models\FriendList;

/**
 * Site controller
 */
class SiteController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup', 'changepic', 'message', 'addmessage'],
                        'allow' => true,
                        'roles' => ['?'],
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

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->render('index');
        } else {
            $pic = \frontend\models\Page::find()->where(['user_id' => Yii::$app->user->identity->id, 'status' => 1])->all();
            $info_user = \app\models\User::find()->where(['id' => Yii::$app->user->identity->id])->all();
            //$user_list = \app\models\User::find()->all();
            $edit= new TimeLine();
            $pic_avatar = \frontend\models\Page::find()->where(['user_id' => Yii::$app->user->identity->id])->all();

            $user_id=Yii::$app->user->identity->id;
            $model= new FriendList();
            $sql = 'SELECT * FROM `friend_list` WHERE  `from`=' . $user_id . ' and `status`=1 or   `to`=' . $user_id . ' and `status`=1';
            $res = $model->findBySql($sql)->asArray()->all();

            //$res = $model::find()->where(['to'=>$user_id, 'status' => 0])->asArray()->all();
            foreach($res as $val){
                if($val['from']!=Yii::$app->user->identity->id) {
                    $res = \frontend\models\Page::find()->select('pic_name')->where(['user_id' => $val['from'], 'status' => 1])->asArray()->one();
                    $res2 = \app\models\User::find()->select('id,first_name,last_name')->where(['id' => $val['from']])->asArray()->one();
                    $pic_url = (empty($res)) ? 'uploads/def.jpg' : $res['pic_name'];
                    $user_list[] = array('user_id' => $res2['id'], 'first_name' => $res2['first_name'], 'last_name' => $res2['last_name'], 'pic' => $pic_url);
                }
                else{
                    $res = \frontend\models\Page::find()->select('pic_name')->where(['user_id' => $val['to'], 'status' => 1])->asArray()->one();
                    $res2 = \app\models\User::find()->select('id,first_name,last_name')->where(['id' => $val['to']])->asArray()->one();
                    $pic_url = (empty($res)) ? 'uploads/def.jpg' : $res['pic_name'];
                    $user_list[] = array('user_id' => $res2['id'], 'first_name' => $res2['first_name'], 'last_name' => $res2['last_name'], 'pic' => $pic_url);
                }
            }

            return $this->render('page', ['pic' => $pic, 'info_user' => $info_user,
                'user_list' => $user_list, 'pic_avatar' => $pic_avatar,'edit'=>$edit]);

        }

    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionMsglist()
    {
        $res = \app\models\User::find()->all();
        foreach($res as $val){
            $res=\frontend\models\Page::find()->select('pic_name')->where(['user_id' => $val->id, 'status' => 1])->asArray()->one();
            $pic_url = (empty($res)) ? 'uploads/def.jpg' : $res['pic_name'];

           $user_list[]=array('user_id'=>$val->id,'first_name'=>$val->first_name,'last_name'=>$val->last_name,'pic'=>$pic_url);
        }
        return $this->render('msglist', ['user_list' => $user_list,]);
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionUpload()
    {
        $model = new UploadForm();
        $model2 = new Page();
        if (Yii::$app->request->isPost) {

            $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');
            if ($model->upload()) {
                //file is uploaded successfully

                print_r(Yii::$app->request->post(''));

            }
        }
        $pic = $model2->find()->where(['user_id' => Yii::$app->user->identity->id])->all();
        return $this->render('upload', ['model' => $model,
            'pic' => $pic
        ]);
    }

    public function actionSavepic($id)
    {
        \Yii::$app->db->createCommand("UPDATE `page` SET `status`=0 WHERE `user_id`=:user_id")
            ->bindValue(':user_id', Yii::$app->user->identity->id)
            ->execute();
        \Yii::$app->db->createCommand("UPDATE `page` SET `status`=1 WHERE `id`=:id")
            ->bindValue(':id', $id)
            ->execute();

    }

    public function actionDeletepic($id)
    {
        \Yii::$app->db->createCommand('DELETE FROM `page` WHERE `id`=:id')
            ->bindParam(':id', $id)
            ->execute();
    }

    public function actionMessage($id)
    {
        $usr_id = Yii::$app->user->identity->id;
        $model = new Message();
        $sql = 'SELECT * FROM `message` WHERE `from`=' . $usr_id . ' and `to`=' . $id . ' or `to`=' . $usr_id . ' and `from`=' . $id . '';
        $msg = $model->findBySql($sql)->asArray()->all();
        Message::updateAll(['status' => 1], '`from`=' . $id . ' and `to`=' . $usr_id . '');

        return json_encode($msg);

    }

    public function actionGetpic($id)
    {
        $pic = \frontend\models\Page::find()->where(['user_id' => $id, 'status' => 1])->asArray()->one();
        return json_encode($pic);

    }

    public function actionAddmessage()
    {
        $model = new Message();
        $data = Yii::$app->request->post();
        $model->from = $data['usid'];
        $model->to = $data['frid'];
        $model->message = $data['msg'];
        $model->save();
        if (empty($data['lastmsg'])) {
            $sql = 'SELECT * FROM `message` WHERE  `from`=' . $data['usid'] . ' and `to`=' . $data['frid'] . ' or   `to`=' . $data['usid'] . ' and `from`=' . $data['frid'] . '';
            Message::updateAll(['status' => 1], '`from`=' . $data['frid'] . ' and `to`=' . $data['usid'] . '');

        } else {
            $sql = 'SELECT * FROM `message` WHERE `message_id`>' . $data['lastmsg'] . ' and  `from`=' . $data['usid'] . ' and `to`=' . $data['frid'] . ' or `message_id`>' . $data['lastmsg'] . ' and  `to`=' . $data['usid'] . ' and `from`=' . $data['frid'] . '';
            Message::updateAll(['status' => 1], '`from`=' . $data['frid'] . ' and `to`=' . $data['usid'] . '');

        }
        $msg = $model->findBySql($sql)->asArray()->all();
        return json_encode($msg);

    }

    public function actionChekmsg()
    {
        $usr_id = Yii::$app->user->identity->id;

        $model = new Message();
        $data = Yii::$app->request->post();
        if (empty($data['lastmsg'])) {
            $sql = 'SELECT * FROM `message` WHERE  `from`=' . $usr_id . ' and `to`=' . $data['frid'] . ' or   `to`=' . $usr_id . ' and `from`=' . $data['frid'] . '';
            Message::updateAll(['status' => 1], '`from`=' . $data['frid'] . ' and `to`=' . $usr_id . '');

        } else {
            $sql = 'SELECT * FROM `message` WHERE `message_id`>' . $data['lastmsg'] . ' and  `from`=' . $usr_id . ' and `to`=' . $data['frid'] . ' or `message_id`>' . $data['lastmsg'] . ' and  `to`=' . $usr_id . ' and `from`=' . $data['frid'] . '';
            Message::updateAll(['status' => 1], '`from`=' . $data['frid'] . ' and `to`=' . $usr_id . '');


        }
        $msg = $model->findBySql($sql)->asArray()->all();
        return json_encode($msg);
    }

    public function actionNewmsg()
    {
        $user_id=Yii::$app->user->identity->id;
        //$data = Yii::$app->request->post();
        //$res = Message::find()->select('status')->where(['`to`' => $data['usid'], '`status`' => 0, '`from`' => $data['frid']])->asArray()->all();
        $res = Message::find()->select('status')->where(['`to`' => $user_id, '`status`' => 0])->asArray()->all();
        $res = count($res);

        echo $res;

    }
    public function actionEditor(){
        $data = Yii::$app->request->post();
        $model = new TimeLine();
        $user_id = Yii::$app->user->identity->id;
        if(!empty($data['title']) && !empty($data['content'])){
            $model->user_id = $user_id;
            $model->post_title = $data['title'];
            $model->post = $data['content'];
            $model->posted_time=date("j F Y H:m");
            $model->save();}

        if( !empty($data['last_post'])) {

            $sql = 'SELECT * FROM `time_line` WHERE `post_id`>' . $data['last_post'] . ' and  `user_id`=' . $user_id .'';
            $res = $model->findBySql($sql)->asArray()->all();

            return json_encode($res);
        }
        else{
            $res = $model->find()->where(['`user_id`' => $user_id])->asArray()->all();
            return json_encode($res);
        }
    }
    public function actionNewmsglist()
    {
        $user_id=Yii::$app->user->identity->id;
        $res = Message::find()->select('from')->where(['`to`' => $user_id, '`status`' => 0, ])->asArray()->all();
        //$res = count($res);
        //print_r($res);
        return json_encode($res);

    }
    public function actionProfile($id){
        $pic_avatar = \frontend\models\Page::find()->where(['user_id' => $id, 'status' => 1])->all();
        $info_user = \app\models\User::find()->where(['id' => $id])->all();
        //$user_list = \app\models\User::find()->all();
        $edit= new TimeLine();
        $pic = \frontend\models\Page::find()->where(['user_id' => $id])->all();

        $model= new FriendList();
        $sql = 'SELECT * FROM `friend_list` WHERE  `from`=' . $id . ' and `status`=1 or   `to`=' . $id . ' and `status`=1';
        $res = $model->findBySql($sql)->asArray()->all();

        //$res = $model::find()->where(['to'=>$user_id, 'status' => 0])->asArray()->all();
        foreach($res as $val){
            $res=\frontend\models\Page::find()->select('pic_name')->where(['user_id' => $val['from'], 'status' => 1])->asArray()->one();
            $res2=\app\models\User::find()->select('id,first_name,last_name')->where(['id' => $val['from']])->asArray()->one();
            $user_list[]=array('user_id'=>$res2['id'],'first_name'=>$res2['first_name'],'last_name'=>$res2['last_name'],'pic'=>$res['pic_name'],'id'=>$id);

        }

        return $this->render('profile', ['pic' => $pic, 'info_user' => $info_user,
            'user_list' => $user_list, 'pic_avatar' => $pic_avatar,'edit'=>$edit,'id'=>$id]);

    }
    public function actionProfiletimeline(){
        $data = Yii::$app->request->post();
        $model = new TimeLine();
        if(!empty($data['id'])){
            $res = $model->find()->where(['`user_id`' => $data['id']])->asArray()->all();
            return json_encode($res);
        }
    }
    public function actionNewnot()
    {
        $user_id=Yii::$app->user->identity->id;
        //$data = Yii::$app->request->post();
        //$res = Message::find()->select('status')->where(['`to`' => $data['usid'], '`status`' => 0, '`from`' => $data['frid']])->asArray()->all();
        $res = FriendList::find()->select('status')->where(['`to`' => $user_id, '`status`' => 0])->asArray()->all();
        $res = count($res);

        echo $res;

    }
}
