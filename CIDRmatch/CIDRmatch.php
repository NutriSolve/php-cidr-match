<?php
namespace CIDRmatch;

/** CIDR match
 * ================================================================================
 * IDRmatch is a library to match an IP to an IP range in CIDR notation (IPv4 and
 * IPv6).
 *  ================================================================================
 * @package     CIDRmatch
 * @author      Thomas Lutz
 * @copyright   Copyright (c) 2015 - present Thomas Lutz
 * @license     http://tholu.mit-license.org
 *  ================================================================================
 */

class CIDRmatch
{

    public function match($ip, $cidr)
    {
        list($subnet, $mask) = explode('/', $cidr);

        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            // it's valid
            $ipVersion = 'v4';
        } else {
            // it's not valid
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                // it's valid
                $ipVersion = 'v6';
            } else {
                // it's not valid
                return false;
            }
        }

        switch ($ipVersion) {
            case 'v4':
                return $this->IPv4Match($ip, $subnet, $mask);
                break;
            case 'v6':
                return $this->IPv6Match($ip, $subnet, $mask);
                break;
        }
    }

    // inspired by: http://stackoverflow.com/questions/7951061/matching-ipv6-address-to-a-cidr-subnet
    private function IPv6MaskToByteArray($subnetMask)
    {
        $addr = str_repeat("f", $subnetMask / 4);
        switch ($subnetMask % 4) {
            case 0:
                break;
            case 1:
                $addr .= "8";
                break;
            case 2:
                $addr .= "c";
                break;
            case 3:
                $addr .= "e";
                break;
        }
        $addr = str_pad($addr, 32, '0');
        $addr = pack("H*", $addr);

        return $addr;
    }

    // inspired by: http://stackoverflow.com/questions/7951061/matching-ipv6-address-to-a-cidr-subnet
    private function IPv6Match($address, $subnetAddress, $subnetMask)
    {
        $subnet = inet_pton($subnetAddress);
        $addr = inet_pton($address);

        $binMask = $this->IPv6MaskToByteArray($subnetMask);

        return ($addr & $binMask) == $subnet;
    }

    // inspired by: http://stackoverflow.com/questions/594112/matching-an-ip-to-a-cidr-mask-in-php5
    private function IPv4Match($address, $subnetAddress, $subnetMask)
    {
        if ((ip2long($address) & ~((1 << (32 - $subnetMask)) - 1)) == ip2long($subnetAddress)) {
            return true;
        }

        return false;
    }

}
