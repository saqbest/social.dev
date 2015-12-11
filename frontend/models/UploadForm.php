<?php
namespace app\models;
use Yii;

use yii\base\Model;
use yii\web\UploadedFile;
use frontend\models\Page;

class UploadForm extends Model
{
    /**
     * @var UploadedFile[]
     */
    public $imageFiles;

    public function rules()
    {
        return [
            [['imageFiles'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg', 'maxFiles' => 4,'maxSize' => 1024 * 1024 * 2],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            foreach ($this->imageFiles as $file) {
                $file->saveAs('uploads/' . $file->baseName .date('Yhis') .'.' . $file->extension);

            }
            $model2 = new Page();
            $model2->pic_name = 'uploads/' . $file->baseName .date('Yhis') .'.' . $file->extension;
            $model2->user_id=Yii::$app->user->identity->id;
            $model2->up_date=date('Y-m-d');
            $model2->status=0;
            $model2->insert();
           return true;
        } else {
            return false;
        }
    }
}

?>