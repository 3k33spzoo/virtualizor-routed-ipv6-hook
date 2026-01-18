<?php
/**
 * @copyright 2026 3K33 sp. z o.o. d/b/a Strike.bz
 * @license   MIT https://opensource.org/license/mit
 *
 * Description:
 * This Virtualizor hook allows to integrate routed IPv6 setup on hypervisor's running Virtualizor software.
 * 
 * Disclaimer:
 * This software is provided "as is", without any warranty of any kind,
 * either expressed or implied, including but not limited to the warranties
 * of merchantability, fitness for a particular purpose, or non-infringement.
 * In no event shall 3K33 sp. z o.o. be liable for any claim, damages,
 * or other liability arising from, out of, or in connection with
 * the software or the use of this software.
 *
 * Trademark Notice:
 * Virtualizor is a registered trademark of Softaculous Ltd.
 * 
 */

function ipv6_to_subnet(string $ipv6, int $prefix): string {
    $bin = inet_pton($ipv6);

    if ($bin === false) {
        return '';
    }

    $bytes = unpack('C*', $bin);

    $bits = $prefix;
    foreach ($bytes as $i => $byte) {
        if ($bits >= 8) {
            $bits -= 8;
        } elseif ($bits > 0) {
            $mask = (0xFF << (8 - $bits)) & 0xFF;
            $bytes[$i] &= $mask;
            $bits = 0;
        } else {
            $bytes[$i] = 0;
        }
    }

    $net = pack('C*', ...$bytes);

    return inet_ntop($net) . '/' . $prefix;
}


function __after_startvps($vps) {
    $res = makequery(
        "SELECT i.*, ip.* 
         FROM ips i
         LEFT JOIN ippool ip ON ip.ippid = i.ippid
         WHERE i.vpsid = :vid
           AND i.ipv6 = 1",
        array(':vid' => $vps['vpsid'])
    );

    for($i=0; $i < vsql_num_rows($res); $i++) { 
        $ips[$i] = vsql_fetch_assoc($res);
    };

    if (isset($ips) && !empty($ips)) {
        foreach ($ips as $ip) {
            $ip_subnet = ipv6_to_subnet($ip['ip'], (int)$ip['ipr_netmask']);
            exec('ip -6 route replace ' . escapeshellarg($ip['ip'] . '/128') . ' dev viifbr0');
            exec('ip -6 route replace '  . escapeshellarg($ip_subnet) . ' via ' . escapeshellarg($ip['ip']) . ' dev viifbr0');
        };
    };
}
?>
