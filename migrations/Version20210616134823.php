<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210616134823 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_bon_plan (user_id INT NOT NULL, bon_plan_id INT NOT NULL, INDEX IDX_1E3C12F2A76ED395 (user_id), INDEX IDX_1E3C12F22E81A751 (bon_plan_id), PRIMARY KEY(user_id, bon_plan_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_code_promo (user_id INT NOT NULL, code_promo_id INT NOT NULL, INDEX IDX_FB21455BA76ED395 (user_id), INDEX IDX_FB21455B294102D4 (code_promo_id), PRIMARY KEY(user_id, code_promo_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_bon_plan ADD CONSTRAINT FK_1E3C12F2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_bon_plan ADD CONSTRAINT FK_1E3C12F22E81A751 FOREIGN KEY (bon_plan_id) REFERENCES bon_plan (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_code_promo ADD CONSTRAINT FK_FB21455BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_code_promo ADD CONSTRAINT FK_FB21455B294102D4 FOREIGN KEY (code_promo_id) REFERENCES code_promo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE saved_bonplan DROP FOREIGN KEY FK_28FABDE7F52E2F80');
        $this->addSql('ALTER TABLE saved_bonplan DROP PRIMARY KEY');
        $this->addSql('DROP INDEX IDX_28FABDE7F52E2F80 ON saved_bonplan');
        $this->addSql('ALTER TABLE saved_bonplan CHANGE bon_plan_id bon_plan_id  INT NOT NULL');
        $this->addSql('ALTER TABLE saved_bonplan ADD CONSTRAINT FK_28FABDE7F52E2F80 FOREIGN KEY (bon_plan_id ) REFERENCES bon_plan (id)');
        $this->addSql('ALTER TABLE saved_bonplan ADD PRIMARY KEY (user_id, bon_plan_id )');
        $this->addSql('CREATE INDEX IDX_28FABDE7F52E2F80 ON saved_bonplan (bon_plan_id )');
        $this->addSql('ALTER TABLE saved_codepromo DROP FOREIGN KEY FK_EE06C9DC68FB988A');
        $this->addSql('ALTER TABLE saved_codepromo DROP PRIMARY KEY');
        $this->addSql('DROP INDEX IDX_EE06C9DC68FB988A ON saved_codepromo');
        $this->addSql('ALTER TABLE saved_codepromo CHANGE code_promo_id code_promo_id  INT NOT NULL');
        $this->addSql('ALTER TABLE saved_codepromo ADD CONSTRAINT FK_EE06C9DC68FB988A FOREIGN KEY (code_promo_id ) REFERENCES code_promo (id)');
        $this->addSql('ALTER TABLE saved_codepromo ADD PRIMARY KEY (user_id, code_promo_id )');
        $this->addSql('CREATE INDEX IDX_EE06C9DC68FB988A ON saved_codepromo (code_promo_id )');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user_bon_plan');
        $this->addSql('DROP TABLE user_code_promo');
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
