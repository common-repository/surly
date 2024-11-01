<?php

class BackupHostTest extends PHPUnit_Framework_TestCase 
{
	public function testRequestRootStatusCurl()
	{
		$surly = new Surly();
		$this->assertEquals('GOOD', $surly->_performCurlRequest("{$surly->apiHost}{$surly->apiRootStatusPath}"), 'Failure! Please, first check root status of server and then check your code.');
				
		$surly->apiHost = 'asdfasdfasdfadfasdf.com';
		$this->assertEquals('', $surly->_performCurlRequest("{$surly->apiHost}{$surly->apiRootStatusPath}"));
	}
	
	public function testRequestRootStatusStream()
	{
		$surly = new Surly();
		$this->assertEquals('GOOD', $surly->_performStreamRequest("{$surly->apiHost}{$surly->apiRootStatusPath}"), 'Failure! Please, first check root status of server and then check your code.');
				
		$surly->apiHost = 'asdfasdfasdfadfasdf.com';
		$this->assertEquals('', $surly->_performStreamRequest("{$surly->apiHost}{$surly->apiRootStatusPath}"));
	}
	
	public function testRequestRootStatusSocket()
	{
		$surly = new Surly();
		$this->assertEquals('GOOD', $surly->_performSocketRequest("{$surly->apiHost}{$surly->apiRootStatusPath}"), 'Failure! Please, first check root status of server and then check your code.');
				
		$surly->apiHost = 'asdfasdfasdfadfasdf.com';
		$this->assertEquals('', $surly->_performSocketRequest("{$surly->apiHost}{$surly->apiRootStatusPath}"));
	}
	
	public function testUseBackupHost()
	{
		$surly = $this->getMock('Surly', array('_checkIsRootDomainAliveRemotely'), array('AA00150'));
				
		$surly->expects($this->any())
			->method('_checkIsRootDomainAliveRemotely')
			->withAnyParameters()
			->will($this->returnValue(
				false
			));
		$surly->isRootDomainAlive = null;

		$text = '
			<a href="http://www.some.com/thing?test=5&a=5">link</a>
			<a href="http://www.some.com/thing/">link</a>
			';
		
		$expected = '
			<a href="http://surdotly.com/o/some.com/thing%3Ftest%3D5%26a%3D5/AA00150">link</a>
			<a href="http://surdotly.com/o/some.com/thing%2F/AA00150">link</a>
			';
		
		$this->assertEquals($expected, $surly->process($text));		
	}
	
	public function testRootStatusApiNotAvailable()
	{
		$surly = new Surly('AA00150');
		$surly->apiHost = 'asdfasdfasdfasdfasdfasdfasdfadfasdfasdfasdf.com';
		
		$text = '
			<a href="http://www.some.com/thing?test=5&a=5">link</a>
			<a href="http://www.some.com/thing/">link</a>
			';
		
		$expected = '
			<a href="http://sur.ly/o/some.com/thing%3Ftest%3D5%26a%3D5/AA00150">link</a>
			<a href="http://sur.ly/o/some.com/thing%2F/AA00150">link</a>
			';
		
		$this->assertEquals($expected, $surly->process($text));		
	}
}