# mtap
provisioning system for Mikrotik RouterOS via API

Application provides easy way to manage L2 filters on bridge interfaces of Mikrotik RouterOS devices.
3 mandatory fields are located on the page for filters management:
1) action:
a) drop (means - no access)
b) accept (means - traffic will pass)

2) chain:
a) input - all the traffic which reaches Mikrotik for being routed to some other broadcast domain (i.e., vlan or Internet)
b) forward - all L2-broadcast traffic (DHCP-requests for example)
Thus, when customer add filter, he should add 2 entries - one for ‘input’ chain, other for ‘forward’ chain to avoid both - routing and broadcast traffic.

3) src-mac-address - target mac-address, which should be blocked either accepted 

Application allows to use 2 scenarios for customer network:
1) Whitelist of mac-addresses - when the customer prohibit all mac-addresses except added to the whitelist. Explicit “deny all” rule should be configured manually as the bottom rule after adding filling in the white list.
2) Blacklist of mac-addresses - when the customer allow all mac-addresses except added to the blacklist.

Config file include:
1) DB connection settings
2) log file path

Each customers action will be logged (log in attempt, router selection, adding/removing filters attempt) 
