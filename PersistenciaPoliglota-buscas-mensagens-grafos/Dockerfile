FROM php:8.2-cli

RUN apt-get update
RUN apt-get install -y libpq-dev && docker-php-ext-install pdo_pgsql

RUN pecl install redis && docker-php-ext-enable redis

RUN apt install -y libz-dev libmemcached-dev && pecl install memcached && docker-php-ext-enable memcached

RUN pecl install mongodb && docker-php-ext-enable mongodb

RUN apt-get update && apt-get install -y cmake

RUN apt-get install -y ninja-build git libuv1-dev cmake libgmp-dev && \
    cd /tmp && \
    git clone --depth 1 https://github.com/scylladb/cpp-driver.git scyladb-driver \
      && cd scyladb-driver \
      && mkdir build \
      && cd build \
      && cmake -DCASS_CPP_STANDARD=17 -DCASS_BUILD_STATIC=ON -DCASS_BUILD_SHARED=ON -DCASS_USE_STD_ATOMIC=ON -DCASS_USE_TIMERFD=ON -DCASS_USE_LIBSSH2=ON -DCASS_USE_ZLIB=ON CMAKE_C_FLAGS="-fPIC" -DCMAKE_CXX_FLAGS="-fPIC -Wno-error=redundant-move" -DCMAKE_BUILD_TYPE="RelWithInfo" -G Ninja .. \
      && ninja install && \
    cd /tmp && \
    git clone --recursive https://github.com/he4rt/scylladb-php-driver.git && \
    cd scylladb-php-driver && \
    cmake --preset Release  && cd out/Release && ninja install && \
    cp ../../cassandra.ini /usr/local/etc/php/conf.d/cassandra.ini && \
    cp cassandra.so /usr/local/lib/php/extensions/no-debug-non-zts-20220829/
