<?php

namespace App\Controllers;

use App\Config;
use \PDO;
use \PDOException;
use \Exception;

class SQLiteConnection
{
    protected $pdo;

    /**
     * return in instance of the PDO object that connects to the SQLite database
     * 
     * @param Boolean $migrate      Indica si debe ejecutarse la migración de las tablas.
     * 
     * @return \PDO
     */
    public function connect($migrate = false)
    {
        try {
            if ($this->pdo == null) {
                $this->pdo = new PDO("sqlite:" . Config::PATH_TO_SQLITE_FILE);

                if($migrate){
                    $this->migrate();
                }

                return $this->pdo;
            }
        } catch (PDOException $e) {
            print "    <p class=\"aviso\">Error: No puede conectarse con la base de datos.</p>\n";
            print "\n";
            print "    <p class=\"aviso\">Error: " . $e->getMessage() . "</p>\n";
            pie();
            exit();
        }

    }

    /**
     * Crea las tablas de la base de datos
     */
    private function migrate()
    {
        $create_table_currencies = 'CREATE TABLE IF NOT EXISTS "currencies" ("id"	INTEGER NOT NULL, "identifier" VARCHAR(80) UNIQUE, "symbol" VARCHAR(5), "name" VARCHAR(80), "link"	VARCHAR(128), "image" VARCHAR(255), "price" FLOAT, "description" TEXT, "high24"	float, "low24"	float, "change24" float, PRIMARY KEY("id" AUTOINCREMENT));';
        
        $create_table_favorites = "CREATE TABLE IF NOT EXISTS favorites (id VARCHAR(80) NOT NULL, name VARCHAR(80) NOT NULL,symbol VARCHAR(5) NULL)";

        $create_table_wallet = "CREATE TABLE IF NOT EXISTS wallet (id VARCHAR(80) NOT NULL PRIMARY KEY, name VARCHAR(80) NOT NULL, symbol VARCHAR(5) NOT NULL, investment FLOAT NOT NULL DEFAULT 0);";

        $create_table_tracing = "CREATE TABLE IF NOT EXISTS tracing (btc_value FLOAT NOT NULL, euro_value FLOAT NOT NULL, datetime DATETIME NOT NULL, currencie VARCHAR(80) NOT NULL, CONSTRAINT fk_tracing_favorites FOREIGN KEY (currencie) REFERENCES favorites (id) ON DELETE CASCADE ON UPDATE CASCADE);";

        $create_table_wallet_tracking = "CREATE TABLE IF NOT EXISTS wallet_tracking (datetime DATETIME NOT NULL, currencie VARCHAR(80) NOT NULL, euro_value FLOAT NULL)";

        $commands = [$create_table_currencies, $create_table_favorites, $create_table_wallet, $create_table_tracing, $create_table_wallet_tracking];

        if($this->pdo != null){
            var_dump($this->pdo);
            foreach ($commands as $command) {
                try{
                $this->pdo->exec($command);
                }catch(Exception $e){
                    var_dump($e);
                    continue;
                }
            }
        }
    }

    /**
     * Devuelve las tablas de la base de datos
     */
    public function get_tables() {

        $stmt = $this->pdo->query("SELECT name
                                   FROM sqlite_master
                                   WHERE type = 'table'
                                   ORDER BY name");
        $tables = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $tables[] = $row['name'];
        }

        return $tables;
    }

    /**
     * Cierra la conexión con la base de datos
     */
    public function close(){
        $this->pdo = null;
    }

}
