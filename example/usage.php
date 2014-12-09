<?php
// do not execute further code
return;

$translator = new Translator();
$translator->loadFile("cz.neon");


// in presenter:
$this->template->setTranslator($translator);


// in latte template:
/*

 {_'item.key'}


 */