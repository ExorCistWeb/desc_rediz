CREATE TABLE wp_custom_users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    bio TEXT,
    phone VARCHAR(20),
    FOREIGN KEY (user_id) REFERENCES wp_users(ID) ON DELETE CASCADE
);

CREATE TABLE wp_services (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT
);

CREATE TABLE wp_portfolio (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    user_id BIGINT UNSIGNED,
    service_id BIGINT UNSIGNED,
    FOREIGN KEY (user_id) REFERENCES wp_custom_users(id) ON DELETE SET NULL,
    FOREIGN KEY (service_id) REFERENCES wp_services(id) ON DELETE SET NULL
);
