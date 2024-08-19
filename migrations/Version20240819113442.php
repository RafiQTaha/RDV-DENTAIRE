<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240819113442 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ac_annee (id INT NOT NULL, formation_id INT DEFAULT NULL, code VARCHAR(255) DEFAULT NULL, designation VARCHAR(255) DEFAULT NULL, active INT DEFAULT NULL, validation_academique VARCHAR(255) DEFAULT NULL, cloture_academique VARCHAR(255) DEFAULT NULL, INDEX IDX_2655A1C45200282E (formation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ac_etablissement (id INT NOT NULL, code VARCHAR(255) DEFAULT NULL, designation VARCHAR(255) DEFAULT NULL, abreviation VARCHAR(255) DEFAULT NULL, active INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ac_formation (id INT NOT NULL, etablissement_id INT DEFAULT NULL, code VARCHAR(255) DEFAULT NULL, designation VARCHAR(255) DEFAULT NULL, abreviation VARCHAR(255) DEFAULT NULL, active INT DEFAULT NULL, INDEX IDX_EF42FEE5FF631228 (etablissement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ac_promotion (id INT NOT NULL, formation_id INT DEFAULT NULL, code VARCHAR(255) DEFAULT NULL, designation VARCHAR(255) DEFAULT NULL, ordre INT DEFAULT NULL, active INT DEFAULT NULL, INDEX IDX_6E1FA28B5200282E (formation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ac_semestre (id INT NOT NULL, promotion_id INT DEFAULT NULL, code VARCHAR(255) DEFAULT NULL, designation VARCHAR(255) DEFAULT NULL, active INT DEFAULT NULL, INDEX IDX_79AC9915139DF194 (promotion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pgroupe (id INT NOT NULL, groupe_id INT DEFAULT NULL, niveau VARCHAR(10) DEFAULT NULL, INDEX IDX_C47F22D77A45358C (groupe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pstatut (id INT NOT NULL, code VARCHAR(255) NOT NULL, designation VARCHAR(255) DEFAULT NULL, abreviation VARCHAR(255) DEFAULT NULL, active INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tadmission (id INT AUTO_INCREMENT NOT NULL, preinscription_id INT DEFAULT NULL, code VARCHAR(255) DEFAULT NULL, INDEX IDX_E9A5B708337288 (preinscription_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tetudiant (id INT NOT NULL, code VARCHAR(255) DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, mail1 VARCHAR(255) DEFAULT NULL, cin VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tinscription (id INT NOT NULL, statut_id INT DEFAULT NULL, admission_id INT DEFAULT NULL, annee_id INT DEFAULT NULL, promotion_id INT DEFAULT NULL, groupe_id INT DEFAULT NULL, code VARCHAR(255) DEFAULT NULL, INDEX IDX_698E3BDEF6203804 (statut_id), INDEX IDX_698E3BDE75C9C554 (admission_id), INDEX IDX_698E3BDE543EC5F0 (annee_id), INDEX IDX_698E3BDE139DF194 (promotion_id), INDEX IDX_698E3BDE7A45358C (groupe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tpreinscription (id INT NOT NULL, etudiant_id INT NOT NULL, annee_id INT DEFAULT NULL, code VARCHAR(255) DEFAULT NULL, inscription_valide SMALLINT DEFAULT NULL, active SMALLINT DEFAULT NULL, INDEX IDX_EED29A80DDEAB1A3 (etudiant_id), INDEX IDX_EED29A80543EC5F0 (annee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE us_module (id INT AUTO_INCREMENT NOT NULL, designation VARCHAR(255) NOT NULL, prefix VARCHAR(255) DEFAULT NULL, icon VARCHAR(255) DEFAULT NULL, ordre INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE us_operation (id INT AUTO_INCREMENT NOT NULL, sous_module_id INT DEFAULT NULL, link VARCHAR(255) DEFAULT NULL, icon VARCHAR(255) DEFAULT NULL, designation VARCHAR(255) DEFAULT NULL, class_tag VARCHAR(255) DEFAULT NULL, id_tag VARCHAR(255) DEFAULT NULL, ordre INT DEFAULT NULL, align TINYINT(1) DEFAULT NULL, INDEX IDX_176B6B7FB4CB5EDE (sous_module_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE us_operation_user (us_operation_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_61201196585E0F6 (us_operation_id), INDEX IDX_6120119A76ED395 (user_id), PRIMARY KEY(us_operation_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE us_sous_module (id INT AUTO_INCREMENT NOT NULL, module_id INT DEFAULT NULL, link VARCHAR(255) DEFAULT NULL, designation VARCHAR(255) DEFAULT NULL, prefix VARCHAR(255) DEFAULT NULL, ordre INT NOT NULL, INDEX IDX_411A14EBAFC2B591 (module_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, etudiant_id INT DEFAULT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, enable TINYINT(1) NOT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, created DATETIME DEFAULT NULL, INDEX IDX_8D93D649DDEAB1A3 (etudiant_id), UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ac_annee ADD CONSTRAINT FK_2655A1C45200282E FOREIGN KEY (formation_id) REFERENCES ac_formation (id)');
        $this->addSql('ALTER TABLE ac_formation ADD CONSTRAINT FK_EF42FEE5FF631228 FOREIGN KEY (etablissement_id) REFERENCES ac_etablissement (id)');
        $this->addSql('ALTER TABLE ac_promotion ADD CONSTRAINT FK_6E1FA28B5200282E FOREIGN KEY (formation_id) REFERENCES ac_formation (id)');
        $this->addSql('ALTER TABLE ac_semestre ADD CONSTRAINT FK_79AC9915139DF194 FOREIGN KEY (promotion_id) REFERENCES ac_promotion (id)');
        $this->addSql('ALTER TABLE pgroupe ADD CONSTRAINT FK_C47F22D77A45358C FOREIGN KEY (groupe_id) REFERENCES pgroupe (id)');
        $this->addSql('ALTER TABLE tadmission ADD CONSTRAINT FK_E9A5B708337288 FOREIGN KEY (preinscription_id) REFERENCES tpreinscription (id)');
        $this->addSql('ALTER TABLE tinscription ADD CONSTRAINT FK_698E3BDEF6203804 FOREIGN KEY (statut_id) REFERENCES pstatut (id)');
        $this->addSql('ALTER TABLE tinscription ADD CONSTRAINT FK_698E3BDE75C9C554 FOREIGN KEY (admission_id) REFERENCES tadmission (id)');
        $this->addSql('ALTER TABLE tinscription ADD CONSTRAINT FK_698E3BDE543EC5F0 FOREIGN KEY (annee_id) REFERENCES ac_annee (id)');
        $this->addSql('ALTER TABLE tinscription ADD CONSTRAINT FK_698E3BDE139DF194 FOREIGN KEY (promotion_id) REFERENCES ac_promotion (id)');
        $this->addSql('ALTER TABLE tinscription ADD CONSTRAINT FK_698E3BDE7A45358C FOREIGN KEY (groupe_id) REFERENCES pgroupe (id)');
        $this->addSql('ALTER TABLE tpreinscription ADD CONSTRAINT FK_EED29A80DDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES tetudiant (id)');
        $this->addSql('ALTER TABLE tpreinscription ADD CONSTRAINT FK_EED29A80543EC5F0 FOREIGN KEY (annee_id) REFERENCES ac_annee (id)');
        $this->addSql('ALTER TABLE us_operation ADD CONSTRAINT FK_176B6B7FB4CB5EDE FOREIGN KEY (sous_module_id) REFERENCES us_sous_module (id)');
        $this->addSql('ALTER TABLE us_operation_user ADD CONSTRAINT FK_61201196585E0F6 FOREIGN KEY (us_operation_id) REFERENCES us_operation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE us_operation_user ADD CONSTRAINT FK_6120119A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE us_sous_module ADD CONSTRAINT FK_411A14EBAFC2B591 FOREIGN KEY (module_id) REFERENCES us_module (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649DDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES tetudiant (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ac_annee DROP FOREIGN KEY FK_2655A1C45200282E');
        $this->addSql('ALTER TABLE ac_formation DROP FOREIGN KEY FK_EF42FEE5FF631228');
        $this->addSql('ALTER TABLE ac_promotion DROP FOREIGN KEY FK_6E1FA28B5200282E');
        $this->addSql('ALTER TABLE ac_semestre DROP FOREIGN KEY FK_79AC9915139DF194');
        $this->addSql('ALTER TABLE pgroupe DROP FOREIGN KEY FK_C47F22D77A45358C');
        $this->addSql('ALTER TABLE tadmission DROP FOREIGN KEY FK_E9A5B708337288');
        $this->addSql('ALTER TABLE tinscription DROP FOREIGN KEY FK_698E3BDEF6203804');
        $this->addSql('ALTER TABLE tinscription DROP FOREIGN KEY FK_698E3BDE75C9C554');
        $this->addSql('ALTER TABLE tinscription DROP FOREIGN KEY FK_698E3BDE543EC5F0');
        $this->addSql('ALTER TABLE tinscription DROP FOREIGN KEY FK_698E3BDE139DF194');
        $this->addSql('ALTER TABLE tinscription DROP FOREIGN KEY FK_698E3BDE7A45358C');
        $this->addSql('ALTER TABLE tpreinscription DROP FOREIGN KEY FK_EED29A80DDEAB1A3');
        $this->addSql('ALTER TABLE tpreinscription DROP FOREIGN KEY FK_EED29A80543EC5F0');
        $this->addSql('ALTER TABLE us_operation DROP FOREIGN KEY FK_176B6B7FB4CB5EDE');
        $this->addSql('ALTER TABLE us_operation_user DROP FOREIGN KEY FK_61201196585E0F6');
        $this->addSql('ALTER TABLE us_operation_user DROP FOREIGN KEY FK_6120119A76ED395');
        $this->addSql('ALTER TABLE us_sous_module DROP FOREIGN KEY FK_411A14EBAFC2B591');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649DDEAB1A3');
        $this->addSql('DROP TABLE ac_annee');
        $this->addSql('DROP TABLE ac_etablissement');
        $this->addSql('DROP TABLE ac_formation');
        $this->addSql('DROP TABLE ac_promotion');
        $this->addSql('DROP TABLE ac_semestre');
        $this->addSql('DROP TABLE pgroupe');
        $this->addSql('DROP TABLE pstatut');
        $this->addSql('DROP TABLE tadmission');
        $this->addSql('DROP TABLE tetudiant');
        $this->addSql('DROP TABLE tinscription');
        $this->addSql('DROP TABLE tpreinscription');
        $this->addSql('DROP TABLE us_module');
        $this->addSql('DROP TABLE us_operation');
        $this->addSql('DROP TABLE us_operation_user');
        $this->addSql('DROP TABLE us_sous_module');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
