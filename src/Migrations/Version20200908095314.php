<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200908095314 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE demande (id INT AUTO_INCREMENT NOT NULL, diplome_id INT DEFAULT NULL, secretaire_id INT DEFAULT NULL, entreprise_id INT DEFAULT NULL, laureat_id INT DEFAULT NULL, etablissement_id INT DEFAULT NULL, directeur_pedagogique_id INT DEFAULT NULL, etat_secretaire INT DEFAULT 2 NOT NULL, etat_directeur_pd INT DEFAULT 2 NOT NULL, etat_directeur_gn INT DEFAULT 2 NOT NULL, date_validation_secretaire DATE DEFAULT NULL, date_validation_dp DATE DEFAULT NULL, date_validation_de DATE DEFAULT NULL, INDEX IDX_2694D7A526F859E2 (diplome_id), INDEX IDX_2694D7A5A90F02B2 (secretaire_id), INDEX IDX_2694D7A5A4AEAFEA (entreprise_id), INDEX IDX_2694D7A57D38AC64 (laureat_id), INDEX IDX_2694D7A5FF631228 (etablissement_id), INDEX IDX_2694D7A5F64B851A (directeur_pedagogique_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE diplome (id INT AUTO_INCREMENT NOT NULL, type_id INT DEFAULT NULL, specialite_id INT DEFAULT NULL, code VARCHAR(255) NOT NULL, fichier VARCHAR(255) NOT NULL, date_obtention DATE DEFAULT NULL, date_depot DATE NOT NULL, annee DATE NOT NULL, INDEX IDX_EB4C4D4EC54C8C93 (type_id), INDEX IDX_EB4C4D4E2195E0F0 (specialite_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE directeur_pedagogique (id INT NOT NULL, etablissement_id INT DEFAULT NULL, INDEX IDX_7C33481FFF631228 (etablissement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, addresse VARCHAR(255) DEFAULT NULL, telephone VARCHAR(255) NOT NULL, datenaissance DATE DEFAULT NULL, photo_url VARCHAR(255) DEFAULT NULL, pseudo VARCHAR(255) DEFAULT NULL, deleted TINYINT(1) NOT NULL, type VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entreprise (id INT NOT NULL, nom_entreprise VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etablissement (id INT NOT NULL, pays_id INT DEFAULT NULL, nom_etablissement VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_20FD592CACA3E3B2 (nom_etablissement), INDEX IDX_20FD592CA6E44244 (pays_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE laureat (id INT NOT NULL, cin_num_sejour VARCHAR(255) DEFAULT NULL, lieu_naissance VARCHAR(255) DEFAULT NULL, nationalite VARCHAR(255) DEFAULT NULL, nom_arabe VARCHAR(255) DEFAULT NULL, prenom_arabe VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pays (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE secretaire (id INT NOT NULL, etablissement_id INT DEFAULT NULL, INDEX IDX_7DB5C2D0FF631228 (etablissement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE signalisation (id INT AUTO_INCREMENT NOT NULL, laureat_id INT DEFAULT NULL, etablissement_id INT DEFAULT NULL, date DATE NOT NULL, INDEX IDX_1BD243CD7D38AC64 (laureat_id), INDEX IDX_1BD243CDFF631228 (etablissement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE specialite (id INT AUTO_INCREMENT NOT NULL, designation VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_diplome (id INT AUTO_INCREMENT NOT NULL, designation VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE demande ADD CONSTRAINT FK_2694D7A526F859E2 FOREIGN KEY (diplome_id) REFERENCES diplome (id)');
        $this->addSql('ALTER TABLE demande ADD CONSTRAINT FK_2694D7A5A90F02B2 FOREIGN KEY (secretaire_id) REFERENCES secretaire (id)');
        $this->addSql('ALTER TABLE demande ADD CONSTRAINT FK_2694D7A5A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id)');
        $this->addSql('ALTER TABLE demande ADD CONSTRAINT FK_2694D7A57D38AC64 FOREIGN KEY (laureat_id) REFERENCES laureat (id)');
        $this->addSql('ALTER TABLE demande ADD CONSTRAINT FK_2694D7A5FF631228 FOREIGN KEY (etablissement_id) REFERENCES etablissement (id)');
        $this->addSql('ALTER TABLE demande ADD CONSTRAINT FK_2694D7A5F64B851A FOREIGN KEY (directeur_pedagogique_id) REFERENCES directeur_pedagogique (id)');
        $this->addSql('ALTER TABLE diplome ADD CONSTRAINT FK_EB4C4D4EC54C8C93 FOREIGN KEY (type_id) REFERENCES type_diplome (id)');
        $this->addSql('ALTER TABLE diplome ADD CONSTRAINT FK_EB4C4D4E2195E0F0 FOREIGN KEY (specialite_id) REFERENCES specialite (id)');
        $this->addSql('ALTER TABLE directeur_pedagogique ADD CONSTRAINT FK_7C33481FFF631228 FOREIGN KEY (etablissement_id) REFERENCES etablissement (id)');
        $this->addSql('ALTER TABLE directeur_pedagogique ADD CONSTRAINT FK_7C33481FBF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE entreprise ADD CONSTRAINT FK_D19FA60BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE etablissement ADD CONSTRAINT FK_20FD592CA6E44244 FOREIGN KEY (pays_id) REFERENCES pays (id)');
        $this->addSql('ALTER TABLE etablissement ADD CONSTRAINT FK_20FD592CBF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE laureat ADD CONSTRAINT FK_8744C4B0BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE secretaire ADD CONSTRAINT FK_7DB5C2D0FF631228 FOREIGN KEY (etablissement_id) REFERENCES etablissement (id)');
        $this->addSql('ALTER TABLE secretaire ADD CONSTRAINT FK_7DB5C2D0BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE signalisation ADD CONSTRAINT FK_1BD243CD7D38AC64 FOREIGN KEY (laureat_id) REFERENCES laureat (id)');
        $this->addSql('ALTER TABLE signalisation ADD CONSTRAINT FK_1BD243CDFF631228 FOREIGN KEY (etablissement_id) REFERENCES etablissement (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE demande DROP FOREIGN KEY FK_2694D7A526F859E2');
        $this->addSql('ALTER TABLE demande DROP FOREIGN KEY FK_2694D7A5F64B851A');
        $this->addSql('ALTER TABLE directeur_pedagogique DROP FOREIGN KEY FK_7C33481FBF396750');
        $this->addSql('ALTER TABLE entreprise DROP FOREIGN KEY FK_D19FA60BF396750');
        $this->addSql('ALTER TABLE etablissement DROP FOREIGN KEY FK_20FD592CBF396750');
        $this->addSql('ALTER TABLE laureat DROP FOREIGN KEY FK_8744C4B0BF396750');
        $this->addSql('ALTER TABLE secretaire DROP FOREIGN KEY FK_7DB5C2D0BF396750');
        $this->addSql('ALTER TABLE demande DROP FOREIGN KEY FK_2694D7A5A4AEAFEA');
        $this->addSql('ALTER TABLE demande DROP FOREIGN KEY FK_2694D7A5FF631228');
        $this->addSql('ALTER TABLE directeur_pedagogique DROP FOREIGN KEY FK_7C33481FFF631228');
        $this->addSql('ALTER TABLE secretaire DROP FOREIGN KEY FK_7DB5C2D0FF631228');
        $this->addSql('ALTER TABLE signalisation DROP FOREIGN KEY FK_1BD243CDFF631228');
        $this->addSql('ALTER TABLE demande DROP FOREIGN KEY FK_2694D7A57D38AC64');
        $this->addSql('ALTER TABLE signalisation DROP FOREIGN KEY FK_1BD243CD7D38AC64');
        $this->addSql('ALTER TABLE etablissement DROP FOREIGN KEY FK_20FD592CA6E44244');
        $this->addSql('ALTER TABLE demande DROP FOREIGN KEY FK_2694D7A5A90F02B2');
        $this->addSql('ALTER TABLE diplome DROP FOREIGN KEY FK_EB4C4D4E2195E0F0');
        $this->addSql('ALTER TABLE diplome DROP FOREIGN KEY FK_EB4C4D4EC54C8C93');
        $this->addSql('DROP TABLE demande');
        $this->addSql('DROP TABLE diplome');
        $this->addSql('DROP TABLE directeur_pedagogique');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE entreprise');
        $this->addSql('DROP TABLE etablissement');
        $this->addSql('DROP TABLE laureat');
        $this->addSql('DROP TABLE pays');
        $this->addSql('DROP TABLE secretaire');
        $this->addSql('DROP TABLE signalisation');
        $this->addSql('DROP TABLE specialite');
        $this->addSql('DROP TABLE type_diplome');
    }
}
