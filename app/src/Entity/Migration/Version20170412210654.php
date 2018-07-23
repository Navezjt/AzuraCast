<?php

namespace Entity\Migration;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Add relay URL to mountpoint table, update Shoutcast 2 stations to have one default mount point.
 */
final class Version20170412210654 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE station_mounts ADD relay_url VARCHAR(255) DEFAULT NULL');
    }

    public function postUp(Schema $schema)
    {
        $all_stations = $this->connection->fetchAll("SELECT * FROM station WHERE frontend_type='shoutcast2'");

        foreach ($all_stations as $station) {
            $this->connection->insert('station_mounts', [
                'station_id' => $station['id'],
                'name' => '/radio.mp3',
                'is_default' => 1,
                'fallback_mount' => '/autodj.mp3',
                'enable_streamers' => 1,
                'enable_autodj' => 1,
                'autodj_format' => 'mp3',
                'autodj_bitrate' => 128,
            ]);
        }
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE station_mounts DROP relay_url');
    }
}
