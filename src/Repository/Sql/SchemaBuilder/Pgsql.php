<?php
namespace Acme\MyApp\Repository\Sql\SchemaBuilder;

class Pgsql
{
    public static function createSchema(array $tableNames)
    {
        // component: 'Acme\\App\\Repository\\Sql\\CategorySqlRepository'
        $categories = $tableNames['categories'];
        $sqls[] = "CREATE TABLE IF NOT EXISTS {$categories} (".
                " id BIGSERIAL PRIMARY KEY,".
                " name VARCHAR(255) NOT NULL UNIQUE".
                ")";

        // component: 'Acme\\App\\Repository\\Sql\\ProductSqlRepository'
        $products = $tableNames['products'];
        $colors = $tableNames['colors'];
        $sqls[] = "CREATE TABLE IF NOT EXISTS {$products} (".
                " id BIGSERIAL PRIMARY KEY,".
                " category BIGINT NOT NULL,".
                " name VARCHAR(255) NOT NULL UNIQUE".
                ")";
        $sqls[] = "CREATE TABLE IF NOT EXISTS {$colors} (".
                " id BIGSERIAL PRIMARY KEY,".
                " product BIGINT NOT NULL,".
                " color BIGINT NOT NULL".
                ")";
        $sqls[] = "CREATE UNIQUE INDEX {$colors}_unique".
                " ON {$colors} (product,color)";

        $authUsers = $tableNames['rindow_authusers'];
        $sqls[] = "CREATE TABLE IF NOT EXISTS {$authUsers} (".
                " id BIGSERIAL PRIMARY KEY,".
                " username VARCHAR(255) NOT NULL UNIQUE,".
                " password VARCHAR(255),".
                " disabled INTEGER,".
                " accountExpirationDate INTEGER,".
                " lastPasswordChangeDate INTEGER,".
                " lockExpirationDate INTEGER".
                ")";

        $authorities = $tableNames['rindow_authorities'];
        $sqls[] = "CREATE TABLE IF NOT EXISTS {$authorities} (".
                " userid BIGINT NOT NULL,".
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
