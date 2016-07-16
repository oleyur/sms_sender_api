<?php
namespace common\components\behaviors;

use Yii;
use yii\base\Behavior;
//use yii\imagine\Image;
use Gregwar\Image\Image;
use yii\validators\FileValidator;
use yii\validators\ImageValidator;
use yii\web\UploadedFile;
use yii\db\ActiveRecord;

/**
 * Behavior for simplifies file upload
 *
 * Need to install dependence  php composer.phar require --prefer-dist yiisoft/yii2-imagine "*"

 *
 * For example:
 *
 * ```php
 *
 *
 * public $image;   // create public attribute
 *
 *
 * // add image rules
 * public function rules()
 * {
 *      return [
 *          ['image', 'image', 'extensions' => 'jpg, jpeg, gif, png', 'skipOnEmpty' => $this->isNewRecord?false:true],
 *      ];
 * }
 *
 *
 *
 * public function behaviors()
 * {
 *      return [
 *          'imageUpload' => [
 *              'class'         => UploadFileBehavior::className(),
 *              'attributeName' => 'image',
 *              'savePath'      => "@root/uploads",
 *              'url'           => "/uploads",
 *              'baseUrl'       => Yii::$app == "app-frontend"?Yii::$app->urlManager->baseUrl:"",
 *              'thumbnails' => [
 *                    "small"  => [100, 100],
 *                    "medium" => [300, 300],
 *               ]
 *          ],
 *      ];
 * }
 *
 *
 *
 * //In template
 * <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
 *
 *      <?php $image_path = $model->getFileUrl("image") ?>
 *      <?php echo !empty($image_path)?Html::img($image_path):""; ?>
 *
 *      <?= $form->field($model, 'image')->fileInput(['accept' => 'image/*']) ?>
 *
 *
 *      <div class="form-group">
 *          <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success']) ?>
 *      </div>
 *
 * <?php ActiveForm::end(); ?>
 *
 *
 *
 * Examples of getting file:
 *
 *  $model->getFileUrl("image")             // original file url
 *  $model->getFileUrl("image", "small")    // small thumbnail file url
 *
 *  $model->getFilePath("image")            // small thumbnail file path
 *  $model->getFilePath("image", "small")   // small thumbnail file path
 *
 *  If you have several file attributes in one model, then need to call functions directly through behavior
 *  $model->getBehavior('myBehaviorName')->getFileUrl("image");
 *
 *
 * ```
 *
 * @author HimikLab
 */
class UploadFileBehavior extends Behavior
{
    /** @var string model file field name */
    public $attributeName = '';

    /**
     * @var string|callable path or alias to the directory in which to save files
     * or anonymous function returns directory path
     */
    public $savePath = '';

    /**
     * @var string|callable path or alias to the web directory in which to save files
     * or anonymous function returns directory path
     */
    public $url = '';

    /**
     * @var string path or alias , by default is  Yii::$app->getUrlManager()->baseUrl
     *
     */
    public $baseUrl = null;

    /**
     * @var array of thumbnails
     * or anonymous function returns directory path
     */
    public $thumbnails = [];

    /**
     * file prefix. can be used to avoid name clashes for example.
     */
    public $prefix = 'img_';


    /**
     * default separator when attributeForName is an array and must be joined.
     */
    public $attributeSeparator = '_';




    private $_extensions_cache = [];

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
            ActiveRecord::EVENT_AFTER_INSERT    => 'afterInsert',
            ActiveRecord::EVENT_AFTER_UPDATE    => 'afterUpdate',
            ActiveRecord::EVENT_BEFORE_DELETE   => 'beforeDelete',
        ];
    }



    public function init()
    {
        if ($this->savePath instanceof \Closure) {
            $this->savePath = call_user_func($this->savePath);
        }
        if ($this->url instanceof \Closure) {
            $this->url = call_user_func($this->url);
        }
        $this->savePath = Yii::getAlias($this->savePath);
        $this->url = Yii::getAlias($this->url);
    }

    /**
     * This method is invoked before validation starts.
     */
    public function beforeValidate()
    {
        /** @var ActiveRecord $model */
        $model = $this->owner;
        $file = UploadedFile::getInstance($model, $this->attributeName);
        if($file === NULL){
            // check in with getInstanceByName, will works when used rest api
            $file = UploadedFile::getInstanceByName($this->attributeName);
        }

        if ($file instanceof UploadedFile) {
            $model->{$this->attributeName} = $file;
        }
    }

    /**
     * This method is invoked after insert.
     */
    public function afterInsert()
    {
        $this->loadFile();
    }

    /**
     * This method is invoked after update.
     */
    public function afterUpdate()
    {
        $this->loadFile();
    }

    /**
     * This method is invoked before delete.
     */
    public function beforeDelete()
    {
        $this->deleteFile();
    }



    /**
     * @param string $attributeName
     * @param string $format format of size of image
     * @return string file path
     */
    public function getFilePath($attributeName, $format = ""){

        $this->attributeName = $attributeName;

        $extensions = $this->getExtensions();
        if(empty($extensions)){
            return "";
        }

        $fileName = $this->generateFileName();

        if(!empty($format)){
            $format = $this->attributeSeparator.$format;
        }

        foreach ((array)$extensions as $extension) {
            $filePath = $this->savePath . DIRECTORY_SEPARATOR . $fileName.$format.".".$extension;

            if(file_exists($filePath)){
                return $filePath;
            }
        }

        return "";
    }

    /**
     * @param string $attributeName
     * @param string $format format of size of image
     * @return string file url
     */
    public function getFileUrl($attributeName, $format = ""){
        $filePath = $this->getFilePath($attributeName, $format);

        $url = str_replace($this->savePath, $this->url, $filePath);

        if($this->baseUrl !== null){
            $url = $this->baseUrl.$url;
        }else{
            $url = Yii::$app->getUrlManager()->baseUrl.$url;
        }

        return $url;
    }



    /**
     *  save file
     */
    public function loadFile()
    {
        /** @var ActiveRecord $model */
        $model = $this->owner;

        /** @var UploadedFile $file */
        $file = $model->{$this->attributeName};

        if (!$file instanceof UploadedFile) {
            return;
        }

        $filePath = $this->generateFilePath($file->getExtension());

        // remove previous file
        $this->deleteFile();

        $file->saveAs($filePath);

        $this->generateThumbnails($file->getExtension(), $filePath);
    }

    /**
     * @param string $ext
     * @param $originalPath
     */
    public function generateThumbnails($ext, $originalPath){

        if(!empty($this->thumbnails)){
            foreach ((array)$this->thumbnails as $format => $info) {
                $filePath = $this->generateFilePath($ext, $format);
//                Image::thumbnail($originalPath, $info[0],$info[1])->save($filePath);
                Image::open($originalPath)->cropResize($info[0],$info[1])->save($filePath);
            }
        }
    }

    /**
     * remove file
     */
    public function deleteFile()
    {
        $filePath = $this->getFilePath($this->attributeName);

        if (is_file($filePath)) {
            unlink($filePath);
        }

        foreach ((array)$this->thumbnails as $name => $info) {
            $filePath = $this->getFilePath($this->attributeName, $name);
            if (is_file($filePath)) {
                unlink($filePath);
            }
        }

    }

    /**
     * @param string $ext
     * @param string $format format of size of image
     * @return string save path
     */
    public function generateFilePath($ext, $format = ""){
        $name = $this->generateFileName();
        if(!empty($format)){
            $format = $this->attributeSeparator.$format;
        }

        if (!is_dir($this->savePath)) {
            mkdir($this->savePath, 0755, true);
        }

        return $this->savePath . DIRECTORY_SEPARATOR . $name.$format.".".$ext;
    }

    /**
     * @return string  save file name
     */
    public function generateFileName()
    {
        /** @var ActiveRecord $model */
        $model = $this->owner;
        $attributeForName = $model->getTableSchema()->primaryKey;

        if (!is_array($attributeForName)) {
            $partName = $model->{$attributeForName};
        } else {
            $partName = array();
            foreach ($attributeForName as $attr)
                $partName[] = $model->{$attr};
            $partName = join($this->attributeSeparator, $partName);
        }

        $file_name = $this->prefix.$partName;

        return $file_name;
    }

    /**
     * @return array extensions
     */
    public function getExtensions(){

        // get6 from cache on class level
        if(isset($this->_extensions_cache[$this->attributeName])){
            return $this->_extensions_cache[$this->attributeName];
        }


        //$this->attributeName
        /** @var ActiveRecord $model */
        $model = $this->owner;

        // get extensions from validator rules
        $extensions = [];
        foreach ($model->getValidators() as $validator) {
            if ($validator instanceof ImageValidator || $validator instanceof FileValidator) {
                if(in_array($this->attributeName, $validator->attributes)){
                    foreach ((array)$validator->extensions as $extension) {
                        $extensions[$extension] = $extension;
                    }
                }
            }
        }

        // save to cache on class level
        $this->_extensions_cache[$this->attributeName] = $extensions;

        return $extensions;
    }
}
