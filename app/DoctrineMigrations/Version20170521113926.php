<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170521113926 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_course ADD flow_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_course ADD CONSTRAINT FK_73CC74847EB60D1B FOREIGN KEY (flow_id) REFERENCES flow (id)');
        $this->addSql('CREATE INDEX IDX_73CC74847EB60D1B ON user_course (flow_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_course DROP FOREIGN KEY FK_73CC74847EB60D1B');
        $this->addSql('DROP INDEX IDX_73CC74847EB60D1B ON user_course');
        $this->addSql('ALTER TABLE user_course DROP flow_id');
    }
}
