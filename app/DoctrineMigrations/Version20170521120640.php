<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170521120640 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user_homework (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, homework_id INT DEFAULT NULL, content LONGTEXT NOT NULL, comment LONGTEXT DEFAULT NULL, checked TINYINT(1) NOT NULL, mark INT DEFAULT NULL, INDEX IDX_6CEDEF4EA76ED395 (user_id), INDEX IDX_6CEDEF4EB203DDE5 (homework_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_homework ADD CONSTRAINT FK_6CEDEF4EA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE user_homework ADD CONSTRAINT FK_6CEDEF4EB203DDE5 FOREIGN KEY (homework_id) REFERENCES homework (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE user_homework');
    }
}
