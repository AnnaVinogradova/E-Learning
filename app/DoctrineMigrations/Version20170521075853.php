<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170521075853 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user_exam (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, exam_id INT DEFAULT NULL, start_date DATETIME DEFAULT NULL, status TINYINT(1) NOT NULL, checked TINYINT(1) NOT NULL, sertificate TINYINT(1) NOT NULL, finished TINYINT(1) NOT NULL, INDEX IDX_423AEA0FA76ED395 (user_id), INDEX IDX_423AEA0F578D5E91 (exam_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_exam ADD CONSTRAINT FK_423AEA0FA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE user_exam ADD CONSTRAINT FK_423AEA0F578D5E91 FOREIGN KEY (exam_id) REFERENCES exam (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE user_exam');
    }
}
