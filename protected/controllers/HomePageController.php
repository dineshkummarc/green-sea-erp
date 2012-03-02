<?php

class HomePageController extends Controller
{
	public function actionIndex()
	{
	    $userInfo = User::model()->with('ReceiveAddress')->findByAttributes(array('id'=>Yii::app()->user->id),
	            array("select"=>'t.name'));
		$this->render('index', array('info'=>$userInfo));
	}

}