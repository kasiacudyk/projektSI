<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210621202744 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE users_data');
        $this->addSql('ALTER TABLE todolist_tags ADD CONSTRAINT FK_236546CFB3AB48EB FOREIGN KEY (to_do_list_id) REFERENCES to_do_list (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users ADD email VARCHAR(180) NOT NULL, DROP login, CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL, CHANGE role roles JSON NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX email_idx ON users (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE users_data (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(45) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, surname VARCHAR(45) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, email VARCHAR(45) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE todolist_tags DROP FOREIGN KEY FK_236546CFB3AB48EB');
        $this->addSql('DROP INDEX email_idx ON users');
        $this->addSql('ALTER TABLE users ADD login VARCHAR(45) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP email, CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE roles role JSON NOT NULL');
    }
}
