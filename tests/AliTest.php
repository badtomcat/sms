<?php


class AliTest extends PHPUnit_Framework_TestCase
{
	/**
	* A basic test example.
	*
	* @return void
	*/
	public function testx()
	{
	    $config = require __DIR__."/config.php";
		$test = new \Badtomcat\Sms\Ali\Send('*Tl**jHi***','kRp7******zdSRz');
        $test->setPhoneNumber("136xxxxxxxx");
        $test->setSignName("****");
        $test->setTemplateCode("SMS_1222822**");
        $test->setTemplateParam(array (
            "code" => "102530",
        ));
        $this->assertTrue($test->send());
	}
}


