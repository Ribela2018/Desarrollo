<?php
/** 
 * Esta clase solo verifica que este logueado
 */
class UserLoginFilter extends CFilter  
{  
    public function preFilter($filterChain)  
    {
    	$request=Yii::app()->getRequest();
        
    	if(!Yii::app()->user->id)
    	{
    		$request->redirect('/');
    		return false;
    	}
    	else
    	{
    		return true;
    	}
    } 

    public function postFilter($filterChain)  
    {  
        // Se aplica después de ejecutarse la acción.  
    }  
}