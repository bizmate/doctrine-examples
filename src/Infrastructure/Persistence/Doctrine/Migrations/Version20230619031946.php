<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230619031946 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Business <- one to many -> Reviews <- many to one -> Author/User';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE business (id VARCHAR(255) NOT NULL, alias VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, review_count INT NOT NULL, rating DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE businesses_reviews (business_id VARCHAR(255) NOT NULL, review_id VARCHAR(255) NOT NULL, INDEX IDX_31882A2BA89DB457 (business_id), UNIQUE INDEX UNIQ_31882A2B3E2E969B (review_id), PRIMARY KEY(business_id, review_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE review (id VARCHAR(255) NOT NULL, user_id VARCHAR(255) DEFAULT NULL, rating INT NOT NULL, text LONGTEXT DEFAULT NULL, INDEX IDX_794381C6A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE businesses_reviews ADD CONSTRAINT FK_31882A2BA89DB457 FOREIGN KEY (business_id) REFERENCES business (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE businesses_reviews ADD CONSTRAINT FK_31882A2B3E2E969B FOREIGN KEY (review_id) REFERENCES review (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE businesses_reviews DROP FOREIGN KEY FK_31882A2BA89DB457');
        $this->addSql('ALTER TABLE businesses_reviews DROP FOREIGN KEY FK_31882A2B3E2E969B');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6A76ED395');
        $this->addSql('DROP TABLE business');
        $this->addSql('DROP TABLE businesses_reviews');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE user');
    }
}
