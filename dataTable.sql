CREATE TABLE `accounts` (
  `id`         int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name`       varchar(100) NOT NULL,
  `parent_id`  int(11) UNSIGNED DEFAULT NULL,
  `code`       varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
               UNIQUE KEY(`code`),
               UNIQUE KEY(`name`,`parent_id`),
  CONSTRAINT   `a_p_1` FOREIGN KEY (`parent_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `customers` (
  `id`         int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name`       varchar(100) NOT NULL,
  `phone`      varchar(64) NOT NULL,
  `address`    varchar(64) NOT NULL,
  `status`     int(3) UNSIGNED NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
               UNIQUE KEY(`name` , `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `employees` (
  `id`         int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name`       varchar(100) NOT NULL,
  `address`    varchar(100) NOT NULL,
  `phone`      varchar(100) NOT NULL,
  `statement`  varchar(100) DEFAULT NULL,
  `salary`     int(11) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
               UNIQUE KEY(`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `loans` (
  `id`         int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name`       varchar(100) NOT NULL,
  `address`    varchar(100) NOT NULL,
  `phone`      varchar(100) NOT NULL,
  `Lending`    int(11) UNSIGNED NOT NULL DEFAULT 0,
  `repayment`  int(11) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
               UNIQUE KEY(`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `main` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `management` (
  `id`         int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name`       varchar(100) NOT NULL,
  `cost`       int(6) UNSIGNED NOT NULL,
  `percent`    tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
               UNIQUE KEY(`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `permissions` (
  `id`         int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name`       varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
               UNIQUE KEY(`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `services` (
  `id`               int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name`             varchar(100) NOT NULL,
  `parent_id`        int(11) UNSIGNED DEFAULT NULL,
  `created_at`       timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at`       timestamp NOT NULL DEFAULT current_timestamp(),
                     UNIQUE KEY(`name` , `parent_id`),
  CONSTRAINT         `s_p_1` FOREIGN KEY (`parent_id`) REFERENCES `services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `stores` (
  `id`         int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name`       varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
               UNIQUE KEY(`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `users` (
  `id`         int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_name`  varchar(100) NOT NULL,
  `password`   varchar(64) NOT NULL,
  `full_name`  varchar(64) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
               UNIQUE KEY(`user_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `sales` (
  `id`         int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `main_id`    int(11) UNSIGNED NOT NULL,
  `service_id` int(11) UNSIGNED NOT NULL,
  `parent_id`  int(11) UNSIGNED DEFAULT NULL,
  `quantity`   int(6) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  CONSTRAINT   `s_m_1` FOREIGN KEY (`main_id`) REFERENCES `main` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT   `s_s_2` FOREIGN KEY (`parent_id`) REFERENCES `services` (`parent_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT   `s_s_3` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `workers` (
  `id`         int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name`       varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
               UNIQUE KEY(`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `bills` (
  `id`             int(11)    UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `main_id`        int(11)    UNSIGNED NOT NULL,
  `customer_id`    int(11)    UNSIGNED NOT NULL,
  `code`           int(11)    UNSIGNED NOT NULL,
  `num_days`       int(10)    UNSIGNED NOT NULL DEFAULT 1,
  `baggage`        bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `status`         int(3)     UNSIGNED NOT NULL DEFAULT 1,
  `bill_type`      int(3)     UNSIGNED NOT NULL DEFAULT 1,
  `relay`          int(10)    UNSIGNED NOT NULL DEFAULT 0,
  `employee_price` int(10)    UNSIGNED NOT NULL DEFAULT 0,
  `total_price`    bigint(20) UNSIGNED NOT NULL,
  `discount`       int(11)    UNSIGNED NOT NULL DEFAULT 0,
  `price`          bigint(20) UNSIGNED NOT NULL,
  `bill_date`      date NOT NULL,
  `details`        longtext NOT NULL,
  `created_at`     timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at`     timestamp NOT NULL DEFAULT current_timestamp(),
                   UNIQUE KEY(`code` , `status`),
  CONSTRAINT       `b_m_1` FOREIGN KEY (`main_id`) REFERENCES `main` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT       `b_c_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `bill_expense` (
  `id`             int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `bill_id`        int(11) UNSIGNED NOT NULL,
  `main_id`        int(11) UNSIGNED NOT NULL,
  `tent`           int(11) UNSIGNED NOT NULL DEFAULT 0,
  `decoration`     int(11) UNSIGNED NOT NULL DEFAULT 0,
  `electricity`    int(11) UNSIGNED NOT NULL DEFAULT 0,
  `service`        int(11) UNSIGNED NOT NULL DEFAULT 0,
  `incentives`     int(11) UNSIGNED NOT NULL DEFAULT 0,
  `administrative` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `admin`          int(11) UNSIGNED NOT NULL DEFAULT 0,
  `warehouse`      int(11) UNSIGNED NOT NULL DEFAULT 0,
  `relay_in`       int(11) UNSIGNED NOT NULL DEFAULT 0,
  `relay_out`      int(11) UNSIGNED NOT NULL DEFAULT 0,
  `driver`         int(11) UNSIGNED NOT NULL DEFAULT 0,
  `companion`      int(11) UNSIGNED NOT NULL DEFAULT 0,
  `total`          int(11) UNSIGNED NOT NULL DEFAULT 0,
  `created_at`     timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at`     timestamp NOT NULL DEFAULT current_timestamp(),
                   UNIQUE KEY(`bill_id`),
  CONSTRAINT       `b_b_1` FOREIGN KEY (`bill_id`) REFERENCES `bills` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT       `b_m_2` FOREIGN KEY (`main_id`) REFERENCES `main` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `bill_refund` (
  `id`           int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `bill_id`      int(11) UNSIGNED NOT NULL,
  `main_id`      int(11) UNSIGNED NOT NULL,
  `amount_paid`  int(11) UNSIGNED NOT NULL DEFAULT 0,
  `amount_total` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `refund`       int(11) UNSIGNED NOT NULL DEFAULT 0,
  `pay`          int(11) UNSIGNED NOT NULL DEFAULT 0,
  `created_at`   timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at`   timestamp NOT NULL DEFAULT current_timestamp(),
                 UNIQUE KEY(`bill_id`),
  CONSTRAINT     `b_r_b_1` FOREIGN KEY (`bill_id`) REFERENCES `bills` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT     `b_r_m_1` FOREIGN KEY (`main_id`) REFERENCES `main` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `store_service`(
  `id`              int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `store_id`        int(11) UNSIGNED NOT NULL,
  `service_id`      int(11) UNSIGNED NOT NULL,
  `worker_id`       int(11) UNSIGNED NOT NULL,
  `quantity`        int(6) UNSIGNED NOT NULL DEFAULT 0,
  `customer_price`  int(11) UNSIGNED NOT NULL DEFAULT 0,
  `companion_price` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `employee_price`  int(11) UNSIGNED NOT NULL DEFAULT 0,
  `created_at`      timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at`      timestamp NOT NULL DEFAULT current_timestamp(),
                    UNIQUE(`store_id`, `service_id`),
  CONSTRAINT        `s_s_s_1` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT        `s_s_s_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT        `s_s_w_3` FOREIGN KEY (`worker_id`) REFERENCES `workers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `cashing` (
  `id`               int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `store_service_id` int(11) UNSIGNED NOT NULL,
  `service_id`       int(11) UNSIGNED NOT NULL,
  `store_id`         int(11) UNSIGNED NOT NULL,
  `parent_id`        int(11) UNSIGNED DEFAULT NULL,
  `bill_id`          int(11) UNSIGNED NOT NULL,
  `quantity`         int(11) UNSIGNED NOT NULL DEFAULT 0,
  `created_at`       timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at`       timestamp NOT NULL DEFAULT current_timestamp(),
  CONSTRAINT         `c_s_s_1` FOREIGN KEY (`store_service_id`) REFERENCES `store_service` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT         `c_s_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT         `c_s_3` FOREIGN KEY (`parent_id`) REFERENCES `services` (`parent_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT         `c_s_4` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT         `c_b_5` FOREIGN KEY (`bill_id`) REFERENCES `bills` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `cashing_out` (
  `id`          int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `cashing_id`  int(11) UNSIGNED NOT NULL,
  `customer_id` int(11) UNSIGNED NOT NULL,
  `quantity`    int(11) UNSIGNED NOT NULL DEFAULT 0,
  `created_at`  timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at`  timestamp NOT NULL DEFAULT current_timestamp(),
  CONSTRAINT    `c_o_c_1` FOREIGN KEY (`cashing_id`) REFERENCES `cashing` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT    `c_o_c_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `checks` (
  `id`                int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `account_id_first`  int(11) UNSIGNED NOT NULL,
  `account_id_second` int(11) UNSIGNED NOT NULL,
  `main_id`           int(11) UNSIGNED NOT NULL,
  `amount`            int(11) UNSIGNED NOT NULL,
  `serial_number`     int(11) UNSIGNED NOT NULL,
  `name`              varchar(60) NOT NULL,
  `release_date`      date NOT NULL,
  `expiry_date`       date NOT NULL,
  `statement`         varchar(100) DEFAULT NULL,
  `status`            tinyint(1) NOT NULL DEFAULT 0,
  `created_at`        timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at`        timestamp NOT NULL DEFAULT current_timestamp(),
  CONSTRAINT          `c_a_1` FOREIGN KEY (`account_id_first`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT          `c_a_2` FOREIGN KEY (`account_id_second`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT          `c_m_3` FOREIGN KEY (`main_id`) REFERENCES `main` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `move` (
  `id`         int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `main_id`    int(11) UNSIGNED NOT NULL,
  `account_id` int(11) UNSIGNED NOT NULL,
  `price`      bigint(20) UNSIGNED NOT NULL,
  `statment`   varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
               UNIQUE(`main_id`, `account_id`),
  CONSTRAINT   `m_m_1` FOREIGN KEY (`main_id`) REFERENCES `main` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT   `m_a_2` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `move_fake` (
  `id`         int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `main_id`    int(11) UNSIGNED NOT NULL,
  `account_id` int(11) UNSIGNED NOT NULL,
  `price`      bigint(20) UNSIGNED NOT NULL,
  `statment`   varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
               UNIQUE(`main_id`, `account_id`),
  CONSTRAINT   `m_f_m_1` FOREIGN KEY (`main_id`) REFERENCES `main` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT   `m_f_a_2` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `move_line`(
  `id`         int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `move_id`    int(11) UNSIGNED NOT NULL,
  `account_id` int(11) UNSIGNED NOT NULL,
  `debtor`     bigint(20) UNSIGNED NOT NULL,
  `creditor`   bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  CONSTRAINT   `m_l_m_1` FOREIGN KEY (`move_id`) REFERENCES `move` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT   `m_l_a_2` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `move_line_fake`(
  `id`           int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `move_fake_id` int(11) UNSIGNED NOT NULL,
  `account_id`   int(11) UNSIGNED NOT NULL,
  `debtor`       bigint(20) UNSIGNED NOT NULL,
  `creditor`     bigint(20) UNSIGNED NOT NULL,
  `created_at`   timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at`   timestamp NOT NULL DEFAULT current_timestamp(),
  CONSTRAINT     `m_l_f_m_1` FOREIGN KEY (`move_fake_id`) REFERENCES `move_fake` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT     `m_l_f_a_2` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `user_permission` (
  `id`            int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id`       int(11) UNSIGNED NOT NULL,
  `permission_id` int(11) UNSIGNED NOT NULL,
  `created_at`    timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at`    timestamp NOT NULL DEFAULT current_timestamp(),
  CONSTRAINT      `u_p_u_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT      `u_p_p_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `employee_salary` (
  `id`            int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `main_id`       int(11) UNSIGNED NOT NULL,
  `employee_id`   int(11) UNSIGNED NOT NULL,
  `status`        tinyint(1) NOT NULL DEFAULT 0,
  `salary`        int(11) UNSIGNED NOT NULL,
  `month`         int(3) UNSIGNED NOT NULL,
  `year`          year(4) NOT NULL DEFAULT current_timestamp(),
  `created_at`    timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at`    timestamp NOT NULL DEFAULT current_timestamp(),
  CONSTRAINT      `e_s_m_1` FOREIGN KEY (`main_id`) REFERENCES `main` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT      `e_s_e_2` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;