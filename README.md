# [proserver-ansible-example](https://github.com/punktDe/proserver-ansible-example)

This playbook demonstrates the use of our [open source Ansible roles for the proServer](https://github.com/punktDe?q=proserver-ansible).

## Requirements

- Ansible
- Vagrant
- VirtualBox

## Development with Vagrant and VirtualBox

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
Just update `neos.domain` and `mailhog.domain` in `host_vars/proserver-dev.yaml`.

```sh
echo "172.17.78.40 neos.proserver-dev.local mailhog.proserver-dev.local" | sudo tee -a /etc/hosts
```

**4)** Go to [http://neos.proserver-dev.local](http://neos.proserver-dev.local)

## Going live

Configure your proServer(s) in `inventory` and use `ansible-playbook` as usual.

## Helpful links

- [Ansible Installation Guide](https://docs.ansible.com/ansible/latest/installation_guide/intro_installation.html)
- [Ansible Getting Started](https://docs.ansible.com/ansible/latest/user_guide/intro_getting_started.html)
- [Vagrant Getting Started](https://www.vagrantup.com/intro/getting-started/index.html)
