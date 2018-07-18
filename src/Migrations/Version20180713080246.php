<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180713080246 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE history_movie (id INT AUTO_INCREMENT NOT NULL, history_id INT NOT NULL, movie_id INT NOT NULL, note INT DEFAULT NULL, INDEX IDX_44E6FA081E058452 (history_id), INDEX IDX_44E6FA088F93B6FC (movie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE watchlist (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, date_create DATETIME NOT NULL, UNIQUE INDEX UNIQ_340388D3A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE watchlist_movie (watchlist_id INT NOT NULL, movie_id INT NOT NULL, INDEX IDX_B38D698383DD0D94 (watchlist_id), INDEX IDX_B38D69838F93B6FC (movie_id), PRIMARY KEY(watchlist_id, movie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE history_movie ADD CONSTRAINT FK_44E6FA081E058452 FOREIGN KEY (history_id) REFERENCES history (id)');
        $this->addSql('ALTER TABLE history_movie ADD CONSTRAINT FK_44E6FA088F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id)');
        $this->addSql('ALTER TABLE watchlist ADD CONSTRAINT FK_340388D3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE watchlist_movie ADD CONSTRAINT FK_B38D698383DD0D94 FOREIGN KEY (watchlist_id) REFERENCES watchlist (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE watchlist_movie ADD CONSTRAINT FK_B38D69838F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE history ADD user_id INT DEFAULT NULL, DROP note');
        $this->addSql('ALTER TABLE history ADD CONSTRAINT FK_27BA704BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_27BA704BA76ED395 ON history (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE watchlist_movie DROP FOREIGN KEY FK_B38D698383DD0D94');
        $this->addSql('DROP TABLE history_movie');
        $this->addSql('DROP TABLE watchlist');
        $this->addSql('DROP TABLE watchlist_movie');
        $this->addSql('ALTER TABLE history DROP FOREIGN KEY FK_27BA704BA76ED395');
        $this->addSql('DROP INDEX UNIQ_27BA704BA76ED395 ON history');
        $this->addSql('ALTER TABLE history ADD note VARCHAR(250) DEFAULT NULL COLLATE utf8mb4_unicode_ci, DROP user_id');
    }
}
