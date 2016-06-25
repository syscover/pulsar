<?php

class DbSqlite
{
    private $pdo;
    private static $dbUrl = "../../data/getaddress.db";

    public function __construct($dbUrl)
    {
        $filename   = realpath($dbUrl);
        $this->pdo  = $this->open($filename);
    }

    private function open($filename)
    {
        return new PDO('sqlite:' . $filename);
    }

    private function close()
    {
        $this->pdo = null;
    }

    public static function getCountries($lang)
    {
        $dbSQLite = new DbSqlite(DbSqlite::$dbUrl);

        $sql = "SELECT * FROM country_002 WHERE country_002.lang_id_002 = '" . $lang . "'";

        $stmt = $dbSQLite->pdo->query($sql);

        $objetcs = $stmt->fetchAll();

        $dbSQLite->close();

        return $objetcs;
    }

    public static function getTerritorialAreas1($country)
    {
        $dbSQLite = new DbSqlite(DbSqlite::$dbUrl);

        $sql = "SELECT * FROM territorial_area_1_003 WHERE territorial_area_1_003.country_id_003 = '" . $country . "'";

        $stmt = $dbSQLite->pdo->query($sql);

        $objetcs = $stmt->fetchAll();

        $dbSQLite->close();

        return $objetcs;
    }

    public static function getTerritorialAreas2($territorialArea1)
    {
        $dbSQLite = new DbSqlite(DbSqlite::$dbUrl);

        $sql = "SELECT * FROM territorial_area_2_004 WHERE territorial_area_2_004.territorial_area_1_id_004 = '" . $territorialArea1 . "'";

        $stmt = $dbSQLite->pdo->query($sql);

        $objetcs = $stmt->fetchAll();

        $dbSQLite->close();

        return $objetcs;
    }

    public static function getTerritorialAreas3($territorialArea2)
    {
        $dbSQLite = new DbSqlite(DbSqlite::$dbUrl);

        $sql = "SELECT * FROM territorial_area_3_005 WHERE territorial_area_3_005.territorial_area_2_id_005 = '" . $territorialArea2 . "'";

        $stmt = $dbSQLite->pdo->query($sql);

        $objetcs = $stmt->fetchAll();

        $dbSQLite->close();

        return $objetcs;
    }
}