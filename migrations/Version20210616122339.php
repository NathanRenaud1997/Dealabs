<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210616122339 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alerte ADD keyword VARCHAR(255) NOT NULL, DROP keywords');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alerte ADD keywords LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\', DROP keyword');
        $this->addSql('ALTER TABLE saved_bonplan DROP FOREIGN KEY FK_28FABDE7F52E2F80');
        $this->addSql('DROP INDEX IDX_28FABDE7F52E2F80 ON saved_bonplan');
        $this->addSql('ALTER TABLE saved_bonplan DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE saved_bonplan CHANGE bon_plan_id  bon_plan_id INT NOT NULL');
        $this->addSql('ALTER TABLE saved_bonplan ADD CONSTRAINT FK_28FABDE7F52E2F80 FOREIGN KEY (bon_plan_id) REFERENCES bon_plan (id)');
        $this->addSql('CREATE INDEX IDX_28FABDE7F52E2F80 ON saved_bonplan (bon_plan_id)');
        $this->addSql('ALTER TABLE saved_bonplan ADD PRIMARY KEY (user_id, bon_plan_id)');
        $this->addSql('ALTER TABLE saved_codepromo DROP FOREIGN KEY FK_EE06C9DC68FB988A');
        $this->addSql('DROP INDEX IDX_EE06C9DC68FB988A ON saved_codepromo');
        $this->addSql('ALTER TABLE saved_codepromo DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE saved_codepromo CHANGE code_promo_id  code_promo_id INT NOT NULL');
        $this->addSql('ALTER TABLE saved_codepromo ADD CONSTRAINT FK_EE06C9DC68FB988A FOREIGN KEY (code_promo_id) REFERENCES code_promo (id)');
        $this->addSql('CREATE INDEX IDX_EE06C9DC68FB988A ON saved_codepromo (code_promo_id)');
        $this->addSql('ALTER TABLE saved_codepromo ADD PRIMARY KEY (user_id, code_promo_id)');
    }
}
