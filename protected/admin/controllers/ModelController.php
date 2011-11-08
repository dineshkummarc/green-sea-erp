<?php
class ModelController extends Controller
{
    public function actionIndex(array $params = array(), $name = '', $numPerPage = 20, $pageNum = 1)
    {
    	$model = new Models();
    	$criteria = new CDbCriteria;
    	if (!empty($params['name']))
            $criteria->addCondition("t.nick_name like '%{$params['name']}%'");

        $count = $model->count($criteria);
        $pages = new CPagination($count);
        $pages->currentPage = $pageNum - 1;
        $pages->pageSize = $numPerPage;
        $pages->applyLimit($criteria);

        $modelList = Models::model()->findAll($criteria);
        $this->render('index', array('modelList'=>$modelList, 'pages'=>$pages));
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

            // 封面图片上传
            $uploadFile = CUploadedFile::getInstanceByName('head_img');
            if ($uploadFile === null && empty($model->head_img))
                $this->error("请上传头像");
            else if ($uploadFile !== null)
            {
                if (!$model->upload($uploadFile, 'head_img'))
                    $this->error($model->getError('head_img'));
            }

            // 封面图片上传
            $uploadFile = CUploadedFile::getInstanceByName('picture');
            if ($uploadFile === null && empty($model->picture))
                $this->error("请上传封面图片");
            else if ($uploadFile !== null)
            {
                if (!$model->upload($uploadFile, 'picture'))
                    $this->error($model->getError('picture'));
            }

            $model->password = '123';
            $model->sign_up = 0;
            $model->price_markup = 0;
            if ($model->save())
            {
                $this->success($message,array('navTabId'=>'model-index') );
                $this->redirect($this->createUrl('index'));
            }
            else
            {
                $this->error(Dumper::dumpString($model->getErrors()));
                $this->redirect($this->createUrl('index'));
            }
        }
        $area = Area::model()->findAllByAttributes(array('parent_id'=>0));
        $this->render('edit', array('model'=>$model, 'areaList'=>$area, 'multiple'=>true));
    }

    public function actionDel(array $id = array())
    {
        if (empty($id))
            $this->error('参数传递错误！');

        $sqlIn = implode(',', $id);

        $sql = "DELETE FROM {{models}} WHERE id in ($sqlIn)";
        $command = Yii::app()->db->createCommand($sql);
        $command->execute();
        $this->success('删除成功',array('navTabId'=>'model-index'));
    }

}