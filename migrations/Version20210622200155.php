<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210622200155 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE to_do_list ADD author_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE to_do_list ADD CONSTRAINT FK_4A6048ECF675F31B FOREIGN KEY (author_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_4A6048ECF675F31B ON to_do_list (author_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE to_do_list DROP FOREIGN KEY FK_4A6048ECF675F31B');
        $this->addSql('DROP INDEX IDX_4A6048ECF675F31B ON to_do_list');
        $this->addSql('ALTER TABLE to_do_list DROP author_id');
    }
}
