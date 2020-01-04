FROM mysql/mysql-server:5.5.62

# Setup the custom configuration
ADD mysqld.cnf /etc/mysql/mysql.conf.d/mysqld.cnf
COPY add.sql /docker-entrypoint-initdb.d
RUN chmod 777 /docker-entrypoint-initdb.d/add.sql