<?php

declare(strict_types=1);

namespace FATCHIP\ObjectCodeK3\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220810101500 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql("ALTER TABLE `oxorderarticles`
          ADD COLUMN `FCK3OBJECTCODECFG` varchar(255) NOT NULL
          DEFAULT '' COMMENT 'K3 ObjectCode configuration id'
          ;");

        $this->addSql("ALTER TABLE `oxorderarticles`
          ADD COLUMN `FCK3OBJECTCODEAPP` varchar(255) NOT NULL
          DEFAULT '' COMMENT 'K3 ObjectCode app code'
          ;");
    }

    public function down(Schema $schema): void
    {
    }
}
