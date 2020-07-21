<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200719160830 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE diplome CHANGE type_id type_id INT DEFAULT NULL, CHANGE specialite_id specialite_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE signalisation CHANGE laureat_id laureat_id INT DEFAULT NULL, CHANGE etablissement_id etablissement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE directeur_pedagogique CHANGE etablissement_id etablissement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE nom nom VARCHAR(255) DEFAULT NULL, CHANGE prenom prenom VARCHAR(255) DEFAULT NULL, CHANGE addresse addresse VARCHAR(255) DEFAULT NULL, CHANGE datenaissance datenaissance DATE DEFAULT NULL, CHANGE photo_url photo_url VARCHAR(255) DEFAULT NULL, CHANGE pseudo pseudo VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE entreprise CHANGE nom_entreprise nom_entreprise VARCHAR(255) DEFAULT NULL, CHANGE logo logo VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE etablissement CHANGE pays_id pays_id INT DEFAULT NULL, CHANGE nom_etablissement nom_etablissement VARCHAR(255) DEFAULT NULL, CHANGE logo logo VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE laureat CHANGE cin_num_sejour cin_num_sejour VARCHAR(255) DEFAULT NULL, CHANGE lieu_naissance lieu_naissance VARCHAR(255) DEFAULT NULL, CHANGE nationalite nationalite VARCHAR(255) DEFAULT NULL, CHANGE nom_arabe nom_arabe VARCHAR(255) DEFAULT NULL, CHANGE prenom_arabe prenom_arabe VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE secretaire CHANGE etablissement_id etablissement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE demande ADD etat_directeur_pd INT DEFAULT 0 NOT NULL, ADD etat_directreur_gn INT DEFAULT 0 NOT NULL, CHANGE diplome_id diplome_id INT DEFAULT NULL, CHANGE secretaire_id secretaire_id INT DEFAULT NULL, CHANGE entreprise_id entreprise_id INT DEFAULT NULL, CHANGE laureat_id laureat_id INT DEFAULT NULL, CHANGE etablissement_id etablissement_id INT DEFAULT NULL, CHANGE directeur_pedagogique_id directeur_pedagogique_id INT DEFAULT NULL, CHANGE date_validation_secretaire date_validation_secretaire DATE DEFAULT NULL, CHANGE date_validation_dp date_validation_dp DATE DEFAULT NULL, CHANGE date_validation_de date_validation_de DATE DEFAULT NULL, CHANGE etat etat_secritaire INT DEFAULT 0 NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE demande ADD etat INT DEFAULT 0 NOT NULL, DROP etat_secritaire, DROP etat_directeur_pd, DROP etat_directreur_gn, CHANGE diplome_id diplome_id INT DEFAULT NULL, CHANGE secretaire_id secretaire_id INT DEFAULT NULL, CHANGE entreprise_id entreprise_id INT DEFAULT NULL, CHANGE laureat_id laureat_id INT DEFAULT NULL, CHANGE etablissement_id etablissement_id INT DEFAULT NULL, CHANGE directeur_pedagogique_id directeur_pedagogique_id INT DEFAULT NULL, CHANGE date_validation_secretaire date_validation_secretaire DATE DEFAULT \'NULL\', CHANGE date_validation_dp date_validation_dp DATE DEFAULT \'NULL\', CHANGE date_validation_de date_validation_de DATE DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE diplome CHANGE type_id type_id INT DEFAULT NULL, CHANGE specialite_id specialite_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE directeur_pedagogique CHANGE etablissement_id etablissement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE entreprise CHANGE nom_entreprise nom_entreprise VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE logo logo VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE etablissement CHANGE pays_id pays_id INT DEFAULT NULL, CHANGE nom_etablissement nom_etablissement VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE logo logo VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE laureat CHANGE cin_num_sejour cin_num_sejour VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE lieu_naissance lieu_naissance VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE nationalite nationalite VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE nom_arabe nom_arabe VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE prenom_arabe prenom_arabe VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE secretaire CHANGE etablissement_id etablissement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE signalisation CHANGE laureat_id laureat_id INT DEFAULT NULL, CHANGE etablissement_id etablissement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE nom nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE prenom prenom VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE addresse addresse VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE datenaissance datenaissance DATE DEFAULT \'NULL\', CHANGE photo_url photo_url VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE pseudo pseudo VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
