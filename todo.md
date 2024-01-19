#  Todo (are these actually already done?)

    • Continue with attaching the Gluster cluster. 
      At this time we may choose home.lan share's long term location. 
      Considerations:
        • /usr/share/approach, 
        • /usr/share/home.lan
        • /lib/approach/approach + /lib/suitespace/[project_dir] + /usr/bin/[project_entry_script]
        • Other; see: <http://www.novell.com/documentation/open-enterprise-server-2018/stor_filesys_lx/data/bs8hzyc.html>

    • Web Stack Role
        • Install PHP 8.2+ and NGINX
        • Create PHP socket for project name, taken as Ansible variable or user prompt when running
        • Add variable to NGINX storing the location of the socket in nginx.conf
        • Add an include line to nginx.conf to import .conf from the suite-share
        • Make sure NGINX and PHP-FPM services are both enabled 
        • Enable|Disable|Start|Restart|Stop|Status NGINX and PHP-FPM services 
        • Free to use existing galaxy roles and just polyfill them with GPT
