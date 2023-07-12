<?php

use frontend\models\wikiinvestment\Member;
use frontend\models\wikiinvestment\Translator;

$language = Translator::find()->where(["status" => 1])->asArray()->all();
$a = [];
if (count($language) > 0) {
	foreach ($language as $lang) :
		$japanese[$lang["english"]] = $lang["japanese"];
	endforeach;
}
return $japanese;
