<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240530203901 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog ADD author_id INT');
        $this->addSql('ALTER TABLE blog ADD confidentiality SMALLINT NOT NULL DEFAULT 0');
        $this->addSql('ALTER TABLE blog ALTER slug DROP DEFAULT');
        $this->addSql('ALTER TABLE blog ADD CONSTRAINT FK_C0155143F675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_C0155143F675F31B ON blog (author_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE blog DROP CONSTRAINT FK_C0155143F675F31B');
        $this->addSql('DROP INDEX IDX_C0155143F675F31B');
        $this->addSql('ALTER TABLE blog DROP author_id');
        $this->addSql('ALTER TABLE blog DROP confidentiality');
        $this->addSql('ALTER TABLE blog ALTER slug SET DEFAULT \'\'');
    }
}
