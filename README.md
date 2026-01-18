# Virtualizor IPv6 Routing Hook

**Copyright** 2026 3K33 sp. z o.o. d/b/a Strike.bz
**License:** MIT [https://opensource.org/license/mit](https://opensource.org/license/mit)

## Description

This Virtualizor hook allows you to integrate routed IPv6 setup on hypervisors running Virtualizor software.

## Disclaimer

This software is provided "as is", without any warranty of any kind,
either expressed or implied, including but not limited to the warranties
of merchantability, fitness for a particular purpose, or non-infringement.

In no event shall 3K33 sp. z o.o. be liable for any claim, damages,
or other liability arising from, out of, or in connection with
the software or the use of this software.

## Trademark Notice

Virtualizor is a registered trademark of Softaculous Ltd.

## Installation

1. Copy the hook file (after_startvps.php) into your Virtualizor hooks directory (`/usr/local/virtualizor/hooks/`) on all nodes where you want to enable routed IPv6.
2. Restart the VPS instances to apply the routed IPv6 configuration.

### Important Notes

- This script assumes that all IPv6 addresses assigned to VPS instances are **subnets**. Do **not** use this script if any IPv6 addresses are single IPs or otherwise not subnets, unless you modify it accordingly.
- It also assumes that a bridge exists and that its interface name is `viifbr0` (the default for Virtualizor). The script will **not work in NAT mode**.
- This script was developed specifically for our infrastructure. We **cannot guarantee** that it will function correctly on other setups.
- This software is provided **as-is**, without any support. We will not assist with installation or troubleshooting. If you encounter issues, you are welcome to submit a pull request (PR) while keeping the original functionality intact.