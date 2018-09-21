<?php
class Email extends CFormatter
{
    public function sendEmail($html,$correo,$topic,$dir=null, $copy=null)
    {
        if(isset($html) && isset($correo))
        {
            $mailer=Yii::createComponent('application.extensions.mailer.EMailer');
            $mailer->Host = 'mail.etelix.com';
            $mailer->Port = '475';
//            $mailer->SMTPDebug = 2;
            $mailer->SMTPSecure = 'tls';
            $mailer->Username = 'aru@etelix.com';
            $mailer->SMTPAuth = true;
            $mailer->Password ="3t3l1x.aru";
            $mailer->IsSMTP();
            $mailer->IsHTML(true);
            $mailer->From = 'aru@etelix.com';
            $mailer->AddReplyTo('aru@etelix.com');
            $mailer->AddAddress($correo);
//            $mailer->addBCC($correo);
            if ($copy != null)
            {
                foreach ($copy as $key => $value)
                {
                  $mailer->addCC($value);
                }
            }
//            $mailer->addBCC('carlosr@sacet.biz');
            $mailer->FromName = 'ARU';
            $mailer->CharSet = 'UTF-8';
            $mailer->Subject = Yii::t('', $topic);
            if($dir!=null){
                $mailer->AddAttachment($dir);
            }    
            $message = $html;
            $mailer->Body = $message;
            if($mailer->Send() && $dir!=null){
                unlink($dir);
            }    
        }
    }
}
?>