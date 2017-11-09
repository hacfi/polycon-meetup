# Polycon Meetup - Docker for PHP Developers
November 9, 2017



## Run your Docker image on AWS EC2 via systemd

1. Start new EC2 instance running [Debian Stretch](https://wiki.debian.org/Cloud/AmazonEC2Image/Stretch) via AWS cli (or AWS Console)

```
  aws ec2 run-instances --image-id ami-8bb70be4 --instance-type c4.large --key-name X
```


2. Get public ip (or use AWS Console)

```
aws ec2 describe-instances
```


3. Install Docker

```
ssh admin@>>>IP-from-step-2<<<
sudo -i

apt-get update
apt-get install -y \
    apt-transport-https \
    ca-certificates \
    curl \
    gnupg2 \
    software-properties-common

curl -fsSL https://download.docker.com/linux/$(. /etc/os-release; echo "$ID")/gpg | apt-key add -
add-apt-repository \
  "deb [arch=amd64] https://download.docker.com/linux/$(. /etc/os-release; echo "$ID") \
  $(lsb_release -cs) \
  stable"

apt-get update
apt-get install -y docker-ce

systemctl status docker

# Register at https://hub.docker.com/ first
docker login
```


4. Install [systemd-docker](https://github.com/ibuildthecloud/systemd-docker)

```
curl -L https://github.com/ibuildthecloud/systemd-docker/releases/download/v0.2.1/systemd-docker -o /usr/local/bin/systemd-docker
chmod +x /usr/local/bin/systemd-docker
```

5. Start postgresql & redis Docker containers

```
  docker run -d -p 5432:5432 -e POSTGRES_USER=user -e POSTGRES_PASSWORD=pass -e POSTGRES_DB=dbname --name db postgres:9.6.5  
docker run -d -p 6379:6379 --name redis redis:3.2.6
```

6. Create systemd service

```
exit  # exit root
exit  # exit EC2 instance & return to host
scp systemd/docker_polycon_app.service systemd/polycon.env admin@>>>IP-from-step-2<<<:
ssh admin@>>>IP-from-step-2<<<
sudo -i
mkdir /etc/docker-container
mv /home/admin/polycon.env /etc/docker-container
mv /home/admin/docker_polycon_app.service /etc/systemd/system
```

6. Start systemd service & create database schema

```
systemctl start docker_polycon_app
systemctl status docker_polycon_app

docker exec docker_polycon_app.service bin/console doctrine:schema:create
```

7. Open browser

Open http://>>>IP-from-step-2<<</
