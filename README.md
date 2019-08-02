# proServer Ansible Template

This repository serves as a template for your proServer ansible configuration. It uses our [open source Ansible roles](https://github.com/punktDe?q=proserver-ansible) for the [punkt.de proServer](https://infrastructure.punkt.de/de/produkte/proserver.html) as submodules to configure everything your PHP projects needs, like nginx with letsencrypt, MariaDB or Postgres, redis, elasticsearch and many services more.


## Set up your proServer

### 1. Install ansible

Set up ansible on your local mashine. Ansible 2.8 is required. See the [Ansible Installation Guide](https://docs.ansible.com/ansible/latest/installation_guide/intro_installation.html) for detailed instructions for your operating system.

### 2. Clone this repository

Clone this repository and init its submodules with `git submodule update --init`.

### 3. Configure your project

Basically there are two files, that define the services and configuration for your proServer instance:

**host_vars**

The host_vars file contains the configuration parameters for your host. We already provide some template files tailored to different applications and environments within the folder `host_vars_templates`. Copy the files you want to use to the `host_vars` folder. Then replace at least the occurences of `vpro0000` with your proServers number.

**inventory.ini**

The inventory.ini assignes the hosts to ansible groups. These groups define the roles and services a provisioned server instance provides.

### Run ansible

	ansible-playbook --ssh-extra-args '-o ProxyJump=jumping@ssh-jumphost.karlsruhe.punkt.de' --limit staging playbook.yaml


## Development with Vagrant and VirtualBox

### Additional Requirements

- Vagrant
- VirtualBox

### Set up your development environment

**1)** Clone this repository and it's submodules

```sh
git clone --recurse-submodules https://github.com/punktDe/proserver-ansible-example.git
cd proserver-ansible-example
```

**2)** Start proServer as a virtual machine using Vagrant

```sh
vagrant up
```

**3)** Update `/etc/hosts` to include virtual host names used in your playbook

You can also use your own domain if you like.
Just update `neos.domain` and `mailhog.domain` in `host_vars/development.yaml`.

```sh
echo "172.17.78.40 neos.proserver-dev.local mailhog.proserver-dev.local" | sudo tee -a /etc/hosts
```

**4)** Go to [http://neos.proserver-dev.local](http://neos.proserver-dev.local)


## Helpful links

- [Ansible Installation Guide](https://docs.ansible.com/ansible/latest/installation_guide/intro_installation.html)
- [Ansible Getting Started](https://docs.ansible.com/ansible/latest/user_guide/intro_getting_started.html)
- [Vagrant Getting Started](https://www.vagrantup.com/intro/getting-started/index.html)
