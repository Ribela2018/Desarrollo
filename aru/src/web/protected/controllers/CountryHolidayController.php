<?php

class CountryHolidayController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
    
    
    
     
   public function actionHolidayDate()
   {
       $consulta = "SELECT ch.id, ch.id_holiday, ch.start_date, ch.cod_country, ch.end_date, h.name, h.description, h.work FROM country_holiday ch "
                . " INNER JOIN holiday h ON ch.id_holiday=h.id ";
        $model = CountryHoliday::model()->findAllBySql($consulta);
        $year = $_GET['HolidayYear'];
        $cont = 0;
        if($model != null)
        {
            foreach($model as $key => $value)
            {
                $color= self::SetColorHoliday($value->cod_country);
//                
                $porciones = explode("-", $value->start_date);
                $porcionesFechaFin = explode("-", $value->end_date);
                $datos[$cont] = array(
                    'id' => $value->id,
                    'title' => $value->name,
                    'start' => $year . "-" . $porciones[1] . "-" . $porciones[2],
                    'end' => $year . "-" . $porcionesFechaFin[1] . "-" . $porcionesFechaFin[2],
                    'allDay' => false,
                    'backgroundColor' => $color
                );
                $cont++;
            }
        }
        else
        {
            $datos[0] = NULL;
        }
        echo json_encode($datos);
    }
   
   
   public function SetColorHoliday($codeCountry)
   {
       switch($codeCountry)
        {
            case 'VEN':
                return "#89c4f4";
                break;
            case 'ESP':
                return "#d84a38";
                break;
            case 'COL':
                return "#ffb848";
                break;
            case 'PER':
                return "#428bca";
                break;
            case 'CHL':
                return "#bf55ec";
                break;
            case 'USA':
                return "#35aa47";
                break;
        }
    }
}