<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250326161335 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE estudiante (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(150) NOT NULL, acudiente VARCHAR(150) NOT NULL, edad INT NOT NULL, genero VARCHAR(150) NOT NULL, fecha_creado DATETIME NOT NULL, fecha_actualizado DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE salon (id INT AUTO_INCREMENT NOT NULL, nombre_salon VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE estudiante_salon (salon_id INT NOT NULL, estudiante_id INT NOT NULL, INDEX IDX_7ED5D76A4C91BDE4 (salon_id), INDEX IDX_7ED5D76A59590C39 (estudiante_id), PRIMARY KEY(salon_id, estudiante_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE estudiante_salon ADD CONSTRAINT FK_7ED5D76A4C91BDE4 FOREIGN KEY (salon_id) REFERENCES salon (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE estudiante_salon ADD CONSTRAINT FK_7ED5D76A59590C39 FOREIGN KEY (estudiante_id) REFERENCES estudiante (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE estudiante_salon DROP FOREIGN KEY FK_7ED5D76A4C91BDE4');
        $this->addSql('ALTER TABLE estudiante_salon DROP FOREIGN KEY FK_7ED5D76A59590C39');
        $this->addSql('DROP TABLE estudiante');
        $this->addSql('DROP TABLE salon');
        $this->addSql('DROP TABLE estudiante_salon');
    }
}
