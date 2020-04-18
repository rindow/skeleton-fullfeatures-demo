<?php
namespace Acme\MyApp\Repository\Sql\SchemaBuilder;

class Mysql
{
    public static function createSchema(array $tableNames)
    {
        // component: 'Acme\\App\\Repository\\Sql\\CategorySqlRepository'
        $categories = $tableNames['categories'];
        $sqls[] = "CREATE TABLE IF NOT EXISTS {$categories} (".
                " id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,".
                " name VARCHAR(255) NOT NULL UNIQUE".
                ")".
                " ENGINE = InnoDB";

        // component: 'Acme\\App\\Repository\\Sql\\ProductSqlRepository'
        $products = $tableNames['products'];
        $colors = $tableNames['colors'];
        $sqls[] = "CREATE TABLE IF NOT EXISTS {$products} (".
                " id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,".
                " category BIGINT UNSIGNED NOT NULL,".
                " name VARCHAR(255) NOT NULL UNIQUE".
                ")".
                " ENGINE = InnoDB";
        $sqls[] = "CREATE TABLE IF NOT EXISTS {$colors} (".
                " id INTEGER PRIMARY KEY AUTOINCREMENT,".
                " product BIGINT UNSIGNED NOT NULL,".
                " color BIGINT UNSIGNED NOT NULL".
                ")".
                " ENGINE = InnoDB";
        $sqls[] = "CREATE UNIQUE INDEX {$colors}_unique".
                " ON {$colors} (product,color)";

        // component: 'Rindow\\Security\\Core\\Authentication\\DefaultUserDetailsSqlRepository'
        $authusers = $tableNames['rindow_authusers'];
        $authorities = $tableNames['rindow_authorities'];
        $sqls[] = "CREATE TABLE IF NOT EXISTS {$authUsers} (".
                " id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,".
                " username VARCHAR(255) NOT NULL UNIQUE,".
                " password VARCHAR(255),".
                " disabled INTEGER,".
                " accountExpirationDate INTEGER,".
                " lastPasswordChangeDate INTEGER,".
                " lockExpirationDate INTEGER".
                ")".
                " ENGINE = InnoDB";

        $sqls[] = "CREATE TABLE IF NOT EXISTS {$authorities} (".
                " userid BIGINT UNSIGNED NOT NULL,".
                " authority VARCHAR(255) NOT NULL".
                ")";
        $sqls[] = "CREATE INDEX {$authorities}_userid ON {$authorities} (userid)";
        $sqls[] = "CREATE UNIQUE INDEX {$authorities}_unique ON {$authorities} (userid,authority)";
        $sqls[] = "CREATE INDEX {$authorities}_authority ON {$authorities} (authority)";
        return $sqls;
    }

    public static function dropSchema(array $tableNames)
    {
        $products = $tableNames['products'];
        $sqls[] = "DROP TABLE IF EXISTS {$products}";
        $categories = $tableNames['categories'];
        $sqls[] = "DROP TABLE IF EXISTS {$categories}";
        $colors = $tableNames['colors'];
        $sqls[] = "DROP TABLE IF EXISTS {$colors}";
        $authUsers = $tableNames['rindow_authusers'];
        $sqls[] = "DROP TABLE IF EXISTS {$authUsers}";
        $authorities = $tableNames['rindow_authorities'];
        $sqls[] = "DROP TABLE IF EXISTS {$authorities}";
        return $sqls;
    }
}
