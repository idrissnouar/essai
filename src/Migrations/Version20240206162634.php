<?php

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

class Version20240206162634 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql(
            'CREATE TABLE `company` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;'
        );
        $this->addSql(
            'CREATE TABLE `employee` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `birth_date` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;'
        );
        $this->addSql(
            'INSERT INTO `company` (`id`, `name`, `created`, `updated`)
VALUES
	(1, "Company A", "2024-02-06 14:47:11", "2024-02-06 14:47:11"),
	(2, "Company B", "2024-02-06 14:47:11", "2024-02-06 14:47:11"),
	(3, "Company C", "2024-02-06 14:47:11", "2024-02-06 14:47:11");
');
        $this->addSql(
            'CREATE TABLE `job` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `employee_id` int NOT NULL,
  `company_id` int NOT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;'
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE employee');
        $this->addSql('DROP TABLE job');
    }
}
