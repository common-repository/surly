<?php

class ReplaceTest extends PHPUnit_Framework_TestCase
{
	public function testNofollow()
	{
		$surly = new Surly('AA00150');
		$surly->whitelist('doamin.com');
		$surly->whitelist('nofollow.com');

		$text = '
			<!-- whitelist -->
			<a rel="nofollow" href="http://doamin.com/">link</a>
			<a rel="nofollow" href="http://nofollow.com/">link</a>
			<!-- \whitelist -->
			<a rel="nofollow" href="http://www.some.com/">link</a>
			<a rel=\'nofollow\' href="http://www.some.com/">link</a>
			<a href="http://www.some.com/" rel="nofollow">link</a>
			<a rel="tag" href="http://www.some.com/">link</a>
			<a href="http://www.some.com/" rel="tag">link</a>
			<a class="nofollow" href="http://www.some.com/">link</a>
			<a href="http://www.some.com/" class="nofollow">link</a>
			<a class="rel" href="http://www.some.com/">link</a>
			<a class="rel nofollow" href="http://www.some.com/">link</a>
			<a class="rel nofollow" rel="nofollow" href="http://www.some.com/">link</a>
			<a class="nofollow" rel="nofollow" data="nofollow" href="http://www.some.com/">link</a>
			<a href="http://www.some.com/" class="nofollow" rel="nofollow" data="nofollow">link</a>
			<a rel="nofollow" data-href="nofollow" href="http://www.some.com/">link</a>
			<a rel="nofollow" data-rel="nofollow" href="http://www.some.com/">link</a>
			<a data-rel="nofollow" rel="nofollow" href="http://www.some.com/">link</a>
			<a href="http://www.some.com/" data-rel="nofollow" rel="nofollow">link</a>
			<a data="nofollow" href="http://www.some.com/">link</a>
			<a href="http://www.some.com/" data="nofollow">link</a>
			<a class="nofollow" rel="author nofollow" data="nofollow" href="http://www.some.com/">link</a>
			<a class="nofollow" rel="nofollow author" data="nofollow" href="http://www.some.com/">link</a>
			<a class="nofollow" rel="archives nofollow author" data="nofollow" href="http://www.some.com/">link</a>
			<a class="nofollow" rel="nofollow archives author" data="nofollow" href="http://www.some.com/">link</a>
			<a class="nofollow" rel="archives author nofollow" data="nofollow" href="http://www.some.com/">link</a>
			<a rel="archives author" class="nofollow" data="nofollow" href="http://www.some.com/">link</a>
			<a data="nofollow" rel="archives author" class="nofollow" href="http://www.some.com/">link</a>
			<a href="http://www.some.com/" rel="archives author" class="nofollow" data="nofollow">link</a>
			<a href="http://www.some.com/" data="nofollow" rel="archives author" class="nofollow">link</a>
			<a rel="" class="nofollow" data="nofollow" href="http://www.some.com/">link</a>
			<a rel="NOFOLLOW" href="http://www.some.com/">link</a>
			<a REL="nofollow" DATA="nofollow" href="http://www.some.com/">link</a>
			<a nofollow rel="author" href="http://www.some.com/">link</a>
			<a rel="nofollows" href="http://www.some.com/">link</a>
		';

		$expected = '
			<!-- whitelist -->
			<a rel="nofollow" href="http://doamin.com/">link</a>
			<a rel="nofollow" href="http://nofollow.com/">link</a>
			<!-- \whitelist -->
			<a href="http://sur.ly/o/some.com/AA00150">link</a>
			<a href="http://sur.ly/o/some.com/AA00150">link</a>
			<a href="http://sur.ly/o/some.com/AA00150">link</a>
			<a rel="tag" href="http://sur.ly/o/some.com/AA00150">link</a>
			<a href="http://sur.ly/o/some.com/AA00150" rel="tag">link</a>
			<a class="nofollow" href="http://sur.ly/o/some.com/AA00150">link</a>
			<a href="http://sur.ly/o/some.com/AA00150" class="nofollow">link</a>
			<a class="rel" href="http://sur.ly/o/some.com/AA00150">link</a>
			<a class="rel nofollow" href="http://sur.ly/o/some.com/AA00150">link</a>
			<a class="rel nofollow" href="http://sur.ly/o/some.com/AA00150">link</a>
			<a class="nofollow" data="nofollow" href="http://sur.ly/o/some.com/AA00150">link</a>
			<a href="http://sur.ly/o/some.com/AA00150" class="nofollow" data="nofollow">link</a>
			<a data-href="nofollow" href="http://sur.ly/o/some.com/AA00150">link</a>
			<a data-rel="nofollow" href="http://sur.ly/o/some.com/AA00150">link</a>
			<a data-rel="nofollow" href="http://sur.ly/o/some.com/AA00150">link</a>
			<a href="http://sur.ly/o/some.com/AA00150" data-rel="nofollow">link</a>
			<a data="nofollow" href="http://sur.ly/o/some.com/AA00150">link</a>
			<a href="http://sur.ly/o/some.com/AA00150" data="nofollow">link</a>
			<a class="nofollow" rel="author " data="nofollow" href="http://sur.ly/o/some.com/AA00150">link</a>
			<a class="nofollow" rel=" author" data="nofollow" href="http://sur.ly/o/some.com/AA00150">link</a>
			<a class="nofollow" rel="archives  author" data="nofollow" href="http://sur.ly/o/some.com/AA00150">link</a>
			<a class="nofollow" rel=" archives author" data="nofollow" href="http://sur.ly/o/some.com/AA00150">link</a>
			<a class="nofollow" rel="archives author " data="nofollow" href="http://sur.ly/o/some.com/AA00150">link</a>
			<a rel="archives author" class="nofollow" data="nofollow" href="http://sur.ly/o/some.com/AA00150">link</a>
			<a data="nofollow" rel="archives author" class="nofollow" href="http://sur.ly/o/some.com/AA00150">link</a>
			<a href="http://sur.ly/o/some.com/AA00150" rel="archives author" class="nofollow" data="nofollow">link</a>
			<a href="http://sur.ly/o/some.com/AA00150" data="nofollow" rel="archives author" class="nofollow">link</a>
			<a rel="" class="nofollow" data="nofollow" href="http://sur.ly/o/some.com/AA00150">link</a>
			<a href="http://sur.ly/o/some.com/AA00150">link</a>
			<a DATA="nofollow" href="http://sur.ly/o/some.com/AA00150">link</a>
			<a nofollow rel="author" href="http://sur.ly/o/some.com/AA00150">link</a>
			<a rel="nofollows" href="http://sur.ly/o/some.com/AA00150">link</a>
		';

		$this->assertEquals($expected, $surly->process($text));
	}

	public function testSubdomainNofollow()
	{
		$surly = new Surly('AA00150');
		$surly->setPanelHost('sub.domain.com');
		$surly->whitelist('doamin.com');
		$surly->whitelist('nofollow.com');

		$text = '
			<!-- whitelist -->
			<a rel="nofollow" href="http://doamin.com/">link</a>
			<a rel="nofollow" href="http://nofollow.com/">link</a>
			<a class="nofollow" rel="archives author nofollow" data="nofollow" href="http://www.doamin.com/">link</a>
			<!-- \whitelist -->
			<a rel="nofollow" href="http://www.some.com/">link</a>
			<a rel=\'nofollow\' href="http://www.some.com/">link</a>
			<a href="http://www.some.com/" rel="nofollow">link</a>
			<a rel="tag" href="http://www.some.com/">link</a>
			<a href="http://www.some.com/" rel="tag">link</a>
			<a class="nofollow" href="http://www.some.com/">link</a>
			<a href="http://www.some.com/" class="nofollow">link</a>
			<a class="rel" href="http://www.some.com/">link</a>
			<a class="rel nofollow" href="http://www.some.com/">link</a>
			<a class="rel nofollow" rel="nofollow" href="http://www.some.com/">link</a>
			<a class="nofollow" rel="nofollow" data="nofollow" href="http://www.some.com/">link</a>
			<a href="http://www.some.com/" class="nofollow" rel="nofollow" data="nofollow">link</a>
			<a rel="nofollow" data-href="nofollow" href="http://www.some.com/">link</a>
			<a rel="nofollow" data-rel="nofollow" href="http://www.some.com/">link</a>
			<a data-rel="nofollow" rel="nofollow" href="http://www.some.com/">link</a>
			<a href="http://www.some.com/" data-rel="nofollow" rel="nofollow">link</a>
			<a data="nofollow" href="http://www.some.com/">link</a>
			<a href="http://www.some.com/" data="nofollow">link</a>
			<a class="nofollow" rel="author nofollow" data="nofollow" href="http://www.some.com/">link</a>
			<a class="nofollow" rel="nofollow author" data="nofollow" href="http://www.some.com/">link</a>
			<a class="nofollow" rel="archives nofollow author" data="nofollow" href="http://www.some.com/">link</a>
			<a class="nofollow" rel="nofollow archives author" data="nofollow" href="http://www.some.com/">link</a>
			<a class="nofollow" rel="archives author nofollow" data="nofollow" href="http://www.some.com/">link</a>
			<a rel="archives author" class="nofollow" data="nofollow" href="http://www.some.com/">link</a>
			<a data="nofollow" rel="archives author" class="nofollow" href="http://www.some.com/">link</a>
			<a href="http://www.some.com/" rel="archives author" class="nofollow" data="nofollow">link</a>
			<a href="http://www.some.com/" data="nofollow" rel="archives author" class="nofollow">link</a>
			<a rel="" class="nofollow" data="nofollow" href="http://www.some.com/">link</a>
			<a rel="NOFOLLOW" href="http://www.some.com/">link</a>
			<a REL="nofollow" DATA="nofollow" href="http://www.some.com/">link</a>
			<a nofollow rel="author" href="http://www.some.com/">link</a>
			<a rel="nofollows" href="http://www.some.com/">link</a>
			<a rel="author" nofollow data-nofollow="author" nofollow="nofollow" href="http://www.some.com/">link</a>
		';

		$expected = '
			<!-- whitelist -->
			<a rel="nofollow" href="http://doamin.com/">link</a>
			<a rel="nofollow" href="http://nofollow.com/">link</a>
			<a class="nofollow" rel="archives author nofollow" data="nofollow" href="http://www.doamin.com/">link</a>
			<!-- \whitelist -->
			<a href="http://sub.domain.com/some.com/">link</a>
			<a href="http://sub.domain.com/some.com/">link</a>
			<a href="http://sub.domain.com/some.com/">link</a>
			<a rel="tag" href="http://sub.domain.com/some.com/">link</a>
			<a href="http://sub.domain.com/some.com/" rel="tag">link</a>
			<a class="nofollow" href="http://sub.domain.com/some.com/">link</a>
			<a href="http://sub.domain.com/some.com/" class="nofollow">link</a>
			<a class="rel" href="http://sub.domain.com/some.com/">link</a>
			<a class="rel nofollow" href="http://sub.domain.com/some.com/">link</a>
			<a class="rel nofollow" href="http://sub.domain.com/some.com/">link</a>
			<a class="nofollow" data="nofollow" href="http://sub.domain.com/some.com/">link</a>
			<a href="http://sub.domain.com/some.com/" class="nofollow" data="nofollow">link</a>
			<a data-href="nofollow" href="http://sub.domain.com/some.com/">link</a>
			<a data-rel="nofollow" href="http://sub.domain.com/some.com/">link</a>
			<a data-rel="nofollow" href="http://sub.domain.com/some.com/">link</a>
			<a href="http://sub.domain.com/some.com/" data-rel="nofollow">link</a>
			<a data="nofollow" href="http://sub.domain.com/some.com/">link</a>
			<a href="http://sub.domain.com/some.com/" data="nofollow">link</a>
			<a class="nofollow" rel="author " data="nofollow" href="http://sub.domain.com/some.com/">link</a>
			<a class="nofollow" rel=" author" data="nofollow" href="http://sub.domain.com/some.com/">link</a>
			<a class="nofollow" rel="archives  author" data="nofollow" href="http://sub.domain.com/some.com/">link</a>
			<a class="nofollow" rel=" archives author" data="nofollow" href="http://sub.domain.com/some.com/">link</a>
			<a class="nofollow" rel="archives author " data="nofollow" href="http://sub.domain.com/some.com/">link</a>
			<a rel="archives author" class="nofollow" data="nofollow" href="http://sub.domain.com/some.com/">link</a>
			<a data="nofollow" rel="archives author" class="nofollow" href="http://sub.domain.com/some.com/">link</a>
			<a href="http://sub.domain.com/some.com/" rel="archives author" class="nofollow" data="nofollow">link</a>
			<a href="http://sub.domain.com/some.com/" data="nofollow" rel="archives author" class="nofollow">link</a>
			<a rel="" class="nofollow" data="nofollow" href="http://sub.domain.com/some.com/">link</a>
			<a href="http://sub.domain.com/some.com/">link</a>
			<a DATA="nofollow" href="http://sub.domain.com/some.com/">link</a>
			<a nofollow rel="author" href="http://sub.domain.com/some.com/">link</a>
			<a rel="nofollows" href="http://sub.domain.com/some.com/">link</a>
			<a rel="author" nofollow data-nofollow="author" nofollow="nofollow" href="http://sub.domain.com/some.com/">link</a>
		';

		$this->assertEquals($expected, $surly->process($text));
	}

	public function testReplaceWithToolbarId()
	{
		$surly = new Surly('AA00150');
		
		$text = '
			<a href="http://www.some.com/">link</a>
			<a href="http://www.some.com/thing?test=5&a=5">link</a>
			<a href="https://www.some.com/thing?test=5&a=5">link</a>
			<a href="http://www.some.com/thing/">link</a>
			<a href="https://www.some.com/thing/">link</a>
			<a href="http://www.some.com/thing?test=5&a=5">http://www.some.com/thing?test=5&a=5</a>
			<a href="https://www.some.com/thing?test=5&a=5">https://www.some.com/thing?test=5&a=5</a>
			<a href="http://www.some.com/thing/">http://www.some.com/thing/</a>
			<a href="https://www.some.com/thing/">https://www.some.com/thing/</a>
			<!-- hash -->
			<a href="http://www.some.com#hash">link</a>
			<a href="http://www.some.com/#">link</a>
			<a href="http://www.some.com/#hash">link</a>
			<a href="http://www.some.com/path/#hash">link</a>
			<a href="http://www.some.com/index.html#hash">link</a>
			<a href="http://www.some.com/#hash/path">link</a>
			<a href="http://www.some.com/#/path">link</a>
			<a href="http://www.some.com/#!path">link</a>
			<a href="http://www.some.com/?query=foo#hash">link</a>
			<a href="http://www.some.com/?query=o&#039;">link</a>
			<a href="http://www.some.com/some.com#hash">link</a>
			<a href="http://www.some.com/path/some.com#hash">link</a>
			<!-- \hash -->
			';
		
		$expected = '
			<a href="http://sur.ly/o/some.com/AA00150">link</a>
			<a href="http://sur.ly/o/some.com/thing%3Ftest%3D5%26a%3D5/AA00150">link</a>
			<a href="https://sur.ly/o/some.com/thing%3Ftest%3D5%26a%3D5/AA00150">link</a>
			<a href="http://sur.ly/o/some.com/thing%2F/AA00150">link</a>
			<a href="https://sur.ly/o/some.com/thing%2F/AA00150">link</a>
			<a href="http://sur.ly/o/some.com/thing%3Ftest%3D5%26a%3D5/AA00150">http://www.some.com/thing?test=5&a=5</a>
			<a href="https://sur.ly/o/some.com/thing%3Ftest%3D5%26a%3D5/AA00150">https://www.some.com/thing?test=5&a=5</a>
			<a href="http://sur.ly/o/some.com/thing%2F/AA00150">http://www.some.com/thing/</a>
			<a href="https://sur.ly/o/some.com/thing%2F/AA00150">https://www.some.com/thing/</a>
			<!-- hash -->
			<a href="http://sur.ly/o/some.com/%23hash/AA00150">link</a>
			<a href="http://sur.ly/o/some.com/%23/AA00150">link</a>
			<a href="http://sur.ly/o/some.com/%23hash/AA00150">link</a>
			<a href="http://sur.ly/o/some.com/path%2F%23hash/AA00150">link</a>
			<a href="http://sur.ly/o/some.com/index.html%23hash/AA00150">link</a>
			<a href="http://sur.ly/o/some.com/%23hash%2Fpath/AA00150">link</a>
			<a href="http://sur.ly/o/some.com/%23%2Fpath/AA00150">link</a>
			<a href="http://sur.ly/o/some.com/%23%21path/AA00150">link</a>
			<a href="http://sur.ly/o/some.com/%3Fquery%3Dfoo%23hash/AA00150">link</a>
			<a href="http://sur.ly/o/some.com/%3Fquery%3Do%27/AA00150">link</a>
			<a href="http://sur.ly/o/some.com/some.com%23hash/AA00150">link</a>
			<a href="http://sur.ly/o/some.com/path%2Fsome.com%23hash/AA00150">link</a>
			<!-- \hash -->
			';
		
		$this->assertEquals($expected, $surly->process($text));
	}

	public function testSubdomainReplaceWithToolbarId()
	{
		$surly = new Surly('AA00150');
		$surly->setPanelHost('sub.domain.com');

		$text = '
			<a href="http://www.some.com/#hash">link</a>
			<a href="http://www.some.com/">link</a>
			<a href="http://www.some.com/thing?test=5&a=5">link</a>
			<a href="https://www.some.com/thing?test=5&a=5">link</a>
			<a href="http://www.some.com/thing/">link</a>
			<a href="https://www.some.com/thing/">link</a>
			<a href="http://www.some.com/thing?test=5&a=5">http://www.some.com/thing?test=5&a=5</a>
			<a href="https://www.some.com/thing?test=5&a=5">https://www.some.com/thing?test=5&a=5</a>
			<a href="http://www.some.com/thing/">http://www.some.com/thing/</a>
			<a href="https://www.some.com/thing/">https://www.some.com/thing/</a>
			<!-- hash -->
			<a href="http://www.some.com#hash">link</a>
			<a href="http://www.some.com/#">link</a>
			<a href="http://www.some.com/#hash">link</a>
			<a href="http://www.some.com/path/#hash">link</a>
			<a href="http://www.some.com/index.html#hash">link</a>
			<a href="http://www.some.com/#hash/path">link</a>
			<a href="http://www.some.com/#/path">link</a>
			<a href="http://www.some.com/#!path">link</a>
			<a href="http://www.some.com/?query=foo#hash">link</a>
			<a href="http://www.some.com/?query=o&#039;">link</a>
			<a href="http://www.some.com/some.com#hash">link</a>
			<a href="http://www.some.com/path/some.com#hash">link</a>
			<!-- \hash -->
			';

		$expected = '
			<a href="http://sub.domain.com/some.com/%23hash">link</a>
			<a href="http://sub.domain.com/some.com/">link</a>
			<a href="http://sub.domain.com/some.com/thing%3Ftest%3D5%26a%3D5">link</a>
			<a href="http://sub.domain.com/s/some.com/thing%3Ftest%3D5%26a%3D5">link</a>
			<a href="http://sub.domain.com/some.com/thing%2F">link</a>
			<a href="http://sub.domain.com/s/some.com/thing%2F">link</a>
			<a href="http://sub.domain.com/some.com/thing%3Ftest%3D5%26a%3D5">http://www.some.com/thing?test=5&a=5</a>
			<a href="http://sub.domain.com/s/some.com/thing%3Ftest%3D5%26a%3D5">https://www.some.com/thing?test=5&a=5</a>
			<a href="http://sub.domain.com/some.com/thing%2F">http://www.some.com/thing/</a>
			<a href="http://sub.domain.com/s/some.com/thing%2F">https://www.some.com/thing/</a>
			<!-- hash -->
			<a href="http://sub.domain.com/some.com/%23hash">link</a>
			<a href="http://sub.domain.com/some.com/%23">link</a>
			<a href="http://sub.domain.com/some.com/%23hash">link</a>
			<a href="http://sub.domain.com/some.com/path%2F%23hash">link</a>
			<a href="http://sub.domain.com/some.com/index.html%23hash">link</a>
			<a href="http://sub.domain.com/some.com/%23hash%2Fpath">link</a>
			<a href="http://sub.domain.com/some.com/%23%2Fpath">link</a>
			<a href="http://sub.domain.com/some.com/%23%21path">link</a>
			<a href="http://sub.domain.com/some.com/%3Fquery%3Dfoo%23hash">link</a>
			<a href="http://sub.domain.com/some.com/%3Fquery%3Do%27">link</a>
			<a href="http://sub.domain.com/some.com/some.com%23hash">link</a>
			<a href="http://sub.domain.com/some.com/path%2Fsome.com%23hash">link</a>
			<!-- \hash -->
			';

		$this->assertEquals($expected, $surly->process($text));
	}

	public function testReplaceWithWwwwInSubdomain()
	{
		$surly = new Surly();
		$surly->setPanelHost('wwwebanything.subdomain.com');

		$text = '
			<a href="http://www.some.com/#hash">link</a>
			<a href="http://www.some.com/">link</a>
			<a href="http://www.some.com/thing?test=5&a=5">link</a>
			<a href="https://www.some.com/thing?test=5&a=5">link</a>
			<a href="http://www.some.com/thing/">link</a>
			<a href="https://www.some.com/thing/">link</a>
			<a href="http://www.some.com/thing?test=5&a=5">http://www.some.com/thing?test=5&a=5</a>
			<a href="https://www.some.com/thing?test=5&a=5">https://www.some.com/thing?test=5&a=5</a>
			<a href="http://www.some.com/thing/">http://www.some.com/thing/</a>
			<a href="https://www.some.com/thing/">https://www.some.com/thing/</a>
			';

		$expected = '
			<a href="http://wwwebanything.subdomain.com/some.com/%23hash">link</a>
			<a href="http://wwwebanything.subdomain.com/some.com/">link</a>
			<a href="http://wwwebanything.subdomain.com/some.com/thing%3Ftest%3D5%26a%3D5">link</a>
			<a href="http://wwwebanything.subdomain.com/s/some.com/thing%3Ftest%3D5%26a%3D5">link</a>
			<a href="http://wwwebanything.subdomain.com/some.com/thing%2F">link</a>
			<a href="http://wwwebanything.subdomain.com/s/some.com/thing%2F">link</a>
			<a href="http://wwwebanything.subdomain.com/some.com/thing%3Ftest%3D5%26a%3D5">http://www.some.com/thing?test=5&a=5</a>
			<a href="http://wwwebanything.subdomain.com/s/some.com/thing%3Ftest%3D5%26a%3D5">https://www.some.com/thing?test=5&a=5</a>
			<a href="http://wwwebanything.subdomain.com/some.com/thing%2F">http://www.some.com/thing/</a>
			<a href="http://wwwebanything.subdomain.com/s/some.com/thing%2F">https://www.some.com/thing/</a>
			';

		$this->assertEquals($expected, $surly->process($text));
	}	
	
	public function testDontReplacesPanelDomains()
	{
		$surly = new Surly();
		
		$text = '
			<a href="http://sur.ly/something.com">link</a>
			<a href="https://sur.ly/something.com">link</a>
			<a href="http://surdotly.com/something.com">link</a>
			<a href="https://surdotly.com/something.com">link</a>
			';
		
		$expected = '
			<a href="http://sur.ly/something.com">link</a>
			<a href="https://sur.ly/something.com">link</a>
			<a href="http://surdotly.com/something.com">link</a>
			<a href="https://surdotly.com/something.com">link</a>
			';
		
		$this->assertEquals($expected, $surly->process($text));
	}
	
	public function testDontReplacesSubomain()
	{
		$surly = new Surly();
		$surly->setPanelHost('sub.domain.com');

		$text = '
			<a href="http://sub.domain.com">link</a>
			<a href="http://sub.domain.com/index.html">link</a>
			';

		$expected = '
			<a href="http://sub.domain.com">link</a>
			<a href="http://sub.domain.com/index.html">link</a>
			';

		$this->assertEquals($expected, $surly->process($text));
	}

	public function testReplaceInvalidUrls()
	{
		$surly = new Surly();
		
		$text = '
			<a href="sdfgergerg">link</a>
			<a href="http://www.www.www">link</a>
			<a href="https://www.www.www">link</a>
			<a href="http://www.www.">link</a>
			<a href="https://www.www.">link</a>
			<a href="http://www.">link</a>
			<a href="https://www.">link</a>
			<a href="http://www">link</a>
			<a href="https://www">link</a>
			<a href="http://www-www">link</a>
			<a href="https://www-www">link</a>
			<a href="http://www-www.www">link</a>
			<a href="https://www-www.www">link</a>
			<a href="http://www.www-www.www">link</a>
			<a href="https://www.www-www.www">link</a>
			<a href="http://">link</a>
			<a href="https://">link</a>
			<a href="http://www.www.www">http://www.www.www</a>
			<a href="https://www.www.www">https://www.www.www</a>
			<a href="http://www.www.">http://www.www.</a>
			<a href="https://www.www.">https://www.www.</a>
			<a href="http://www.">http://www.</a>
			<a href="https://www.">https://www.</a>
			<a href="http://www">http://www</a>
			<a href="https://www">https://www</a>
			<a href="http://www-www">http://www-www</a>
			<a href="https://www-www">https://www-www</a>
			<a href="http://www-www.www">http://www-www.www</a>
			<a href="https://www-www.www">https://www-www.www</a>
			<a href="http://www.www-www.www">http://www.www-www.www</a>
			<a href="https://www.www-www.www">https://www.www-www.www</a>
			<a href="http://www.www-www.www/">http://www.www-www.www/</a>
			<a href="https://www.www-www.www/">https://www.www-www.www/</a>
			<a href="http://">http://</a>
			<a href="https://">https://</a>
			';
		
		$expected = '
			<a href="sdfgergerg">link</a>
			<a href="http://sur.ly/o/www.www/AA000015">link</a>
			<a href="https://sur.ly/o/www.www/AA000015">link</a>
			<a href="http://sur.ly/o/www./AA000015">link</a>
			<a href="https://sur.ly/o/www./AA000015">link</a>
			<a href="http://www.">link</a>
			<a href="https://www.">link</a>
			<a href="http://www">link</a>
			<a href="https://www">link</a>
			<a href="http://www-www">link</a>
			<a href="https://www-www">link</a>
			<a href="http://sur.ly/o/www-www.www/AA000015">link</a>
			<a href="https://sur.ly/o/www-www.www/AA000015">link</a>
			<a href="http://sur.ly/o/www-www.www/AA000015">link</a>
			<a href="https://sur.ly/o/www-www.www/AA000015">link</a>
			<a href="http://">link</a>
			<a href="https://">link</a>
			<a href="http://sur.ly/o/www.www/AA000015">http://www.www.www</a>
			<a href="https://sur.ly/o/www.www/AA000015">https://www.www.www</a>
			<a href="http://sur.ly/o/www./AA000015">http://www.www.</a>
			<a href="https://sur.ly/o/www./AA000015">https://www.www.</a>
			<a href="http://www.">http://www.</a>
			<a href="https://www.">https://www.</a>
			<a href="http://www">http://www</a>
			<a href="https://www">https://www</a>
			<a href="http://www-www">http://www-www</a>
			<a href="https://www-www">https://www-www</a>
			<a href="http://sur.ly/o/www-www.www/AA000015">http://www-www.www</a>
			<a href="https://sur.ly/o/www-www.www/AA000015">https://www-www.www</a>
			<a href="http://sur.ly/o/www-www.www/AA000015">http://www.www-www.www</a>
			<a href="https://sur.ly/o/www-www.www/AA000015">https://www.www-www.www</a>
			<a href="http://sur.ly/o/www-www.www/AA000015">http://www.www-www.www/</a>
			<a href="https://sur.ly/o/www-www.www/AA000015">https://www.www-www.www/</a>
			<a href="http://">http://</a>
			<a href="https://">https://</a>
			';

		$this->assertEquals($expected, $surly->process($text));
	}
	
	public function testReplaceInvalidUrlsWithToolbarId()
	{
		$surly = new Surly('AA00130');
		
		$text = '
			<a href="sdfgergerg">link</a>
			<a href="http://www.www.www">link</a>
			<a href="https://www.www.www">link</a>
			<a href="http://www.www.">link</a>
			<a href="https://www.www.">link</a>
			<a href="http://www.">link</a>
			<a href="https://www.">link</a>
			<a href="http://www">link</a>
			<a href="https://www">link</a>
			<a href="http://www-www">link</a>
			<a href="https://www-www">link</a>
			<a href="http://www-www.www">link</a>
			<a href="https://www-www.www">link</a>
			<a href="http://www.www-www.www">link</a>
			<a href="https://www.www-www.www">link</a>
			<a href="http://">link</a>
			<a href="https://">link</a>
			<a href="http://www.www.www">http://www.www.www</a>
			<a href="https://www.www.www">https://www.www.www</a>
			<a href="http://www.www.">http://www.www.</a>
			<a href="https://www.www.">https://www.www.</a>
			<a href="http://www.">http://www.</a>
			<a href="https://www.">https://www.</a>
			<a href="http://www">http://www</a>
			<a href="https://www">https://www</a>
			<a href="http://www-www">http://www-www</a>
			<a href="https://www-www">https://www-www</a>
			<a href="http://www-www.www">http://www-www.www</a>
			<a href="https://www-www.www">https://www-www.www</a>
			<a href="http://www.www-www.www">http://www.www-www.www</a>
			<a href="https://www.www-www.www">https://www.www-www.www</a>
			<a href="http://www.www-www.www/">http://www.www-www.www/</a>
			<a href="https://www.www-www.www/">https://www.www-www.www/</a>
			<a href="http://">http://</a>
			<a href="https://">https://</a>
			';
		
		$expected = '
			<a href="sdfgergerg">link</a>
			<a href="http://sur.ly/o/www.www/AA00130">link</a>
			<a href="https://sur.ly/o/www.www/AA00130">link</a>
			<a href="http://sur.ly/o/www./AA00130">link</a>
			<a href="https://sur.ly/o/www./AA00130">link</a>
			<a href="http://www.">link</a>
			<a href="https://www.">link</a>
			<a href="http://www">link</a>
			<a href="https://www">link</a>
			<a href="http://www-www">link</a>
			<a href="https://www-www">link</a>
			<a href="http://sur.ly/o/www-www.www/AA00130">link</a>
			<a href="https://sur.ly/o/www-www.www/AA00130">link</a>
			<a href="http://sur.ly/o/www-www.www/AA00130">link</a>
			<a href="https://sur.ly/o/www-www.www/AA00130">link</a>
			<a href="http://">link</a>
			<a href="https://">link</a>
			<a href="http://sur.ly/o/www.www/AA00130">http://www.www.www</a>
			<a href="https://sur.ly/o/www.www/AA00130">https://www.www.www</a>
			<a href="http://sur.ly/o/www./AA00130">http://www.www.</a>
			<a href="https://sur.ly/o/www./AA00130">https://www.www.</a>
			<a href="http://www.">http://www.</a>
			<a href="https://www.">https://www.</a>
			<a href="http://www">http://www</a>
			<a href="https://www">https://www</a>
			<a href="http://www-www">http://www-www</a>
			<a href="https://www-www">https://www-www</a>
			<a href="http://sur.ly/o/www-www.www/AA00130">http://www-www.www</a>
			<a href="https://sur.ly/o/www-www.www/AA00130">https://www-www.www</a>
			<a href="http://sur.ly/o/www-www.www/AA00130">http://www.www-www.www</a>
			<a href="https://sur.ly/o/www-www.www/AA00130">https://www.www-www.www</a>
			<a href="http://sur.ly/o/www-www.www/AA00130">http://www.www-www.www/</a>
			<a href="https://sur.ly/o/www-www.www/AA00130">https://www.www-www.www/</a>
			<a href="http://">http://</a>
			<a href="https://">https://</a>
			';
		
		$this->assertEquals($expected, $surly->process($text));
	}

	public function testDontReplaceRelativeLinks()
	{
		$surly = new Surly();
		
		$text = '
			<a href="/somepage">link</a>
			<a href="/index.php?q=node/3">link</a>
			';
		
		$this->assertEquals($text, $surly->process($text));
	}
	
	public function testDomainWhitelist()
	{
		$surly = new Surly();
		$surly->whitelist('lE.com');
		
		$text = '
			<a href="http://le.com">link</a>
			<a href="https://le.com">link</a>
			<a href="http://le.com#something">link</a>
			<a href="https://le.com#something">link</a>
			<a href="http://lE.cOm">link</a>
			<a href="https://lE.cOm">link</a>
			<a href="http://sub.le.com">link</a>
			<a href="https://sub.le.com">link</a>
			<a href="http://www.le.com">link</a>
			<a href="https://www.le.com">link</a>
			<a href="http://le.com/something">link</a>
			<a href="https://le.com/something">link</a>
			<a href="http://www.le.com/something">link</a>
			<a href="https://www.le.com/something">link</a>
			<a href="http://www.google.com/something">link</a>
			<a href="https://www.google.com/something">link</a>
			<a href="http://google.com/something">link</a>
			<a href="https://google.com/something">link</a>
			<a href="http://www.google.com">link</a>
			<a href="https://www.google.com">link</a>
			<a href="http://google.com">link</a>
			<a href="https://google.com">link</a>
			';
		
		$expected = '
			<a href="http://le.com">link</a>
			<a href="https://le.com">link</a>
			<a href="http://le.com#something">link</a>
			<a href="https://le.com#something">link</a>
			<a href="http://lE.cOm">link</a>
			<a href="https://lE.cOm">link</a>
			<a href="http://sub.le.com">link</a>
			<a href="https://sub.le.com">link</a>
			<a href="http://www.le.com">link</a>
			<a href="https://www.le.com">link</a>
			<a href="http://le.com/something">link</a>
			<a href="https://le.com/something">link</a>
			<a href="http://www.le.com/something">link</a>
			<a href="https://www.le.com/something">link</a>
			<a href="http://sur.ly/o/google.com/something/AA000015">link</a>
			<a href="https://sur.ly/o/google.com/something/AA000015">link</a>
			<a href="http://sur.ly/o/google.com/something/AA000015">link</a>
			<a href="https://sur.ly/o/google.com/something/AA000015">link</a>
			<a href="http://sur.ly/o/google.com/AA000015">link</a>
			<a href="https://sur.ly/o/google.com/AA000015">link</a>
			<a href="http://sur.ly/o/google.com/AA000015">link</a>
			<a href="https://sur.ly/o/google.com/AA000015">link</a>
			';
		
		$this->assertEquals($expected, $surly->process($text));		
		
		$surly = new Surly();
		$surly->whitelist('www.le.com');
		$this->assertEquals($expected, $surly->process($text));
	}
	
	public function testDomainWhitelistWithHttp()
	{
		$surly = new Surly();
		$surly->whitelist('http://test.com');
		
		$text = '<a href="http://test.com">test</a>';
		$expected = '<a href="http://test.com">test</a>';
		
		$this->assertEquals($expected, $surly->process($text));
	}
	
	public function testDomainWhitelistWithHttps()
	{
		$surly = new Surly();
		$surly->whitelist('http://test.com');
		
		$text = '<a href="https://test.com">test</a>';
		$expected = '<a href="https://test.com">test</a>';
		
		$this->assertEquals($expected, $surly->process($text));
	}

	public function testDomainWhitelistWithWwwwInSubdomain()
	{
		$surly = new Surly();
		$surly->whitelist('http://wwwebanything.subdomain.com');

		$text = '<a href="http://wwwebanything.subdomain.com">test</a>';
		$expected = '<a href="http://wwwebanything.subdomain.com">test</a>';

		$this->assertEquals($expected, $surly->process($text));
	}

	public function testDomainWhitelistWithWwwwInDomainName()
	{
		$surly = new Surly();
		$surly->whitelist('http://wwwebsite.com');

		$text = '<a href="http://wwwebsite.com">test</a>';
		$expected = '<a href="http://wwwebsite.com">test</a>';

		$this->assertEquals($expected, $surly->process($text));
	}

	public function testProcessUrl()
	{
		$url2expected = array(
			'https://www.cnn.com' => 'https://sur.ly/o/cnn.com/AA000015',
			'https://cnn.com' => 'https://sur.ly/o/cnn.com/AA000015',
			'http://www.cnn.com' => 'http://sur.ly/o/cnn.com/AA000015',
			'http://cnn.com' => 'http://sur.ly/o/cnn.com/AA000015',

			'https://www.cnn.com/' => 'https://sur.ly/o/cnn.com/AA000015',
			'https://cnn.com/' => 'https://sur.ly/o/cnn.com/AA000015',
			'http://www.cnn.com/' => 'http://sur.ly/o/cnn.com/AA000015',
			'http://cnn.com/' => 'http://sur.ly/o/cnn.com/AA000015',

			'https://www.cnn.com/new?ffd' => 'https://sur.ly/o/cnn.com/new%3Fffd/AA000015',
			'https://cnn.com/new?ffd' => 'https://sur.ly/o/cnn.com/new%3Fffd/AA000015',
			'http://www.cnn.com/new?ffd' => 'http://sur.ly/o/cnn.com/new%3Fffd/AA000015',
			'http://cnn.com/new?ffd' => 'http://sur.ly/o/cnn.com/new%3Fffd/AA000015',

			'https://www.cnn.com/new/ss?ffd' => 'https://sur.ly/o/cnn.com/new%2Fss%3Fffd/AA000015',
			'https://cnn.com/new/ss?ffd' => 'https://sur.ly/o/cnn.com/new%2Fss%3Fffd/AA000015',
			'http://www.cnn.com/new/ss?ffd' => 'http://sur.ly/o/cnn.com/new%2Fss%3Fffd/AA000015',
			'http://cnn.com/new/ss?ffd' => 'http://sur.ly/o/cnn.com/new%2Fss%3Fffd/AA000015',

			'http://www.' => 'http://www.',
			'https://www.' => 'https://www.',
			'http://www-www' => 'http://www-www',
			'https://www-www' => 'https://www-www',
			'http://www-www.www' => 'http://sur.ly/o/www-www.www/AA000015',
			'https://www-www.www' => 'https://sur.ly/o/www-www.www/AA000015',
			'http://www.www-www.www' => 'http://sur.ly/o/www-www.www/AA000015',
			'https://www.www-www.www' => 'https://sur.ly/o/www-www.www/AA000015',
			'http://www' => 'http://www',
			'https://www' => 'https://www',
			
			'http://www1.cnn.com' => 'http://sur.ly/o/www1.cnn.com/AA000015',
			'https://www1.cnn.com' => 'https://sur.ly/o/www1.cnn.com/AA000015',

			'cnn.com' => 'cnn.com',
			'www.cnn.com' => 'www.cnn.com',

			'http://www.some.com#hash' => 'http://sur.ly/o/some.com/%23hash/AA000015',
			'http://www.some.com/#' => 'http://sur.ly/o/some.com/%23/AA000015',
			'http://www.some.com/#hash' => 'http://sur.ly/o/some.com/%23hash/AA000015',
			'http://www.some.com/path/#hash' => 'http://sur.ly/o/some.com/path%2F%23hash/AA000015',
			'http://www.some.com/index.html#hash' => 'http://sur.ly/o/some.com/index.html%23hash/AA000015',
			'http://www.some.com/#hash/path' => 'http://sur.ly/o/some.com/%23hash%2Fpath/AA000015',
			'http://www.some.com/#/path' => 'http://sur.ly/o/some.com/%23%2Fpath/AA000015',
			'http://www.some.com/#!path' => 'http://sur.ly/o/some.com/%23%21path/AA000015',
			'http://www.some.com/?query=foo#hash' => 'http://sur.ly/o/some.com/%3Fquery%3Dfoo%23hash/AA000015',
			'http://www.some.com/some.com#hash' => 'http://sur.ly/o/some.com/some.com%23hash/AA000015',
			'http://www.some.com/path/some.com#hash' => 'http://sur.ly/o/some.com/path%2Fsome.com%23hash/AA000015',
		);

		$surly = new Surly();

		foreach ($url2expected as $url => $encodedUrl) {
			$this->assertEquals($encodedUrl, $surly->processUrl($url));
		}
	}

	public function testSetPanelHost()
	{
		$url2expected = array(
			'sur.ly/o/cnn.com/aa000015' => 'https://sur.ly/o/cnn.com/AA000015',
			'sur.ly/o/ibm.com/aa000015' => 'http://sur.ly/o/ibm.com/AA000015',
			'ibm.com/developerworks' => 'http://www.ibm.com/developerworks',

			'wwwebanything.subdomain.com/qwe' => 'http://wwwebanything.subdomain.com/qwe',
			'wwwebsite.com/qwe' => 'http://wwwebsite.com/qwe',

		);

		$surly = new Surly();

		foreach ($url2expected as $encodedUrl => $url) {
			$this->assertEquals($encodedUrl, $surly->setPanelHost($url)->panelHost);
		}
	}

	public function testProcessUrlToolbarId()
	{
		$url2expected = array(
			'https://www.cnn.com' => 'https://sur.ly/o/cnn.com/AA000150',
			'https://cnn.com' => 'https://sur.ly/o/cnn.com/AA000150',
			'http://www.cnn.com' => 'http://sur.ly/o/cnn.com/AA000150',
			'http://cnn.com' => 'http://sur.ly/o/cnn.com/AA000150',

			'https://www.cnn.com/' => 'https://sur.ly/o/cnn.com/AA000150',
			'https://cnn.com/' => 'https://sur.ly/o/cnn.com/AA000150',
			'http://www.cnn.com/' => 'http://sur.ly/o/cnn.com/AA000150',
			'http://cnn.com/' => 'http://sur.ly/o/cnn.com/AA000150',

			'https://www.cnn.com/new?ffd' => 'https://sur.ly/o/cnn.com/new%3Fffd/AA000150',
			'https://cnn.com/new?ffd' => 'https://sur.ly/o/cnn.com/new%3Fffd/AA000150',
			'http://www.cnn.com/new?ffd' => 'http://sur.ly/o/cnn.com/new%3Fffd/AA000150',
			'http://cnn.com/new?ffd' => 'http://sur.ly/o/cnn.com/new%3Fffd/AA000150',

			'https://www.cnn.com/new/ss?ffd' => 'https://sur.ly/o/cnn.com/new%2Fss%3Fffd/AA000150',
			'https://cnn.com/new/ss?ffd' => 'https://sur.ly/o/cnn.com/new%2Fss%3Fffd/AA000150',
			'http://www.cnn.com/new/ss?ffd' => 'http://sur.ly/o/cnn.com/new%2Fss%3Fffd/AA000150',
			'http://cnn.com/new/ss?ffd' => 'http://sur.ly/o/cnn.com/new%2Fss%3Fffd/AA000150',

			'http://www.' => 'http://www.',
			'https://www.' => 'https://www.',
			'http://www-www' => 'http://www-www',
			'https://www-www' => 'https://www-www',
			'http://www-www.www' => 'http://sur.ly/o/www-www.www/AA000150',
			'https://www-www.www' => 'https://sur.ly/o/www-www.www/AA000150',
			'http://www.www-www.www' => 'http://sur.ly/o/www-www.www/AA000150',
			'https://www.www-www.www' => 'https://sur.ly/o/www-www.www/AA000150',
			'http://www' => 'http://www',
			'https://www' => 'https://www',

			'cnn.com' => 'cnn.com',
			'www.cnn.com' => 'www.cnn.com',
		);

		$surly = new Surly('AA000150');

		foreach ($url2expected as $url => $encodedUrl) {
			$this->assertEquals($encodedUrl, $surly->processUrl($url));
		}
	}

	public function testSubdomainProcessUrl()
	{
		$url2expected = array(
			'https://www.cnn.com' => 'http://sub.domain.com/s/cnn.com/',
			'https://cnn.com' => 'http://sub.domain.com/s/cnn.com/',
			'http://www.cnn.com' => 'http://sub.domain.com/cnn.com/',
			'http://cnn.com' => 'http://sub.domain.com/cnn.com/',

			'https://www.cnn.com/' => 'http://sub.domain.com/s/cnn.com/',
			'https://cnn.com/' => 'http://sub.domain.com/s/cnn.com/',
			'http://www.cnn.com/' => 'http://sub.domain.com/cnn.com/',
			'http://cnn.com/' => 'http://sub.domain.com/cnn.com/',

			'https://www.cnn.com/new?ffd' => 'http://sub.domain.com/s/cnn.com/new%3Fffd',
			'https://cnn.com/new?ffd' => 'http://sub.domain.com/s/cnn.com/new%3Fffd',
			'http://www.cnn.com/new?ffd' => 'http://sub.domain.com/cnn.com/new%3Fffd',
			'http://cnn.com/new?ffd' => 'http://sub.domain.com/cnn.com/new%3Fffd',

			'https://www.cnn.com/new/ss?ffd' => 'http://sub.domain.com/s/cnn.com/new%2Fss%3Fffd',
			'https://cnn.com/new/ss?ffd' => 'http://sub.domain.com/s/cnn.com/new%2Fss%3Fffd',
			'http://www.cnn.com/new/ss?ffd' => 'http://sub.domain.com/cnn.com/new%2Fss%3Fffd',
			'http://cnn.com/new/ss?ffd' => 'http://sub.domain.com/cnn.com/new%2Fss%3Fffd',

			'http://www.' => 'http://www.',
			'https://www.' => 'https://www.',
			'http://www-www' => 'http://www-www',
			'https://www-www' => 'https://www-www',
			'http://www-www.www' => 'http://sub.domain.com/www-www.www/',
			'https://www-www.www' => 'http://sub.domain.com/s/www-www.www/',
			'http://www.www-www.www' => 'http://sub.domain.com/www-www.www/',
			'https://www.www-www.www' => 'http://sub.domain.com/s/www-www.www/',
			'http://www' => 'http://www',
			'https://www' => 'https://www',

			'http://www1.cnn.com' => 'http://sub.domain.com/www1.cnn.com/',
			'https://www1.cnn.com' => 'http://sub.domain.com/s/www1.cnn.com/',

			'cnn.com' => 'cnn.com',
			'www.cnn.com' => 'www.cnn.com',

			'http://www.some.com#hash' => 'http://sub.domain.com/some.com/%23hash',
			'http://www.some.com/#' => 'http://sub.domain.com/some.com/%23',
			'http://www.some.com/#hash' => 'http://sub.domain.com/some.com/%23hash',
			'http://www.some.com/path/#hash' => 'http://sub.domain.com/some.com/path%2F%23hash',
			'http://www.some.com/index.html#hash' => 'http://sub.domain.com/some.com/index.html%23hash',
			'http://www.some.com/#hash/path' => 'http://sub.domain.com/some.com/%23hash%2Fpath',
			'http://www.some.com/#/path' => 'http://sub.domain.com/some.com/%23%2Fpath',
			'http://www.some.com/#!path' => 'http://sub.domain.com/some.com/%23%21path',
			'http://www.some.com/?query=foo#hash' => 'http://sub.domain.com/some.com/%3Fquery%3Dfoo%23hash',
			'http://www.some.com/some.com#hash' => 'http://sub.domain.com/some.com/some.com%23hash',
			'http://www.some.com/path/some.com#hash' => 'http://sub.domain.com/some.com/path%2Fsome.com%23hash',
		);

		$surly = new Surly();
		$surly->setPanelHost('http://www.sub.domain.com');

		foreach ($url2expected as $url => $encodedUrl) {
			$this->assertEquals($encodedUrl, $surly->processUrl($url));
		}
	}

	public function testProcessUrlWithWwwwInSubdomain()
	{
		$url2expected = array(
			'https://www.cnn.com' => array('http://wwwebanything.subdomian.com/s/cnn.com/', 'http://wwwebsite.com/s/cnn.com/'),
			'https://cnn.com' => array('http://wwwebanything.subdomian.com/s/cnn.com/', 'http://wwwebsite.com/s/cnn.com/'),
			'http://www.cnn.com' => array('http://wwwebanything.subdomian.com/cnn.com/', 'http://wwwebsite.com/cnn.com/'),
			'http://cnn.com' => array('http://wwwebanything.subdomian.com/cnn.com/', 'http://wwwebsite.com/cnn.com/'),

			'https://www.cnn.com/' => array('http://wwwebanything.subdomian.com/s/cnn.com/', 'http://wwwebsite.com/s/cnn.com/'),
			'https://cnn.com/' => array('http://wwwebanything.subdomian.com/s/cnn.com/', 'http://wwwebsite.com/s/cnn.com/'),
			'http://www.cnn.com/' => array('http://wwwebanything.subdomian.com/cnn.com/', 'http://wwwebsite.com/cnn.com/'),
			'http://cnn.com/' => array('http://wwwebanything.subdomian.com/cnn.com/', 'http://wwwebsite.com/cnn.com/'),

			'https://www.cnn.com/new?ffd' => array('http://wwwebanything.subdomian.com/s/cnn.com/new%3Fffd', 'http://wwwebsite.com/s/cnn.com/new%3Fffd'),
			'https://cnn.com/new?ffd' => array('http://wwwebanything.subdomian.com/s/cnn.com/new%3Fffd', 'http://wwwebsite.com/s/cnn.com/new%3Fffd'),
			'http://www.cnn.com/new?ffd' => array('http://wwwebanything.subdomian.com/cnn.com/new%3Fffd', 'http://wwwebsite.com/cnn.com/new%3Fffd'),
			'http://cnn.com/new?ffd' => array('http://wwwebanything.subdomian.com/cnn.com/new%3Fffd', 'http://wwwebsite.com/cnn.com/new%3Fffd'),

			'https://www.cnn.com/new/ss?ffd' => array('http://wwwebanything.subdomian.com/s/cnn.com/new%2Fss%3Fffd', 'http://wwwebsite.com/s/cnn.com/new%2Fss%3Fffd'),
			'https://cnn.com/new/ss?ffd' => array('http://wwwebanything.subdomian.com/s/cnn.com/new%2Fss%3Fffd', 'http://wwwebsite.com/s/cnn.com/new%2Fss%3Fffd'),
			'http://www.cnn.com/new/ss?ffd' => array('http://wwwebanything.subdomian.com/cnn.com/new%2Fss%3Fffd', 'http://wwwebsite.com/cnn.com/new%2Fss%3Fffd'),
			'http://cnn.com/new/ss?ffd' => array('http://wwwebanything.subdomian.com/cnn.com/new%2Fss%3Fffd', 'http://wwwebsite.com/cnn.com/new%2Fss%3Fffd'),

			'http://www.' => array('http://www.', 'http://www.'),
			'https://www.' => array('https://www.', 'https://www.'),
			'http://www-www' => array('http://www-www', 'http://www-www'),
			'https://www-www' => array('https://www-www', 'https://www-www'),
			'http://www-www.www' => array('http://wwwebanything.subdomian.com/www-www.www/', 'http://wwwebsite.com/www-www.www/'),
			'https://www-www.www' => array('http://wwwebanything.subdomian.com/s/www-www.www/', 'http://wwwebsite.com/s/www-www.www/'),
			'http://www.www-www.www' => array('http://wwwebanything.subdomian.com/www-www.www/', 'http://wwwebsite.com/www-www.www/'),
			'https://www.www-www.www' => array('http://wwwebanything.subdomian.com/s/www-www.www/', 'http://wwwebsite.com/s/www-www.www/'),
			'http://www' => array('http://www', 'http://www'),
			'https://www' => array('https://www', 'https://www'),

			'http://www1.cnn.com' => array('http://wwwebanything.subdomian.com/www1.cnn.com/', 'http://wwwebsite.com/www1.cnn.com/'),
			'https://www1.cnn.com' => array('http://wwwebanything.subdomian.com/s/www1.cnn.com/', 'http://wwwebsite.com/s/www1.cnn.com/'),

			'cnn.com' => array('cnn.com', 'cnn.com'),
			'www.cnn.com' => array('www.cnn.com', 'www.cnn.com'),
		);

		$surly = new Surly();
		$surly->setPanelHost('http://wwwebanything.subdomian.com');

		foreach ($url2expected as $url => $encodedUrl) {
			$this->assertEquals($encodedUrl[0], $surly->processUrl($url));
		}

		$surly = new Surly();
		$surly->setPanelHost('http://wwwebsite.com');

		foreach ($url2expected as $url => $encodedUrl) {
			$this->assertEquals($encodedUrl[1], $surly->processUrl($url));
		}
	}

	public function testSubdomainProcessUrlToolbarId()
	{
		$url2expected = array(
			'https://www.cnn.com' => 'http://sub.domain.com/s/cnn.com/',
			'https://cnn.com' => 'http://sub.domain.com/s/cnn.com/',
			'http://www.cnn.com' => 'http://sub.domain.com/cnn.com/',
			'http://cnn.com' => 'http://sub.domain.com/cnn.com/',

			'https://www.cnn.com/' => 'http://sub.domain.com/s/cnn.com/',
			'https://cnn.com/' => 'http://sub.domain.com/s/cnn.com/',
			'http://www.cnn.com/' => 'http://sub.domain.com/cnn.com/',
			'http://cnn.com/' => 'http://sub.domain.com/cnn.com/',

			'https://www.cnn.com/new?ffd' => 'http://sub.domain.com/s/cnn.com/new%3Fffd',
			'https://cnn.com/new?ffd' => 'http://sub.domain.com/s/cnn.com/new%3Fffd',
			'http://www.cnn.com/new?ffd' => 'http://sub.domain.com/cnn.com/new%3Fffd',
			'http://cnn.com/new?ffd' => 'http://sub.domain.com/cnn.com/new%3Fffd',

			'https://www.cnn.com/new/ss?ffd' => 'http://sub.domain.com/s/cnn.com/new%2Fss%3Fffd',
			'https://cnn.com/new/ss?ffd' => 'http://sub.domain.com/s/cnn.com/new%2Fss%3Fffd',
			'http://www.cnn.com/new/ss?ffd' => 'http://sub.domain.com/cnn.com/new%2Fss%3Fffd',
			'http://cnn.com/new/ss?ffd' => 'http://sub.domain.com/cnn.com/new%2Fss%3Fffd',

			'http://www.' => 'http://www.',
			'https://www.' => 'https://www.',
			'http://www-www' => 'http://www-www',
			'https://www-www' => 'https://www-www',
			'http://www-www.www' => 'http://sub.domain.com/www-www.www/',
			'https://www-www.www' => 'http://sub.domain.com/s/www-www.www/',
			'http://www.www-www.www' => 'http://sub.domain.com/www-www.www/',
			'https://www.www-www.www' => 'http://sub.domain.com/s/www-www.www/',
			'http://www' => 'http://www',
			'https://www' => 'https://www',

			'cnn.com' => 'cnn.com',
			'www.cnn.com' => 'www.cnn.com',
		);

		$surly = new Surly('AA000150');
		$surly->setPanelHost('https://sub.domain.com');

		foreach ($url2expected as $url => $encodedUrl) {
			$this->assertEquals($encodedUrl, $surly->processUrl($url));
		}
	}

	public function testProcessMultipleUrls()
	{
		$url2expected = array(
			'https://www.cnn.com' => 'https://sur.ly/o/cnn.com/AA000015',
			'https://cnn.com' => 'https://sur.ly/o/cnn.com/AA000015',
			'http://www.cnn.com' => 'http://sur.ly/o/cnn.com/AA000015',
			'http://cnn.com' => 'http://sur.ly/o/cnn.com/AA000015',

			'https://www.cnn.com/' => 'https://sur.ly/o/cnn.com/AA000015',
			'https://cnn.com/' => 'https://sur.ly/o/cnn.com/AA000015',
			'http://www.cnn.com/' => 'http://sur.ly/o/cnn.com/AA000015',
			'http://cnn.com/' => 'http://sur.ly/o/cnn.com/AA000015',

			'https://www.cnn.com/new?ffd' => 'https://sur.ly/o/cnn.com/new%3Fffd/AA000015',
			'https://cnn.com/new?ffd' => 'https://sur.ly/o/cnn.com/new%3Fffd/AA000015',
			'http://www.cnn.com/new?ffd' => 'http://sur.ly/o/cnn.com/new%3Fffd/AA000015',
			'http://cnn.com/new?ffd' => 'http://sur.ly/o/cnn.com/new%3Fffd/AA000015',

			'https://www.cnn.com/new/ss?ffd' => 'https://sur.ly/o/cnn.com/new%2Fss%3Fffd/AA000015',
			'https://cnn.com/new/ss?ffd' => 'https://sur.ly/o/cnn.com/new%2Fss%3Fffd/AA000015',
			'http://www.cnn.com/new/ss?ffd' => 'http://sur.ly/o/cnn.com/new%2Fss%3Fffd/AA000015',
			'http://cnn.com/new/ss?ffd' => 'http://sur.ly/o/cnn.com/new%2Fss%3Fffd/AA000015',

			'http://www.' => 'http://www.',
			'https://www.' => 'https://www.',
			'http://www-www' => 'http://www-www',
			'https://www-www' => 'https://www-www',
			'http://www-www.www' => 'http://sur.ly/o/www-www.www/AA000015',
			'https://www-www.www' => 'https://sur.ly/o/www-www.www/AA000015',
			'http://www.www-www.www' => 'http://sur.ly/o/www-www.www/AA000015',
			'https://www.www-www.www' => 'https://sur.ly/o/www-www.www/AA000015',
			'http://www' => 'http://www',
			'https://www' => 'https://www',

			'cnn.com' => 'cnn.com',
			'www.cnn.com' => 'www.cnn.com',

			'http://www.some.com#hash' => 'http://sur.ly/o/some.com/%23hash/AA000015',
			'http://www.some.com/#' => 'http://sur.ly/o/some.com/%23/AA000015',
			'http://www.some.com/#hash' => 'http://sur.ly/o/some.com/%23hash/AA000015',
			'http://www.some.com/path/#hash' => 'http://sur.ly/o/some.com/path%2F%23hash/AA000015',
			'http://www.some.com/index.html#hash' => 'http://sur.ly/o/some.com/index.html%23hash/AA000015',
			'http://www.some.com/#hash/path' => 'http://sur.ly/o/some.com/%23hash%2Fpath/AA000015',
			'http://www.some.com/#/path' => 'http://sur.ly/o/some.com/%23%2Fpath/AA000015',
			'http://www.some.com/#!path' => 'http://sur.ly/o/some.com/%23%21path/AA000015',
			'http://www.some.com/?query=foo#hash' => 'http://sur.ly/o/some.com/%3Fquery%3Dfoo%23hash/AA000015',
			'http://www.some.com/some.com#hash' => 'http://sur.ly/o/some.com/some.com%23hash/AA000015',
			'http://www.some.com/path/some.com#hash' => 'http://sur.ly/o/some.com/path%2Fsome.com%23hash/AA000015',
		);

		$surly = new Surly();

		foreach ($url2expected as $url => $encodedUrl) {
			$this->assertEquals(array($encodedUrl), $surly->processMultipleUrls(array($url)));
		}
	}

	public function testProcessMultipleUrlsToolbarId()
	{
		$url2expected = array(
			'https://www.cnn.com' => 'https://sur.ly/o/cnn.com/AA000150',
			'https://cnn.com' => 'https://sur.ly/o/cnn.com/AA000150',
			'http://www.cnn.com' => 'http://sur.ly/o/cnn.com/AA000150',
			'http://cnn.com' => 'http://sur.ly/o/cnn.com/AA000150',

			'https://www.cnn.com/' => 'https://sur.ly/o/cnn.com/AA000150',
			'https://cnn.com/' => 'https://sur.ly/o/cnn.com/AA000150',
			'http://www.cnn.com/' => 'http://sur.ly/o/cnn.com/AA000150',
			'http://cnn.com/' => 'http://sur.ly/o/cnn.com/AA000150',

			'https://www.cnn.com/new?ffd' => 'https://sur.ly/o/cnn.com/new%3Fffd/AA000150',
			'https://cnn.com/new?ffd' => 'https://sur.ly/o/cnn.com/new%3Fffd/AA000150',
			'http://www.cnn.com/new?ffd' => 'http://sur.ly/o/cnn.com/new%3Fffd/AA000150',
			'http://cnn.com/new?ffd' => 'http://sur.ly/o/cnn.com/new%3Fffd/AA000150',

			'https://www.cnn.com/new/ss?ffd' => 'https://sur.ly/o/cnn.com/new%2Fss%3Fffd/AA000150',
			'https://cnn.com/new/ss?ffd' => 'https://sur.ly/o/cnn.com/new%2Fss%3Fffd/AA000150',
			'http://www.cnn.com/new/ss?ffd' => 'http://sur.ly/o/cnn.com/new%2Fss%3Fffd/AA000150',
			'http://cnn.com/new/ss?ffd' => 'http://sur.ly/o/cnn.com/new%2Fss%3Fffd/AA000150',

			'http://www.' => 'http://www.',
			'https://www.' => 'https://www.',
			'http://www-www' => 'http://www-www',
			'https://www-www' => 'https://www-www',
			'http://www-www.www' => 'http://sur.ly/o/www-www.www/AA000150',
			'https://www-www.www' => 'https://sur.ly/o/www-www.www/AA000150',
			'http://www.www-www.www' => 'http://sur.ly/o/www-www.www/AA000150',
			'https://www.www-www.www' => 'https://sur.ly/o/www-www.www/AA000150',
			'http://www' => 'http://www',
			'https://www' => 'https://www',

			'cnn.com' => 'cnn.com',
			'www.cnn.com' => 'www.cnn.com',
		);

		$surly = new Surly('AA000150');

		foreach ($url2expected as $url => $encodedUrl) {
			$this->assertEquals(array($encodedUrl), $surly->processMultipleUrls(array($url)));
		}
	}

	public function testSubdomainProcessMultipleUrls()
	{
		$url2expected = array(
			'https://www.cnn.com' => 'http://sub.domain.com/s/cnn.com/',
			'https://cnn.com' => 'http://sub.domain.com/s/cnn.com/',
			'http://www.cnn.com' => 'http://sub.domain.com/cnn.com/',
			'http://cnn.com' => 'http://sub.domain.com/cnn.com/',

			'https://www.cnn.com/' => 'http://sub.domain.com/s/cnn.com/',
			'https://cnn.com/' => 'http://sub.domain.com/s/cnn.com/',
			'http://www.cnn.com/' => 'http://sub.domain.com/cnn.com/',
			'http://cnn.com/' => 'http://sub.domain.com/cnn.com/',

			'https://www.cnn.com/new?ffd' => 'http://sub.domain.com/s/cnn.com/new%3Fffd',
			'https://cnn.com/new?ffd' => 'http://sub.domain.com/s/cnn.com/new%3Fffd',
			'http://www.cnn.com/new?ffd' => 'http://sub.domain.com/cnn.com/new%3Fffd',
			'http://cnn.com/new?ffd' => 'http://sub.domain.com/cnn.com/new%3Fffd',

			'https://www.cnn.com/new/ss?ffd' => 'http://sub.domain.com/s/cnn.com/new%2Fss%3Fffd',
			'https://cnn.com/new/ss?ffd' => 'http://sub.domain.com/s/cnn.com/new%2Fss%3Fffd',
			'http://www.cnn.com/new/ss?ffd' => 'http://sub.domain.com/cnn.com/new%2Fss%3Fffd',
			'http://cnn.com/new/ss?ffd' => 'http://sub.domain.com/cnn.com/new%2Fss%3Fffd',

			'http://www.' => 'http://www.',
			'https://www.' => 'https://www.',
			'http://www-www' => 'http://www-www',
			'https://www-www' => 'https://www-www',
			'http://www-www.www' => 'http://sub.domain.com/www-www.www/',
			'https://www-www.www' => 'http://sub.domain.com/s/www-www.www/',
			'http://www.www-www.www' => 'http://sub.domain.com/www-www.www/',
			'https://www.www-www.www' => 'http://sub.domain.com/s/www-www.www/',
			'http://www' => 'http://www',
			'https://www' => 'https://www',

			'cnn.com' => 'cnn.com',
			'www.cnn.com' => 'www.cnn.com',

			'http://www.some.com#hash' => 'http://sub.domain.com/some.com/%23hash',
			'http://www.some.com/#' => 'http://sub.domain.com/some.com/%23',
			'http://www.some.com/#hash' => 'http://sub.domain.com/some.com/%23hash',
			'http://www.some.com/path/#hash' => 'http://sub.domain.com/some.com/path%2F%23hash',
			'http://www.some.com/index.html#hash' => 'http://sub.domain.com/some.com/index.html%23hash',
			'http://www.some.com/#hash/path' => 'http://sub.domain.com/some.com/%23hash%2Fpath',
			'http://www.some.com/#/path' => 'http://sub.domain.com/some.com/%23%2Fpath',
			'http://www.some.com/#!path' => 'http://sub.domain.com/some.com/%23%21path',
			'http://www.some.com/?query=foo#hash' => 'http://sub.domain.com/some.com/%3Fquery%3Dfoo%23hash',
			'http://www.some.com/some.com#hash' => 'http://sub.domain.com/some.com/some.com%23hash',
			'http://www.some.com/path/some.com#hash' => 'http://sub.domain.com/some.com/path%2Fsome.com%23hash',
		);

		$surly = new Surly();
		$surly->setPanelHost('sub.domain.com');

		foreach ($url2expected as $url => $encodedUrl) {
			$this->assertEquals(array($encodedUrl), $surly->processMultipleUrls(array($url)));
		}
	}

	public function testProcessMultipleUrlsWithWwwwInSubdomain()
	{
		$url2expected = array(
			'https://www.cnn.com' => array('http://wwwebanything.subdomian.com/s/cnn.com/', 'http://wwwebsite.com/s/cnn.com/'),
			'https://cnn.com' => array('http://wwwebanything.subdomian.com/s/cnn.com/', 'http://wwwebsite.com/s/cnn.com/'),
			'http://www.cnn.com' => array('http://wwwebanything.subdomian.com/cnn.com/', 'http://wwwebsite.com/cnn.com/'),
			'http://cnn.com' => array('http://wwwebanything.subdomian.com/cnn.com/', 'http://wwwebsite.com/cnn.com/'),

			'https://www.cnn.com/' => array('http://wwwebanything.subdomian.com/s/cnn.com/', 'http://wwwebsite.com/s/cnn.com/'),
			'https://cnn.com/' => array('http://wwwebanything.subdomian.com/s/cnn.com/', 'http://wwwebsite.com/s/cnn.com/'),
			'http://www.cnn.com/' => array('http://wwwebanything.subdomian.com/cnn.com/', 'http://wwwebsite.com/cnn.com/'),
			'http://cnn.com/' => array('http://wwwebanything.subdomian.com/cnn.com/', 'http://wwwebsite.com/cnn.com/'),

			'https://www.cnn.com/new?ffd' => array('http://wwwebanything.subdomian.com/s/cnn.com/new%3Fffd', 'http://wwwebsite.com/s/cnn.com/new%3Fffd'),
			'https://cnn.com/new?ffd' => array('http://wwwebanything.subdomian.com/s/cnn.com/new%3Fffd', 'http://wwwebsite.com/s/cnn.com/new%3Fffd'),
			'http://www.cnn.com/new?ffd' => array('http://wwwebanything.subdomian.com/cnn.com/new%3Fffd', 'http://wwwebsite.com/cnn.com/new%3Fffd'),
			'http://cnn.com/new?ffd' => array('http://wwwebanything.subdomian.com/cnn.com/new%3Fffd', 'http://wwwebsite.com/cnn.com/new%3Fffd'),

			'https://www.cnn.com/new/ss?ffd' => array('http://wwwebanything.subdomian.com/s/cnn.com/new%2Fss%3Fffd', 'http://wwwebsite.com/s/cnn.com/new%2Fss%3Fffd'),
			'https://cnn.com/new/ss?ffd' => array('http://wwwebanything.subdomian.com/s/cnn.com/new%2Fss%3Fffd', 'http://wwwebsite.com/s/cnn.com/new%2Fss%3Fffd'),
			'http://www.cnn.com/new/ss?ffd' => array('http://wwwebanything.subdomian.com/cnn.com/new%2Fss%3Fffd', 'http://wwwebsite.com/cnn.com/new%2Fss%3Fffd'),
			'http://cnn.com/new/ss?ffd' => array('http://wwwebanything.subdomian.com/cnn.com/new%2Fss%3Fffd', 'http://wwwebsite.com/cnn.com/new%2Fss%3Fffd'),

			'http://www.' => array('http://www.', 'http://www.'),
			'https://www.' => array('https://www.', 'https://www.'),
			'http://www-www' => array('http://www-www', 'http://www-www'),
			'https://www-www' => array('https://www-www', 'https://www-www'),
			'http://www-www.www' => array('http://wwwebanything.subdomian.com/www-www.www/', 'http://wwwebsite.com/www-www.www/'),
			'https://www-www.www' => array('http://wwwebanything.subdomian.com/s/www-www.www/', 'http://wwwebsite.com/s/www-www.www/'),
			'http://www.www-www.www' => array('http://wwwebanything.subdomian.com/www-www.www/', 'http://wwwebsite.com/www-www.www/'),
			'https://www.www-www.www' => array('http://wwwebanything.subdomian.com/s/www-www.www/', 'http://wwwebsite.com/s/www-www.www/'),
			'http://www' => array('http://www', 'http://www'),
			'https://www' => array('https://www', 'https://www'),

			'cnn.com' => array('cnn.com', 'cnn.com'),
			'www.cnn.com' => array('www.cnn.com', 'www.cnn.com'),
		);

		$surly = new Surly();
		$surly->setPanelHost('wwwebanything.subdomian.com');

		foreach ($url2expected as $url => $encodedUrl) {
			$this->assertEquals(array($encodedUrl[0]), $surly->processMultipleUrls(array($url)));
		}

		$surly = new Surly();
		$surly->setPanelHost('wwwebsite.com');

		foreach ($url2expected as $url => $encodedUrl) {
			$this->assertEquals(array($encodedUrl[1]), $surly->processMultipleUrls(array($url)));
		}
	}

	public function testSubdomainProcessMultipleUrlsToolbarId()
	{
		$url2expected = array(
			'https://www.cnn.com' => 'http://sub.domain.com/s/cnn.com/',
			'https://cnn.com' => 'http://sub.domain.com/s/cnn.com/',
			'http://www.cnn.com' => 'http://sub.domain.com/cnn.com/',
			'http://cnn.com' => 'http://sub.domain.com/cnn.com/',

			'https://www.cnn.com/' => 'http://sub.domain.com/s/cnn.com/',
			'https://cnn.com/' => 'http://sub.domain.com/s/cnn.com/',
			'http://www.cnn.com/' => 'http://sub.domain.com/cnn.com/',
			'http://cnn.com/' => 'http://sub.domain.com/cnn.com/',

			'https://www.cnn.com/new?ffd' => 'http://sub.domain.com/s/cnn.com/new%3Fffd',
			'https://cnn.com/new?ffd' => 'http://sub.domain.com/s/cnn.com/new%3Fffd',
			'http://www.cnn.com/new?ffd' => 'http://sub.domain.com/cnn.com/new%3Fffd',
			'http://cnn.com/new?ffd' => 'http://sub.domain.com/cnn.com/new%3Fffd',

			'https://www.cnn.com/new/ss?ffd' => 'http://sub.domain.com/s/cnn.com/new%2Fss%3Fffd',
			'https://cnn.com/new/ss?ffd' => 'http://sub.domain.com/s/cnn.com/new%2Fss%3Fffd',
			'http://www.cnn.com/new/ss?ffd' => 'http://sub.domain.com/cnn.com/new%2Fss%3Fffd',
			'http://cnn.com/new/ss?ffd' => 'http://sub.domain.com/cnn.com/new%2Fss%3Fffd',

			'http://www.' => 'http://www.',
			'https://www.' => 'https://www.',
			'http://www-www' => 'http://www-www',
			'https://www-www' => 'https://www-www',
			'http://www-www.www' => 'http://sub.domain.com/www-www.www/',
			'https://www-www.www' => 'http://sub.domain.com/s/www-www.www/',
			'http://www.www-www.www' => 'http://sub.domain.com/www-www.www/',
			'https://www.www-www.www' => 'http://sub.domain.com/s/www-www.www/',
			'http://www' => 'http://www',
			'https://www' => 'https://www',

			'cnn.com' => 'cnn.com',
			'www.cnn.com' => 'www.cnn.com',
		);

		$surly = new Surly('AA000150');
		$surly->setPanelHost('sub.domain.com');

		foreach ($url2expected as $url => $encodedUrl) {
			$this->assertEquals(array($encodedUrl), $surly->processMultipleUrls(array($url)));
		}
	}

	public function testUrlEncode()
	{
		$url2EncodedUrl = array(
			'https://www.cnn.com' => 'cnn.com',
			'https://cnn.com' => 'cnn.com',
			'http://www.cnn.com' => 'cnn.com',
			'http://cnn.com' => 'cnn.com',
			'www.cnn.com' => 'cnn.com',
			'cnn.com' => 'cnn.com',

			'https://www.cnn.com/' => 'cnn.com',
			'https://cnn.com/' => 'cnn.com',
			'http://www.cnn.com/' => 'cnn.com',
			'http://cnn.com/' => 'cnn.com',
			'www.cnn.com/' => 'cnn.com',
			'cnn.com/' => 'cnn.com',

			'https://www.cnn.com/new?ffd' => 'cnn.com/new%3Fffd',
			'https://cnn.com/new?ffd' => 'cnn.com/new%3Fffd',
			'http://www.cnn.com/new?ffd' => 'cnn.com/new%3Fffd',
			'http://cnn.com/new?ffd' => 'cnn.com/new%3Fffd',
			'www.cnn.com/new?ffd' => 'cnn.com/new%3Fffd',
			'cnn.com/new?ffd' => 'cnn.com/new%3Fffd',

			'https://www.cnn.com/new/ss?ffd' => 'cnn.com/new%2Fss%3Fffd',
			'https://cnn.com/new/ss?ffd' => 'cnn.com/new%2Fss%3Fffd',
			'http://www.cnn.com/new/ss?ffd' => 'cnn.com/new%2Fss%3Fffd',
			'http://cnn.com/new/ss?ffd' => 'cnn.com/new%2Fss%3Fffd',
			'www.cnn.com/new/ss?ffd' => 'cnn.com/new%2Fss%3Fffd',
			'cnn.com/new/ss?ffd' => 'cnn.com/new%2Fss%3Fffd',

			'//////' => '%2F%2F%2F%2F%2F%2F',
			'http://www.' => 'www.',
			'https://www.' => 'www.',
		);
		$surly = new Surly();

		foreach ($url2EncodedUrl as $url => $encodedUrl) {
			$this->assertEquals($encodedUrl, $surly->_urlEncode($url));
		}
	}
}
