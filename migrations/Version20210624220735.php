<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210624220735 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE notes_tags (notes_id INT NOT NULL, tags_id INT NOT NULL, INDEX IDX_27E782A7FC56F556 (notes_id), INDEX IDX_27E782A78D7B4FB4 (tags_id), PRIMARY KEY(notes_id, tags_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE notes_tags ADD CONSTRAINT FK_27E782A7FC56F556 FOREIGN KEY (notes_id) REFERENCES notes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE notes_tags ADD CONSTRAINT FK_27E782A78D7B4FB4 FOREIGN KEY (tags_id) REFERENCES tags (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE notes_tags');
    }
}
