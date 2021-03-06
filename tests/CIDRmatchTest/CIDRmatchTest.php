<?php

use CIDRmatch\CIDRmatch;

/**
 * A suite of tests for the CIDRmatch class
 *
 * @author Thomas Lutz
 */
class CIDRmatchTest extends PHPUnit_Framework_TestCase
{
    /**
     * Ensure that IPv4 addresses work
     */
    public function testIPv4Match()
    {
        $cidrMatch = new CIDRmatch();
        $this->assertTrue($cidrMatch->match('104.132.31.99', '104.132.0.0/14'));
        $this->assertTrue($cidrMatch->match('74.125.60.99', '74.125.0.0/16'));

    }

    /**
     * Ensure that IPv6 addresses work
     */
    public function testIPv6Match()
    {
        $cidrMatch = new CIDRmatch();
        $this->assertTrue($cidrMatch->match('2001:0db8:85a3:08d3:1319:8a2e:0370:7347', '2001:0db8:85a3:08d3::/64'));
        $this->assertTrue($cidrMatch->match('2a00:1450:400c:c04::6a', '2a00:1450::/32'));
    }


}
