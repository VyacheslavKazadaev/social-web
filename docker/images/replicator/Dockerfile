FROM centos/systemd

RUN     yum -y update && yum -y clean all
RUN     yum -y install \
        epel-release \
        python36 \
        python34-setuptools \
        git \
     && rm -rf /var/cache/yum

RUN easy_install-3.6 pip
RUN pip3 install tarantool

WORKDIR /
RUN git clone --recursive https://github.com/smarek/mariadb-tarantool-replication.git
WORKDIR /mariadb-tarantool-replication
RUN pip3 install -r requirements.txt

RUN git clone --recursive https://github.com/smarek/python-mariadb-replication.git
WORKDIR python-mariadb-replication
RUN python3 setup.py install

ADD install.sh /mariadb-tarantool-replication/install.sh

WORKDIR /mariadb-tarantool-replication
RUN /mariadb-tarantool-replication/install.sh

CMD ["/usr/sbin/init"]
