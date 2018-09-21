<?php
/**
* 
*/
class sendEmployeeHappyBirthdayCommand extends CConsoleCommand
{
	public function run($args)
	{
            Yii::app()->sendemployeehappybirthday->run();
    }
}
?>