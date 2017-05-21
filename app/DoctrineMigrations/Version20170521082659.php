<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170521082659 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE exam_answer ADD user_exam_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE exam_answer ADD CONSTRAINT FK_11EE1CAFF80409B FOREIGN KEY (user_exam_id) REFERENCES user_exam (id)');
        $this->addSql('CREATE INDEX IDX_11EE1CAFF80409B ON exam_answer (user_exam_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE exam_answer DROP FOREIGN KEY FK_11EE1CAFF80409B');
        $this->addSql('DROP INDEX IDX_11EE1CAFF80409B ON exam_answer');
        $this->addSql('ALTER TABLE exam_answer DROP user_exam_id');
    }
}
