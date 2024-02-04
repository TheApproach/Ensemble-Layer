# Ansible Setup for deploying network ensembles of a standard Approach Orchestra

## Directory Structure

The repository is structured as follows:

ansible
├── inventories
├── playbooks
├── roles
│   └── role_template
└── readme.md

It includes a number of easy-to-use playbooks and a primary role for infrastructure servers.

## Hostfile (hosts.yml)

The hostfile is located at `inventories/hosts.yml`. It contains the definitions of the server groups and the hostnames of the servers in each group.

## Dependencies

The only dependency is Ansible and its dependencies. Please note that the Ansible setup can only be run from the orchestra servers since they have access and permission to our internal servers.

## Getting Started

1. Pull the repo: `git clone https://github.com/TheApproach/Ensemble-Layer.git`.
2. Install Ansible. Here's a guide for [Ubuntu](https://docs.ansible.com/ansible/latest/installation_guide/intro_installation.html#installing-ansible-on-ubuntu) users.
3. You can then run the playbooks. For example, to install the CA certificate, you can use the command:

   ```bash
   ansible-playbook -i /home/ubuntu/ansible/inventories/hosts.yml /home/ubuntu/ansible/playbooks/ca-certificate-playbook.yml
   ```

## Naming Conventions & Best Practices

We adhere to Ansible's default naming conventions and best practices. For roles, we provide a role template at roles/role_template that should be used as a guide when creating new roles.

## Troubleshooting

In case of issues, it's smart to first run the ping-playbook to test the connections. This playbook will help you determine if there are connectivity issues.

For any other issues or queries, please reach out to the [Approach Mailing List](mailto:approach@orchestrationsyndicate.com).
