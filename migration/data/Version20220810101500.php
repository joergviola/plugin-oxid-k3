<?php

declare(strict_types=1);

namespace ObjectCode\K3\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220810101500 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql("ALTER TABLE `oxorderarticles`
          ADD COLUMN `OCK3CFG` varchar(255) NOT NULL
          DEFAULT '' COMMENT 'ObjectCode K3 configuration id'
          ;");

        $this->addSql("ALTER TABLE `oxorderarticles`
          ADD COLUMN `OCK3CODEAPP` varchar(255) NOT NULL
          DEFAULT '' COMMENT 'ObjectCode K3 app code'
          ;");
    }

    public function down(Schema $schema): void
    {
    }
}
