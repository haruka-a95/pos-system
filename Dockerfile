FROM php:8.2-fpm

# 作業ディレクトリ
WORKDIR /var/www/laravel-app

# 必要なパッケージと PHP 拡張のインストール
RUN apt-get update && apt-get upgrade -y && \
    apt-get install -y \
      libpng-dev \
      libjpeg-dev \
      libfreetype6-dev \
      zip \
      git \
      unzip \
      curl \
      gnupg \
      build-essential && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd pdo pdo_mysql && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Composer のインストール
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Node.js 20.x のインストール
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# PHP-FPM 起動
CMD ["php-fpm"]