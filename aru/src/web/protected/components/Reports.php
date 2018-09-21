<?php
/**
 * @package components
 */
class Reports extends CApplicationComponent
{
    public function init() 
    {
       
    }

    /**
     * busca el reporte en componente "balanceAdmin" hace la consulta y extrae 
     * los atributos necesarios para luego formar el html y enviarlo por correo y/o exportarlo a excel
     * @param array $ids
     * @return string
     */
    
    public function adminPositionCode($day,$name,$dir)
    {
        $var=  adminPositionCode::report($day,$name,$dir);
        return $var;
    }
    
    public function newPositionCode($name,$dir)
    {
        $var=  newPositionCode::report($name,$dir);
        return $var;
    }
    
    public function declareDayEmployee($data,$name,$dir)
    {
        $var=  declareDayEmployee::report($data,$name,$dir);
        return $var;
    }
    
    public function adminHourEmployee($content,$name)
    {
        $var=  adminHourEmployee::report($content,$name);
        return $var;
    }


    /**
     * Documentacion va aqui
     */
    public static function defineStyleTd($type)
    {
        switch ($type)
        {
            case ($type%2==0):
                $style="style ='background: #E5F1F4; font-size: 12px; text-align: center; background-position: initial initial; background-repeat: initial initial;font-size: 11px;'";
                break;
            case ($type%2!=0):
                $style="style ='background: #F8F8F8; font-size: 12px; text-align: center; background-position: initial initial; background-repeat: initial initial;font-size: 11px;'";
                break;
        }
        return $style;
    }

    /**
     * Documentacion va aqui
     */
    public static function defineStyleHeader($type)
    {
        switch ($type){
            case "balance":
                $style="style='background:#00992B;color:#FFF;border:0px solid black; font-size: 12px;'";
                break;
            case "libroV":
                $style="style='background:#FFBB00;color:#FFF;border:0px solid black; font-size: 12px;'";
                break;
            case "depositos":
                $style="style='background:#1967B2;color:#FFF;border:0px solid black; font-size: 12px;'";
                break;
            case "captura":
                $style="style='background:#cc99cc;color:#FFF;border:0px solid black; font-size: 12px;'";
                break;
            case "brightstar":
                $style="style='background:#FF9933;color:#FFF;border:0px solid black; font-size: 12px;'";
                break;
            case "sobrante":
                $style="style='background:#5E99F2;color:#FFF;border:0px solid black; font-size: 12px;'";
                break;
        }
        return $style;
    }
    
    /**
     * Documentacion va aqui
     */
    public static function defineHeader($type)
    {
        switch ($type) {
            case "balance":
                $header='<thead>
                            <tr >
                                <th '.self::defineStyleHeader("balance").' id="Fechas" width="70">Fecha</th>
                                <th '.self::defineStyleHeader("balance").' id="balance-grid_c2" width="80">Cabina</th>
                                <th '.self::defineStyleHeader("balance").' id="balance-grid_c3">Saldo Apertura (S/.)</th>
                                <th '.self::defineStyleHeader("balance").' id="balance-grid_c5">Trafico (S/.)</th>
                                <th '.self::defineStyleHeader("balance").' id="balance-grid_c6">Servicios Movistar (S/.)</th>
                                <th '.self::defineStyleHeader("balance").' id="balance-grid_c7">Servicios Claro (S/.)</th>
                                <th '.self::defineStyleHeader("balance").' id="balance-grid_c7">Servicios DirecTv (S/.)</th>
                                <th '.self::defineStyleHeader("balance").' id="balance-grid_c7">Servicios Nextel (S/.)</th>    
                                <th '.self::defineStyleHeader("balance").' id="balance-grid_c8">Monto Deposito (S/.)</th>
                            </tr>
                        </thead>';
                break;
        }
        return $header;
    }
    
    /**
     * Documentacion va aqui
     */
    public static function defineMonto($type,$number=null)
    {
        if($type == null)
        {
            $field = '0.00';
        }
        else
        {
            if((float)$number<0)
            {
                $field = '<font color="red">'.$type.'</font>';
            }
            else
            {
                if((float)$number>0)
                {
                    $field = '<font color="green">'.$type.'</font>';
                }
                else
                {
                    $field = $type;
                }    
            }
        }
        return $field;
    }
    
    /**
     * Documentacion va aqui, cambiar nombre a esta funcion
     */
    public static function defineMonto2($type,$number=null)
    {
        if($type == '-1')
        {
            $field = 'No Declarado';
        }
        else
        {
            if($type == null)
            {
                $field = '0.00';
            }
            else
            {
                if((float)$number<0)
                {
                    $field = '<font color="red">'.$type.'</font>';
                }
                else
                {
                    if((float)$number>0)
                    {
                        $field = '<font color="green">'.$type.'</font>';
                    }
                    else
                    {
                        $field = $type;
                    }    
                }
            }
        }
        return $field;
    }
    
    /**
     * Documentacion va aqui
     */
    public static function defineTotals($type,$number=null)
    {
        if($type == null)
        {
            $field = '0.00';
        }
        else
        {
            if((float)$number<0)
            {
                $field = '<font color="red">'.$type.'</font>';
            }
            else
            {
                if((float)$number>0)
                {
                    $field = '<font color="green">'.$type.'</font>';
                }
                else
                {
                    $field = $type;
                }    
            }
        }
        return $field;
    }
    
    /**
     * Documentacion va aqui, cambiar nombre a esta funcion
     */
    public static function defineTotals2($type,$number=null)
    {
        if($type == '-1')
        {
            $field = 'No Declarado';
        }
        else
        {
            if($type == null)
            {
                $field = '0.00';
            }
            else
            {
                if((float)$number<0)
                {
                    $field = '<font color="red">'.$type.'</font>';
                }
                else
                {
                    if((float)$number>0)
                    {
                        $field = '<font color="green">'.$type.'</font>';
                    }
                    else
                    {
                        $field = $type;
                    }    
                }
            }
        }
        return $field;
    }
    
    /**
     * Documentacion va aqui
     */
    public static function definePago($type,$number=null)
    {
        if($number == 'Pagada')
        {
            $field = $type;
        }
        else
        {
            $field = 'N/A';
        }
        return $field;
    }
    
    public static function format($monto,$type)
    {
        if($type == true)
        {
            $field = Utility::PuntoPorComa($monto);
        }
        else
        {
            $field = $monto;
        }
        return $field;
    }
    
    
}
?>