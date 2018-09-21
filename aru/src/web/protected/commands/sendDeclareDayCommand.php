<?php
/**
* 
*/
class sendDeclareDayCommand extends CConsoleCommand
{
	public function run($args)
	{
            Yii::app()->senddeclareday->run();
        }
}
?>