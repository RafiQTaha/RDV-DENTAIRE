<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240819125655 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE act (id INT AUTO_INCREMENT NOT NULL, promotion_id INT DEFAULT NULL, designation VARCHAR(255) DEFAULT NULL, INDEX IDX_AFECF544139DF194 (promotion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rendezvous (id INT AUTO_INCREMENT NOT NULL, inscription_id INT DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, date DATETIME DEFAULT NULL, created DATETIME DEFAULT NULL, INDEX IDX_C09A9BA85DAC5993 (inscription_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rendezvous_act (rendezvous_id INT NOT NULL, act_id INT NOT NULL, INDEX IDX_8BD42A653345E0A3 (rendezvous_id), INDEX IDX_8BD42A65D1A55B28 (act_id), PRIMARY KEY(rendezvous_id, act_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE act ADD CONSTRAINT FK_AFECF544139DF194 FOREIGN KEY (promotion_id) REFERENCES ac_promotion (id)');
        $this->addSql('ALTER TABLE rendezvous ADD CONSTRAINT FK_C09A9BA85DAC5993 FOREIGN KEY (inscription_id) REFERENCES tinscription (id)');
        $this->addSql('ALTER TABLE rendezvous_act ADD CONSTRAINT FK_8BD42A653345E0A3 FOREIGN KEY (rendezvous_id) REFERENCES rendezvous (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rendezvous_act ADD CONSTRAINT FK_8BD42A65D1A55B28 FOREIGN KEY (act_id) REFERENCES act (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE act DROP FOREIGN KEY FK_AFECF544139DF194');
        $this->addSql('ALTER TABLE rendezvous DROP FOREIGN KEY FK_C09A9BA85DAC5993');
        $this->addSql('ALTER TABLE rendezvous_act DROP FOREIGN KEY FK_8BD42A653345E0A3');
        $this->addSql('ALTER TABLE rendezvous_act DROP FOREIGN KEY FK_8BD42A65D1A55B28');
        $this->addSql('DROP TABLE act');
        $this->addSql('DROP TABLE rendezvous');
        $this->addSql('DROP TABLE rendezvous_act');
    }
}
