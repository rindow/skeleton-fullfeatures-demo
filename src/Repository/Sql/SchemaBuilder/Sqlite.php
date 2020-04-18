<?php
namespace Acme\MyApp\Repository\Sql\SchemaBuilder;

class Sqlite
{
    public static function createSchema(array $tableNames)
    {
        // component: 'Acme\\App\\Repository\\Sql\\CategorySqlRepository'
        $categories = $tableNames['categories'];
        $sqls[] = "CREATE TABLE IF NOT EXISTS {$categories} (".
                " id INTEGER PRIMARY KEY AUTOINCREMENT,".
                " name TEXT UNIQUE NOT NULL".
                ")";

        // component: 'Acme\\App\\Repository\\Sql\\ProductSqlRepository'
        $products = $tableNames['products'];
        $colors = $tableNames['colors'];
        $sqls[] = "CREATE TABLE IF NOT EXISTS {$products} (".
                " id INTEGER PRIMARY KEY AUTOINCREMENT,".
                " category INTEGER NOT NULL,".
                " name TEXT UNIQUE NOT NULL".
                ")";
        $sqls[] = "CREATE TABLE IF NOT EXISTS {$colors} (".
                " id INTEGER PRIMARY KEY AUTOINCREMENT,".
                " product INTEGER NOT NULL,".
                " color INTEGER NOT NULL".
                ")";
        $sqls[] = "CREATE UNIQUE INDEX {$colors}_unique".
                " ON {$colors} (product,color)";

        // component: 'Rindow\\Security\\Core\\Authentication\\DefaultUserDetailsSqlRepository'
        $rindow_authusers = $tableNames['rindow_authusers'];
        $rindow_authorities = $tableNames['rindow_authorities'];
        $sqls[] = "CREATE TABLE {$rindow_authusers}".
                " (id INTEGER PRIMARY KEY AUTOINCREMENT,".
                " username TEXT UNIQUE,".
                " password TEXT,".
                " disabled INTEGER,".
                " accountExpirationDate INTEGER,".
                " lastPasswordChangeDate INTEGER,".
                " lockExpirationDate INTEGER".
                ")";
        $sqls[] = "CREATE TABLE {$rindow_authorities} (userid INTEGER,authority TEXT)";
        $sqls[] = "CREATE INDEX {$rindow_authorities}_userid ON {$rindow_authorities} (userid)";
        $sqls[] = "CREATE UNIQUE INDEX {$rindow_authorities}_unique ON {$rindow_authorities} (userid,authority)";

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
        $rindow_authusers = $tableNames['rindow_authusers'];
        $sqls[] = "DROP TABLE IF EXISTS {$rindow_authusers}";
        $rindow_authorities = $tableNames['rindow_authorities'];
        $sqls[] = "DROP TABLE IF EXISTS {$rindow_authorities}";
        return $sqls;
    }
}
