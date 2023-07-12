<?php

namespace common\email;

use Yii;

class Email
{
	public static function jobUpdate($toMail, $subject, $data)
	{
		Yii::$app->mail->compose('job_update', ["data" => $data])
			->setTo($toMail) //tomail
			// ->setFrom('saknakhngam@gmail.com')
			->setFrom('lower-management@tokyoconsultinggroup.com')
			->setSubject($subject)
			->send();
	}
	public static function jobComplain($toMail, $subject, $data)
	{
		Yii::$app->mail->compose('job_complain', ["data" => $data])
			->setTo($toMail) //tomail
			// ->setFrom('saknakhngam@gmail.com')
			->setFrom('lower-management@tokyoconsultinggroup.com')
			->setSubject($subject)
			->send();
	}
}
