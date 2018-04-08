# [proserver-ansible-example](https://github.com/punktDe/proserver-ansible-example)

This example playbook demonstrates the use of our [publicly available Ansible roles for the proServer](https://github.com/punktDe?q=proserver-ansible).

## Local development

**1)** Clone this repository w/ submodules

```
git clone --recurse-submodules https://github.com/punktDe/proserver-ansible-example.git
cd proserver-ansible-example
```

**2)** Vagrant up

```
vagrant up
```

**3)** Update `/etc/hosts`

```
echo "172.17.78.40 demo.proserver-dev.local mailhog.proserver-dev.local" | sudo tee -a /etc/hosts
```

**4)** Go to [http://demo.proserver-dev.local](http://demo.proserver-dev.local)

## Provision proServer

Configure your proServer(s) in `inventory` and use `ansible-playbook` as usual.
