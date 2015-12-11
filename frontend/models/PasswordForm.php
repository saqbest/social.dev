<?php
namespace app\models;

use app\models\User;
use Yii;
use yii\base\Model;

class PasswordForm extends Model{
    public $oldpass;
    public $newpass;
    public $repeatnewpass;

    public function rules(){
        return [
            [['oldpass','newpass','repeatnewpass'],'required'],
            ['oldpass','findPasswords'],
            ['repeatnewpass','compare','compareAttribute'=>'newpass'],
        ];
    }

    public function findPasswords($attribute, $params){
        $user = User::find()->where([
            'username'=>Yii::$app->user->identity->username
        ])->one();
        $password = $user->password_hash;
        $k=Yii::$app->security->validatePassword($this->oldpass,$password);
        if(!$k)
            $this->addError($attribute,'Old password is incorrect');
    }

    public function attributeLabels(){
        return [
            'oldpass'=>'Old Password',
            'newpass'=>'New Password',
            'repeatnewpass'=>'Repeat New Password',
        ];
    }
}
?>