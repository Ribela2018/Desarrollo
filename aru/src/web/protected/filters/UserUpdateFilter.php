<?php
/** 
 * Esta clase solo filtra el renderizado de vistas
 */
class UserUpdateFilter extends CFilter  
{  
    public function preFilter($filterChain)  
    {
    	$request=Yii::app()->getRequest();
        $statusUser = Users::model()->findByPk(Yii::app()->user->id)->id_status;

        if( $statusUser == 3)
        {
            $request->redirect('/Employee/firstView');
            return false;
        }
        elseif($statusUser == 4)
        {
            $request->redirect('/Employee/UpdatePhone');
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