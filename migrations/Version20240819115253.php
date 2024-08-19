<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240819115253 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE us_operation DROP FOREIGN KEY FK_176B6B7FB4CB5EDE');
        $this->addSql('ALTER TABLE us_operation_user DROP FOREIGN KEY FK_61201196585E0F6');
        $this->addSql('ALTER TABLE us_operation_user DROP FOREIGN KEY FK_6120119A76ED395');
        $this->addSql('ALTER TABLE us_sous_module DROP FOREIGN KEY FK_411A14EBAFC2B591');
        $this->addSql('DROP TABLE us_module');
        $this->addSql('DROP TABLE us_operation');
        $this->addSql('DROP TABLE us_operation_user');
        $this->addSql('DROP TABLE us_sous_module');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE us_module (id INT AUTO_INCREMENT NOT NULL, designation VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, prefix VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, icon VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ordre INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE us_operation (id INT AUTO_INCREMENT NOT NULL, sous_module_id INT DEFAULT NULL, link VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, icon VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, designation VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, class_tag VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, id_tag VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ordre INT DEFAULT NULL, align TINYINT(1) DEFAULT NULL, INDEX IDX_176B6B7FB4CB5EDE (sous_module_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE us_operation_user (us_operation_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_61201196585E0F6 (us_operation_id), INDEX IDX_6120119A76ED395 (user_id), PRIMARY KEY(us_operation_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE us_sous_module (id INT AUTO_INCREMENT NOT NULL, module_id INT DEFAULT NULL, link VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, designation VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, prefix VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ordre INT NOT NULL, INDEX IDX_411A14EBAFC2B591 (module_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE us_operation ADD CONSTRAINT FK_176B6B7FB4CB5EDE FOREIGN KEY (sous_module_id) REFERENCES us_sous_module (id)');
        $this->addSql('ALTER TABLE us_operation_user ADD CONSTRAINT FK_61201196585E0F6 FOREIGN KEY (us_operation_id) REFERENCES us_operation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE us_operation_user ADD CONSTRAINT FK_6120119A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE us_sous_module ADD CONSTRAINT FK_411A14EBAFC2B591 FOREIGN KEY (module_id) REFERENCES us_module (id)');
    }
}
