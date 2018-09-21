 <?php

    /**
     * @package reportes
     */
    class adminPositionCode extends Reports 
    {
        public static function report($ids,$name,$type) 
        {
            
            $nameFormat = htmlentities($name, ENT_QUOTES,'UTF-8');

            $styleHeader = "border: 1px solid #ddd; "
                            . "background-color: #DDD;"
                            . "text-align: left;"
                            . "margin-top: 10px;"
                            . "font-size: 12px;"
                            . "font-weight: 600;";
            
            $styleBody = "padding: 5px;"
                            . "line-height: 1.428571429;"
                            . "vertical-align: top;"
                            . "font-size: 12px;"
                            . "border-top: 1px solid #ddd;"
                            . "text-align: left;"
                            . "border: 1px solid #ddd;"
                            . "padding: 5px;";
            
            $positionCode = self::getModel($ids);
            if($positionCode != NULL){
                
                $table = "<h2 style='font-family: 'Trebuchet MS', Arial, Helvetica, sans-serif;letter-spacing: -1px;text-transform: uppercase;'>{$nameFormat}</h2>
                        <br>
                        <table style='border-spacing: 2px;border-color: gray;border-collapse: collapse;border-spacing: 0;'>
                            <thead class='flip-content'>
                              <tr>
                                 <th style='$styleHeader'>".self::formatText('Nombre')."</th>
                                 <th style='$styleHeader'>".self::formatText('Apellido')."</th>
                                 <th style='$styleHeader'>".self::formatText('División')."</th>
                                 <th style='$styleHeader'>".self::formatText('Dependencia')."</th>
                                 <th style='$styleHeader'>".self::formatText('Posición')."</th>
                                 <th style='$styleHeader'>".self::formatText('Código de Posición')."</th>
                                 <th style='$styleHeader'>".self::formatText('Fecha de Inicio')."</th>
                                 <th style='$styleHeader'>".self::formatText('Fecha Fin')."</th>
                              </tr>
                           </thead>
                        <tbody>";
                foreach ($positionCode as $key => $registro) {

                    $table.=   "<tr>
                                   <td style='$styleBody'>".self::formatText($registro->idEmployee->first_name)."</td>
                                   <td style='$styleBody'>".self::formatText($registro->idEmployee->last_name)."</td>
                                   <td style='$styleBody'>".self::formatText($registro->idDivision->name)."</td>
                                   <td style='$styleBody'>".self::formatText(Division::getNameDivision($registro->id_dependency))."</td>    
                                   <td style='$styleBody'>".self::formatText($registro->idPosition->name)."</td>
                                   <td style='$styleBody'>".$registro->position_code."</td>
                                   <td style='$styleBody'>".$registro->start_date."</td>
                                   <td style='$styleBody'>".$registro->end_date."</td>
                                </tr>";

                }

                 $table.= '</tbody>
                        </table>';
            }else{
                $table='Hubo un error';
            }
            return $table;
        }
            
         
        public static function getModel($ids)
        {
            $modelPositionCode = PositionCode::model()->findAllBySql("SELECT pc.id, pc.position_code, pc.id_position, pc.id_division, d.id_dependency, pc.id_employee, pc.start_date, pc.end_date
                                                                      FROM position_code pc
                                                                      INNER JOIN division as d ON d.id = pc.id_division
                                                                      INNER JOIN position as p ON p.id = pc.id_position
                                                                      INNER JOIN employee as e ON e.id = pc.id_employee
                                                                      WHERE pc.id IN ($ids)
                                                                      ORDER BY pc.position_code ASC;");
            return $modelPositionCode;

        }
        
        public static function formatText($text) 
        {
            $textFormat = htmlentities($text, ENT_QUOTES,'UTF-8');
            return $textFormat;
        }

    }
    ?>