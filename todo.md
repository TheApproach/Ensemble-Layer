#  Todo

    • Continue with attaching the Gluster cluster. Update references to reflect new location
        • /usr/share/my.home on all containers, VMs, instances and boxes
        • checkout Approach to /lib/approach/[type]-[lang]-[major]-[minor]
			       /lib/approach/web-php-2-0/

    • Web Stack Role
        • Replace NGINX with Caddy option
        • Create PHP socket for project name, taken as Ansible variable or user prompt when running
        • Add variable to NGINX storing the location of the socket in nginx.conf
        • Add an include line to nginx.conf to import .conf from the suite-share
        • Make sure NGINX and PHP-FPM services are both enabled 
        • Enable|Disable|Start|Restart|Stop|Status NGINX and PHP-FPM services 
        • Free to use existing galaxy roles and just polyfill them with GPT
        • Make SSL auto-update whether it has to check internal conductor.orchestra.private CA or letsencrypt


    • Ensemble Role
        • Setup PowerDNS
		• Use https://github.com/PowerDNS/pdns-ansible Or similar existing Ansible tools unless desired
		• Use MariaDB Backend (each instance should have standalone mariadb installed on it. replication will happen via DNS recursion not the DB)
		• All nameservers should be on 10.7.2.x and subnet 10.7.0.0/16
	        • First instance set as Authoritative server and SOA of .home, .private, .corp and .lan
		• Install PowerDNS admin
	        • Instance 2 and 3 set as Recursive. 
		• Make instance 1 nameserver-02.system-00.infrastructure.my.home
		• make instance 2 & 3 nameserver-01.system-00.my.home, nameserver-02.system-00.infrastructure.my.home
		• Optionally add a load balancer group ns.my.home that include 01 & 02

	• Setup Step-CA
        	• https://ansible-collection-smallstep.readthedocs.io/en/latest/collections/index_role.html
		• Install root CA to secure.private on 10.7.13.37
			• Walk user through CA setup with menu and questions
			• Notify user after generating root certificate keypair
			• Use certificate chaining to create the online root from an offline root which only the ansible user has temporary access to
		• Install intermedia-CA to 
			• https://conductor.orchestra.private/ca-00
			• https://conductor.orchestra.private/ca-01
			• All other Ansible host inventory will be expressed through DNS names once DNS and CA are setup

	• Install Gluster role (playbook exists)

	• Give user option to install a 3x Galera cluster or a single MariaDB
	• Install Gitea to git.my.home
	• Install Keycloak to portal.my.home
	• Install Netbird to connect.my.home
		• Ansible task to configure Netbird based on Keycloak 
		• Ansible task to configure Keycloak based on netbird endpoints

	• Install web role (playbook exists)
		• ask user for a new project name
		• ask user for a list of IPs or a number of containers to use
		• create edge-[00..n].[project-name].my.home with the edge server web roles
		• ask user for a list of staging IPs or number of containrs to use
		• use the web playbook to setup staging-[00..n].[project-name].my.home

• Test and Document
	• Make sure all my.home, secure.private and orchestra.private addresses are only accessible when connected to Netbird
	• Try running this setup with all instances running as docker, podman or LXC containers on the same machine. 
		• only use compose files to attach networking and enable sshd for ansible
		• containers are only for testing these will be used in a hardware network enironment

	• make sure all facts gathered can be provided via terminal menus using Ansible plugins 
	• make sure all facts gathered can be skipped if provided via external source file or playbook vars
	• make sure all references to 192.x.x.x are updated to 10.7.0.0 addresses
	• make sure all server instances or containers are have a unique [instance-type-NN].system-00.infrastructure.my.home address 
	• projects will point to the same machines/instance/ips via different names but do not need any constraints

	• Ask questions, understand and better document the infrastructure / project / layer mapping
	• Document all playbooks, roles, tasks
	• Suggest ways to make this project more robust, more automated, more in line with the goals presented
	• Suggest other open source tools to install in the future which are BSD / Apache / MIT  style licensed or GPL 2.0






