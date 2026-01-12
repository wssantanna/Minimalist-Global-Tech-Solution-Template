FROM wordpress:latest

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Verify installations
RUN php -v && composer --version

# Set working directory
WORKDIR /var/www/html
