<?php
class Excel extends CFormatter
{
    public function genExcel($name,$html,$salida=true)
    {
        if($salida)
        {
            header('Content-type: application/vnd.ms-excel');
            header("Content-Disposition: attachment; filename={$name}.xls");
            header("Pragma: cache");
            header("Expires: 0");
            echo $html;
        }
        else
        {
            $ruta=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR;
            $fp=fopen($ruta."$name.xls","w+");
            $cuerpo="<!DOCTYPE html>
                        <html>
                            <head>
                                <meta charset='utf-8'>
                                <meta http-equiv='Content-Type' content='application/vnd.ms-excel charset=utf-8'>
                            </head>
                            <body>";
            $cuerpo.=$html;
            $cuerpo.="</body>
            </html>";
            fwrite($fp,$cuerpo);
        }
    }
}
?>