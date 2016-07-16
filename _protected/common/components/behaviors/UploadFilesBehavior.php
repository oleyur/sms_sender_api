<?php
namespace common\components\behaviors;


use Gregwar\Image\Image;
use Yii;
//use yii\imagine\Image;
use yii\web\UploadedFile;
use yii\db\ActiveRecord;

/**
 * Behavior for simplifies files upload
 *
 * Need to install dependence  php composer.phar require --prefer-dist yiisoft/yii2-imagine "*"

 *
 * For example:
 *
 * Create in table field 'images_json' with type 'text'
 *
 * ```php
 *
 * public $images;   // create public attribute
 *
 * // add image rules
 * public function rules()
 * {
 *      return [
 *          ['images', 'image',
 *              'extensions'    => 'jpg, jpeg, gif, png',
 *              'skipOnEmpty'   => $this->isNewRecord?false:true,
 *              'maxFiles'      => 0,
 *          ],
 *      ];
 * }
 *
 *
 *
 * public function behaviors()
 * {
 *      return [
 *          'imageUpload' => [
 *              'class'         => UploadFilesBehavior::className(),
 *              'attributeName' => 'images',
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
 *
 *      <?= $form->field($model, 'images[]')->fileInput([
 *           'accept' => 'image/*',
 *           'multiple'  => true
 *      ]) ?>
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
 *  $model->getFilesUrl("images")             // original files url
 *  $model->getFilesUrl("images", "small")    // small thumbnail files url
 *
 *  $model->getFilesPath("images")            // small thumbnail files path
 *  $model->getFilesPath("images", "small")   // small thumbnail files path
 *
 *  $model->deleteKeyFile($key_id);           // remove one of images
 *
 *  If you have several file attributes in one model, then need to call functions directly through behavior
 *  $model->getBehavior('myBehaviorName')->getFileUrl("image");
 *
 * ```
 *
 */
class UploadFilesBehavior extends UploadFileBehavior
{

    /**
     * @param string $attributeName
     * @param string $format format of size of image
     * @return string file path
     */
    public function getFilesPath($attributeName, $format = ""){

        $this->attributeName = $attributeName;

        $fileName = $this->generateFileName();

        if(!empty($format)){
            $format = $this->attributeSeparator.$format;
        }

        $files = $this->loadField();

        foreach ((array)$files as $key => $extension) {
            $filePath = $this->savePath . DIRECTORY_SEPARATOR . $fileName.$this->attributeSeparator.$key.$format.".".$extension;

            if(file_exists($filePath)){
                $result[$key] = $filePath;
            }
        }

        return $result;
    }

    /**
     * @param string $attributeName
     * @param string $format format of size of image
     * @return string file url
     */
    public function getFilesUrl($attributeName, $format = ""){
        $files = $this->getFilesPath($attributeName, $format);

        foreach ((array)$files as $key => $path) {
            $url = str_replace($this->savePath, $this->url, $path);

            if($this->baseUrl !== null){
                $url = $this->baseUrl.$url;
            }else{
                $url = Yii::$app->getUrlManager()->baseUrl.$url;
            }

            $results[$key] = $url;
        }

        return $results;
    }

    /**
     * remove files
     *
     * @param integer $key number of file
     */
    public function deleteKeyFile($key){
        $files = $this->loadField();

        if(isset($files[$key])){
            $this->deleteFiles($key);

            unset($files[$key]);
        }

        $this->saveField($files);
    }


    /**
     * This method is invoked before validation starts.
     */
    public function beforeValidate()
    {
        /** @var ActiveRecord $model */
        $model = $this->owner;
        $file = UploadedFile::getInstances($model, $this->attributeName);
        if($file === NULL){
            // check in with getInstanceByName, will works when used rest api
            $file = UploadedFile::getInstancesByName($this->attributeName);
        }

        if (is_array($file)) {
            $model->{$this->attributeName} = $file;
        }
    }

    /**
     * This method is invoked before delete.
     */
    public function beforeDelete()
    {
        $this->deleteFiles();
    }


    /**
     *  save file
     */
    public function loadFile()
    {
        /** @var ActiveRecord $model */
        $model = $this->owner;

        /** @var UploadedFile $file */
        $files = $model->{$this->attributeName};


        if(!is_array($files)){
            return;
        }

        if (!is_dir($this->savePath)) {
            mkdir($this->savePath, 0755, true);
        }


        $databaseFiles = $this->loadField();

        if(!empty($databaseFiles)){
            $max_key = max(array_keys($databaseFiles));
        }else{
            $max_key = 0;
        }

        $i = $max_key;
        foreach ((array)$files as $file) {
            if (!$file instanceof UploadedFile) {
                continue;
            }

            $i++;
            $databaseFiles[$i] = $file->extension;

            $filePath = $this->generateKeyFilePath($file, $i);

            $file->saveAs($filePath);

            $this->generateKeyThumbnails($file, $i, $filePath);
        }

        $this->saveField($databaseFiles);
    }


    /**
     * Convert images json to array
     * @return array|mixed
     */
    public function loadField(){

        /** @var ActiveRecord $model */
        $model = $this->owner;
        $images = $model->{$this->attributeName."_json"};
        if(empty($images)){
            return [];
        }else{
            return json_decode($images, true);
        }
    }

    /**
     * @param array $databaseFiles
     */
    public function saveField($databaseFiles){
        /** @var ActiveRecord $model */
        $model = $this->owner;

        /** @var ActiveRecord $info */
        $info = $model::findOne($model->id);

        if(empty($databaseFiles)){
            $jsonArray = '';
        }else{
            $jsonArray = json_encode($databaseFiles);
        }

        // save without events calls
        $attributeName = $this->attributeName."_json";
        $info->updateAttributes([
            $attributeName => $jsonArray,
        ]);
    }


    /**
     * @param $file
     * @param $key
     * @param $originalPath
     */
    protected function generateKeyThumbnails($file, $key, $originalPath){

        if(!empty($this->thumbnails)){
            foreach ((array)$this->thumbnails as $format => $info) {
                $filePath = $this->generateKeyFilePath($file, $key, $format);
//                Image::thumbnail($originalPath, $info[0],$info[1], ManipulatorInterface::THUMBNAIL_INSET)->save($filePath);
//                Image::watermark($filePath, 'images/watermark_'.$format.'.png')->save($filePath);

                //resize
                Image::open($originalPath)->cropResize($info[0],$info[1])->save($filePath);

                //watermark
//                list($width, $height) = getimagesize($filePath);
//                $x = $width/2;
//                $y = $height - $height/20;
//                $size = $height/20;
//                Image::open($filePath)->write('themes/advanced/fonts/OpenSans.ttf', Yii::$app->name, $x, $y, $size, 0, 'white', 'center')->save($filePath);

            }
        }
    }

    /**
     * @param UploadedFile $file
     * @param string       $key
     * @param string       $format format of size of image
     *
     * @return string save path
     */
    protected function generateKeyFilePath(UploadedFile $file, $key, $format = ""){
        $name = $this->generateFileName();
        if(!empty($format)){
            $format = $this->attributeSeparator.$format;
        }

        return $this->savePath . DIRECTORY_SEPARATOR . $name.$this->attributeSeparator.$key.$format.".".$file->getExtension();
    }


    /**
     * remove files
     *
     * @param integer $key
     */
    protected function deleteFiles($key = null)
    {
        // delete original files
        $filesPath = $this->getFilesPath($this->attributeName);

        if($key !== null){
            $filesPath = [$key => $filesPath[$key]];
        }

        $this->_removeFiles($filesPath);

        foreach ((array)$this->thumbnails as $name => $info) {
            $filesPath = $this->getFilesPath($this->attributeName, $name);
            if($key !== null){
                $filesPath = [$key => $filesPath[$key]];
            }
            $this->_removeFiles($filesPath);
        }
    }

    /**
     * @param array $files
     */
    private function _removeFiles($files){
        foreach ((array)$files as $path) {
            if (is_file($path)) {
                unlink($path);
            }
        }
    }
}
