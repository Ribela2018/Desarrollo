<?php
/**
* 
*/
class sendNotificationHolidayCommand extends CConsoleCommand
{
	public function run($args)
	{
            Yii::app()->sendnotificationholiday->run();
    }
}
?>