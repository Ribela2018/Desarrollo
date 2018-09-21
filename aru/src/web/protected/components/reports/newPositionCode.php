<?php

class newPositionCode extends Reports 
{
    public static function report($name,$dir) 
    {
        $objPHPExcel = new PHPExcel();
        $sheet = new newPositionCode(); 

        $objPHPExcel->
                getProperties()
                        ->setCreator("ARU")
                        ->setLastModifiedBy("ARU")
                        ->setTitle($name)
                        ->setSubject($name)
                        ->setDescription("ARU EtelixGroup Códigos CP")
                        ->setKeywords("ARU EtelixGroup Códigos CP")
                        ->setCategory($name);


        //PRIMERA HOJA
        $sheet->_genSheets($objPHPExcel); 

        //IMPRIMIENDO LOS RESULTADOS
        

        //HOJA A MOSTRAR POR DEFECTO 
        $objPHPExcel->setActiveSheetIndex(0);

        //MECANISMO QUE GENERA EL EXCEL
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment;filename='{$name}.xlsx'");
        header("Cache-Control: max-age=0");

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        if($dir!=NULL){
            $objWriter->save($dir);
        }    
        elseif($dir==NULL){
            $objWriter->save('php://output');
            exit;
        }
    }
    
    //GENERA LA 1ERA HOJA DEL REPORTE
    private function _genSheets($objPHPExcel)
    {
        $data = $this->getData();
        if ($data != NULL)
        {
            $colum = array('A','B','C','D','E','F','G');
            $sheet =  0;
            foreach ($data as $key => $value) {
                
                if($value != NULL){
                    //SE CREAN LAS HOJAS NECESARIAS
                    $objPHPExcel->createSheet($sheet)->setCellValue('A1', 'CP')
                                                   ->setCellValue('B1', 'UNIDAD DE NEGOCIOS')
                                                   ->setCellValue('C1', 'DEPARTAMENTO')
                                                   ->setCellValue('D1', 'SUB DEPARTAMENTO')
                                                   ->setCellValue('E1', 'AREA')
                                                   ->setCellValue('F1', 'CARGO')
                                                   ->setCellValue('G1', 'NOMBRE');

                    foreach ($colum as $key4 => $value4) {
                        $this->fontSite($value4.'1','FFFFFF','11','center',$objPHPExcel,$sheet);
                        $this->cellColor($value4.'1','003366',$objPHPExcel,$sheet);
                        $this->borderColor($value4.'1','000000',0,$objPHPExcel,$sheet);
                    }

                    $objPHPExcel->setActiveSheetIndex($sheet)->getRowDimension('1')->setRowHeight(25);
                    $objPHPExcel->setActiveSheetIndex($sheet)->getColumnDimension('A')->setWidth(15);
                    $objPHPExcel->setActiveSheetIndex($sheet)->getColumnDimension('B')->setWidth(23);
                    $objPHPExcel->setActiveSheetIndex($sheet)->getColumnDimension('C')->setWidth(55);
                    $objPHPExcel->setActiveSheetIndex($sheet)->getColumnDimension('D')->setWidth(23);
                    $objPHPExcel->setActiveSheetIndex($sheet)->getColumnDimension('E')->setWidth(20);
                    $objPHPExcel->setActiveSheetIndex($sheet)->getColumnDimension('F')->setWidth(50);
                    $objPHPExcel->setActiveSheetIndex($sheet)->getColumnDimension('G')->setWidth(40);
                    
                    $objPHPExcel->setActiveSheetIndex($sheet)->getStyle('A1:G1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

                    $count = 0;
                    foreach ($value as $key2 => $value2) {

                        if(substr_count($value2['A'], '.') == 1){
                            //TITULOS DE LAS HOJAS
                            $objPHPExcel->setActiveSheetIndex($sheet)->setTitle($value2['A']);
                        }
                        foreach ($value2 as $key3 => $value3) {
                            $objPHPExcel->setActiveSheetIndex($sheet)->setCellValueExplicit($key3.($count+2), $value3, PHPExcel_Cell_DataType::TYPE_STRING);
                            $objPHPExcel->setActiveSheetIndex($sheet)->getStyle($key3.($count+2))->getAlignment()->setWrapText(true);
                            $this->fontSite($key3.($count+2),'000000','11','left',$objPHPExcel,$sheet);
                            if($key3 == 'A'){
                                $gTopic = $this->getNounOfSubordinate($value3);
                                if($gTopic != 0){
                                    foreach ($colum as $key4 => $value4) {
                                        $this->cellColor($value4.($count+2),'33CCCC',$objPHPExcel,$sheet);
                                        $this->borderColor($value4.($count+2),'000000',0,$objPHPExcel,$sheet);
                                    }
                                }
                            }else{
                                foreach ($colum as $key4 => $value4) {
                                    $this->borderColor($value4.($count+2),'000000',0,$objPHPExcel,$sheet);
                                }
                            }
                        }
                        $count++;
                    }
                    $sheet++;
                }
            }
            $objPHPExcel->removeSheetByIndex($sheet);
        }
        else
        {
            //SE CREAN LAS HOJAS NECESARIAS
            $objPHPExcel->createSheet(0)->setCellValue('A1', 'No Existen Códigos CPs');
            //TITULOS DE LAS HOJAS
            $objPHPExcel->setActiveSheetIndex(0)->setTitle('ARU Reporte');
        }
        
    }
    
    private function getData() 
    {
        $array = $array_last = $array_president = array();
        $sql = "SELECT pc.position_code, d.name as division, p.name as position, (initcap(e.first_name) || ' ' || initcap(e.last_name)) as employee
                FROM position_code pc
                INNER JOIN employee e ON e.id = pc.id_employee
                INNER JOIN division d ON d.id = pc.id_division
                INNER JOIN position p ON p.id = pc.id_position
                WHERE pc.end_date IS NULL
                ORDER BY btrsort(pc.position_code);";
        $model = PositionCode::model()->findAllBySql($sql);
        if($model != NULL){
            $sheet = 0;
            foreach ($model as $key => $value) {
                if(substr_count($value->position_code, '.') === 1){
                    $sheet++;
                }
                if($value->position_code == '1'){
                    $array_president[$key] = array(
                        'A' => $value->position_code,
                        'B' => '',
                        'C' => $value->division,
                        'D' => '',
                        'E' => '',
                        'F' => $value->position,
                        'G' => $value->employee,
                    );
                }else{
                    if(substr_count($value->position_code, '.') === 1){
                        $array_last[$key] = array(
                            'A' => $value->position_code,
                            'B' => '',
                            'C' => $value->division,
                            'D' => '',
                            'E' => '',
                            'F' => $value->position,
                            'G' => $value->employee,
                        );
                    }
                    $array[$sheet][$key] = array(
                        'A' => $value->position_code,
                        'B' => '',
                        'C' => $value->division,
                        'D' => '',
                        'E' => '',
                        'F' => $value->position,
                        'G' => $value->employee,
                    );
                }
                if($sheet != 0){
                    $array[$sheet] = ($array_president + $array_last + $array[$sheet]);
                }
            }
        }
        return $array;
    }
    
    public function getNounOfSubordinate($positionCode) 
    {
        $model = PositionCode::model()->findAll("position_code LIKE '$positionCode.%'");
        if($model == NULL){
            $countS = 0;
        }else{
            $countS = count($model);
        }
        return $countS;
    }
    
    //ASIGNA LOS VALORES DE COLOR Y TAMAÑO A LA CELDA ESPECIFICADA
    public function fontSite($cells,$color,$size,$align,$objPHPExcel,$sheet)
    {
        $styleArray = array(
            'font'  => array(
                'bold'  => true,
                'color' => array('rgb' => $color),
                'size'  => $size,
                'name'  => 'Calibri',
        ));
        
        if($align == 'center')
            $objPHPExcel->setActiveSheetIndex($sheet)->getStyle($cells)->applyFromArray($styleArray)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        if($align == 'left')
            $objPHPExcel->setActiveSheetIndex($sheet)->getStyle($cells)->applyFromArray($styleArray)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        if($align == 'right')
            $objPHPExcel->setActiveSheetIndex($sheet)->getStyle($cells)->applyFromArray($styleArray)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    }
    
    //ASIGNA COLOR A UNA CELDA ESPECIFICADA
    public function cellColor($cells,$color,$objPHPExcel,$sheet)
    {
        $objPHPExcel->setActiveSheetIndex($sheet)->getStyle($cells)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array('rgb' => $color),'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_DOUBLE),'rgb' => $color)
        ));
    }
    
    //ASIGNA COLOR AL BORDE DE UNA CELDA ESPECIFICADA
    public function borderColor($cells,$color,$rotate,$objPHPExcel,$sheet)
    {
        $styleArray = array(
               'borders' => array(
                     'outline' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('rgb' => $color),
                     ),
               ),
        );
        $objPHPExcel->setActiveSheetIndex($sheet)->getStyle($cells)->applyFromArray($styleArray);
        $objPHPExcel->setActiveSheetIndex($sheet)->getStyle($cells)->getAlignment()->setTextRotation($rotate);
    }
    
    //ASIGNA COLOR AL BORDE DE UNA CELDA ESPECIFICADA
    public function borderColorNone($cells,$color,$objPHPExcel,$sheet)
    {
        $styleArray = array(
               'borders' => array(
                     'outline' => array(
                            'style' => PHPExcel_Style_Border::BORDER_NONE,
                            'color' => array('rgb' => $color),
                     ),
               ),
        );
        $objPHPExcel->setActiveSheetIndex($sheet)->getStyle($cells)->applyFromArray($styleArray);
    }
    
    //ASIGNA COLOR AL LADO DEL BORDE DE UNA CELDA ESPECIFICADA
    public function borderSiteColor($cells,$color,$site,$objPHPExcel,$sheet)
    {
        $styleArray = array('font' => array('italic' => false, 'bold'=> true,    ),
            'borders' => array(
                $site => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => $color)),
        ),);
        $objPHPExcel->setActiveSheetIndex($sheet)->getStyle($cells)->applyFromArray($styleArray);
    }
}
