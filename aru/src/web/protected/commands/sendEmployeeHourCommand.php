<?php
/**
* 
*/
class sendEmployeeHourCommand extends CConsoleCommand
{
	public function run($args)
	{
            Yii::app()->sendemployeehour->run();
        }
}
?>