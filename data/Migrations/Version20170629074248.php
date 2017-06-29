<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170629074248 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // Create table 'group'
        $table = $schema->createTable('`group`');
        $table->addColumn('groupId', 'integer', ['autoincrement'=>true]);
        $table->addColumn('name', 'string', ['notnull' => true, 'length' => '100']);
        $table->setPrimaryKey(['groupId']);
        $table->addOption('engine', 'InnoDB');

        // Create table 'consumer'
        $table = $schema->createTable('`consumer`');
        $table->addColumn(
            'consumerId',
            'integer',
            ['autoincrement'=>true]
        );
        $table->addColumn('groupId', 'integer');
        $table->addColumn(
            'login',
            'string',
            ['notnull' => true, 'length' => 100]
        );
        $table->addColumn(
            'password',
            'string',
            ['notnull' => true, 'length' => 32, 'fixed' => true]
        );
        $table->addColumn(
            'email',
            'string',
            ['notnull' => true, 'length' => 255]
        );
        $table->addColumn(
            'expirationDateTime',
            'integer'
        );
        $table->addColumn(
            'imageExtention',
            'string',
            ['length' => 5]
        );
        $table->setPrimaryKey(['consumerId']);
        $table->addForeignKeyConstraint(
            '`group`',
            ['groupId'],
            ['groupId'],
            ['onUpdate' => 'cascade']
        );
        $table->addOption('engine', 'InnoDB');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('`consumer`');
        $schema->dropTable('`group`');
    }
}
