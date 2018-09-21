<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
        
        protected function beforeAction($action) {
            $sql = "SELECT s.* FROM sys_session s WHERE s.data->>'username'= '".Yii::app()->user->name."' AND s.data->>'status'='true'";

            if(!Yii::app()->user->isGuest){

                $session = SysSession::model()->findBySql($sql);
                if($session!=null){
                    return parent::beforeAction($action);
                }else{
                    if(Yii::app()->user->getState('action')=='/site/logout'){
                        return parent::beforeAction($action);
                    }
                    Yii::app()->user->setState('action','/site/logout');
                    return $this->redirect('/site/logout');           
                }
                
            }else{

                    return parent::beforeAction($action);                
            }
            
    }
}