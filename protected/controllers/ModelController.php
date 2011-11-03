<?php
class ModelController extends Controller
{
    public function actionIndex()
    {
        $modelList = Models::model()->findAll();
        $this->render('index', array('modelList'=>$modelList));
    }

    public function actionShow($id = null)
    {
        if ($id === null)
        {
            $this->error("参数有误");
            $this->redirect($this->createUrl("site/index"));
        }

        $model = Models::model()->findByPk($id);
        $this->render("show", array("model"=>$model));
    }

    public function actionEdit($id = null)
    {
        $model = new Models;
        if ($id !== null)
            $model = $model->findByPk($id);

        if (isset($_POST['Form']))
        {
            $id = $_POST['Form']['id'];
            if (!empty($id))
            {
                $model = $model->findByPk($id);
                $message = "修改成功";
            }
            else
                $message = "添加成功";

            $model->attributes = $_POST['Form'];
            $uploadFile = CUploadedFile::getInstanceByName('head_img');
            if ($uploadFile === null && $model->head_img)
            {
                $this->error("头像不能为空");
                $this->redirect($this->createUrl(''));
            }
            else
            {
                $path = UploadFile::upload($uploadFile);
                $model->head_img = $path;
            }

            $uploadFile = CUploadedFile::getInstanceByName('picture');
            if ($uploadFile === null && $model->picture)
            {
                $this->error("图片不能为空");
                $this->redirect($this->createUrl(''));
            }
            else
            {
                $path = UploadFile::upload($uploadFile);
                $model->picture = $path;
            }

            $model->area_id = 11;
            $model->password = '123';
            $model->sign_up = 0;
            $model->price_markup = 0;
            if ($model->save())
            {
                $this->success($message);
                $this->redirect($this->createUrl(''));
            }
            else
            {
                $this->error(Dumper::dumpString($model->getErrors()));
                $this->redirect($this->createUrl(''));
            }
        }
        $area = Area::model()->findAllByAttributes(array('parent_id'=>0));

        $this->render("edit", array("model"=>$model, 'areaList'=>$area));
    }

    public function actionDel($id = null)
    {
        if ($id === null)
        {
            $this->error('参数错误');
            $this->redirect($this->createUrl('model/index'));
        }
        $sql = "DELETE FROM {{models}} WHERE id = :id";
        $command = Yii::app()->db->createCommand($sql);
        $command->execute(array(':id'=>$id));
        $this->success('删除成功');
        $this->redirect($this->createUrl('model/index'));
    }

}