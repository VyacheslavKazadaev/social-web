FROM tarantool/tarantool:1.x-centos7

RUN     yum -y update \
     && yum -y clean all
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
RUN pip3 install -r mariadb-tarantool-replication/requirements.txt

RUN git clone --recursive https://github.com/smarek/python-mariadb-replication.git
WORKDIR /python-mariadb-replication
RUN python3 setup.py install

WORKDIR /mariadb-tarantool-replication
RUN /mariadb-tarantool-replication/install.sh

COPY app.lua /opt/tarantool
CMD ["tarantool", "/opt/tarantool/app.lua"]



