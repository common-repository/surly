<?php

class ShortenerTest extends PHPUnit_Framework_TestCase
{
	public function testShortenCurl()
	{
		$surly = new Surly();

		$this->assertEquals(
			'{"urls":{"ixbt.com":"qx"},"errors":[]}',
			$surly->_performCurlRequest(
				"{$surly->apiHost}{$surly->apiPath}",
				'POST',
				array(
					'raw' => 1,
					'urls' => 'ixbt.com'
				)
			)
		);
	}

	public function testShortenStream()
	{
		$surly = new Surly();

		$this->assertEquals(
			'{"urls":{"ixbt.com":"qx"},"errors":[]}',
			$surly->_performStreamRequest(
				"{$surly->apiHost}{$surly->apiPath}",
				'POST',
				array(
					'raw' => 1,
					'urls' => 'ixbt.com'
				)
			)
		);
	}

	public function testShortenSocket()
	{
		$surly = new Surly();

		$this->assertEquals(
			'{"urls":{"ixbt.com":"qx"},"errors":[]}',
			$surly->_performSocketRequest(
				"{$surly->apiHost}{$surly->apiPath}",
				'POST',
				array(
					'raw' => 1,
					'urls' => 'ixbt.com'
				)
			)
		);
	}

	public function testShortenWithWww()
	{
		$surly = $this->getMockSurly(
			array(
				'google.com' => 'gl'
			),
			null,
			true
		);

		$text = '
			<a href="http://google.com">link</a>
			<a href="http://www.google.com">link</a>
			<a href="http://google.com">http://google.com</a>
			<a href="http://www.google.com">http://www.google.com</a>
			';
		
		$expected = '
			<a href="http://sur.ly/o/gl/AA000015">link</a>
			<a href="http://sur.ly/o/gl/AA000015">link</a>
			<a href="http://sur.ly/o/gl/AA000015">http://google.com</a>
			<a href="http://sur.ly/o/gl/AA000015">http://www.google.com</a>
			';

		$this->assertEquals($expected, $surly->process($text));
	}

	public function testShortenWithFragment()
	{
		$surly = $this->getMockSurly(
			array(
				'google.com' => 'gl',
				'google.com/#test' => 'glh',
			),
			null,
			true
		);

		$text = '
			<a href="http://google.com">link</a>
			<a href="http://www.google.com">link</a>
			<a href="http://google.com#test">link</a>
			<a href="http://google.com#test">http://google.com#test</a>
			';

		$expected = '
			<a href="http://sur.ly/o/gl/AA000015">link</a>
			<a href="http://sur.ly/o/gl/AA000015">link</a>
			<a href="http://sur.ly/o/glh/AA000015">link</a>
			<a href="http://sur.ly/o/glh/AA000015">http://google.com#test</a>
			';

		$this->assertTrue(true);
		$this->assertEquals($expected, $surly->process($text));
	}

	public function testDontShortenInvalidUrls()
	{
		$surly = $this->getMockSurly(
			array(
				'google.com' => 'gl'
			),
			null,
			true
		);

		$text = '
			<a href="http://google.com">link</a>
			<a href="http://www.">link</a>
			<a href="http://sdfgsdfgsdfgwww">link</a>
			<a href="http://">link</a>
			<a href="http://www.">http://www.</a>
			<a href="http://sdfgsdfgsdfgwww">http://sdfgsdfgsdfgwww</a>
			<a href="http://">http://</a>
			';

		$expected = '
			<a href="http://sur.ly/o/gl/AA000015">link</a>
			<a href="http://www.">link</a>
			<a href="http://sdfgsdfgsdfgwww">link</a>
			<a href="http://">link</a>
			<a href="http://www.">http://www.</a>
			<a href="http://sdfgsdfgsdfgwww">http://sdfgsdfgsdfgwww</a>
			<a href="http://">http://</a>
			';

		$this->assertEquals($expected, $surly->process($text));
	}

	public function testDontShortenInvalidUrlsWithToolbarId()
	{
		$surly = $this->getMockSurly(
			array(
				'google.com' => 'gl'
			),
			'AA000015',
			true
		);

		$text = '
			<a href="http://google.com">link</a>
			<a href="http://google.com">http://google.com</a>
			<a href="http://www.">link</a>
			<a href="http://sdfgsdfgsdfgwww">link</a>
			<a href="http://">link</a>
			';

		$expected = '
			<a href="http://sur.ly/o/gl/AA000015">link</a>
			<a href="http://sur.ly/o/gl/AA000015">http://google.com</a>
			<a href="http://www.">link</a>
			<a href="http://sdfgsdfgsdfgwww">link</a>
			<a href="http://">link</a>
			';

		$this->assertEquals($expected, $surly->process($text));
	}

	public function testProcessMultipleUrls() {
		$surly = $this->getMockSurly(
			array(
				'google.com' => 'gl',
				'ixbt.com' => 'qx',
			),
			'AA000015',
			true
		);

		$test = array(
			'https://google.com',
			'http://ixbt.com',
		);

		$expected = array(
			'https://sur.ly/o/gl/AA000015',
			'http://sur.ly/o/qx/AA000015',
		);

		$processedUrls = $surly->processMultipleUrls($test);

		foreach ($expected as $key => $url) {
			$this->assertEquals($url, $processedUrls[$key]);
		}
	}

	public function testProcessUrl() {
		$surly = $this->getMockSurly(
			array(
				'google.com' => 'gl',
				'ixbt.com' => 'qx',
			),
			'AA000015',
			true
		);

		$test = array(
			'https://google.com',
			'http://ixbt.com',
		);

		$expected = array(
			'https://sur.ly/o/gl/AA000015',
			'http://sur.ly/o/qx/AA000015',
		);

		foreach ($expected as $key => $url) {
			$this->assertEquals($url, $surly->processUrl($test[$key]));
		}
	}

	private function getMockSurly(array $shortenerAnswer, $toolbarId, $isUseShortener = false)
	{
		$surly = $this->getMock('Surly', array('_shortenRemotely'), array($toolbarId, $isUseShortener));

		$surly->expects($this->any())
			->method('_shortenRemotely')
			->withAnyParameters()
			->will($this->returnValue(
				$shortenerAnswer
			));

		return $surly;
	}
}