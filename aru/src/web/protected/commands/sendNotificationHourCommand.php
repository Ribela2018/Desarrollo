<?php
/**
* 
*/
class sendNotificationHourCommand extends CConsoleCommand
{
	public function run($args)
	{
            Yii::app()->sendnotificationhour->run();
        }
}
?>