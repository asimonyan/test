<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231106113823 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE answer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE participant_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE participant_answer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE question_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE answer (id INT NOT NULL, question_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, is_correct BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DADD4A251E27F6BF ON answer (question_id)');
        $this->addSql('CREATE TABLE participant (id INT NOT NULL, uid VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D79F6B11539B0606 ON participant (uid)');
        $this->addSql('CREATE TABLE participant_answer (id INT NOT NULL, participant_id INT DEFAULT NULL, question_id INT DEFAULT NULL, is_correct BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_68A1F4A49D1C3019 ON participant_answer (participant_id)');
        $this->addSql('CREATE INDEX IDX_68A1F4A41E27F6BF ON participant_answer (question_id)');
        $this->addSql('CREATE TABLE participant_selected_answer (participant_answer_id INT NOT NULL, answer INT NOT NULL, PRIMARY KEY(participant_answer_id, answer))');
        $this->addSql('CREATE INDEX IDX_E8EF3FDA69318723 ON participant_selected_answer (participant_answer_id)');
        $this->addSql('CREATE INDEX IDX_E8EF3FDADADD4A25 ON participant_selected_answer (answer)');
        $this->addSql('CREATE TABLE question (id INT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A251E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE participant_answer ADD CONSTRAINT FK_68A1F4A49D1C3019 FOREIGN KEY (participant_id) REFERENCES participant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE participant_answer ADD CONSTRAINT FK_68A1F4A41E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE participant_selected_answer ADD CONSTRAINT FK_E8EF3FDA69318723 FOREIGN KEY (participant_answer_id) REFERENCES participant_answer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE participant_selected_answer ADD CONSTRAINT FK_E8EF3FDADADD4A25 FOREIGN KEY (answer) REFERENCES answer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE answer_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE participant_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE participant_answer_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE question_id_seq CASCADE');
        $this->addSql('ALTER TABLE answer DROP CONSTRAINT FK_DADD4A251E27F6BF');
        $this->addSql('ALTER TABLE participant_answer DROP CONSTRAINT FK_68A1F4A49D1C3019');
        $this->addSql('ALTER TABLE participant_answer DROP CONSTRAINT FK_68A1F4A41E27F6BF');
        $this->addSql('ALTER TABLE participant_selected_answer DROP CONSTRAINT FK_E8EF3FDA69318723');
        $this->addSql('ALTER TABLE participant_selected_answer DROP CONSTRAINT FK_E8EF3FDADADD4A25');
        $this->addSql('DROP TABLE answer');
        $this->addSql('DROP TABLE participant');
        $this->addSql('DROP TABLE participant_answer');
        $this->addSql('DROP TABLE participant_selected_answer');
        $this->addSql('DROP TABLE question');
    }
}
