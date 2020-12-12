<?php

namespace App\Controllers;

use \PDO;
use \PDOException;

class CoinController
{

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function index()
    {
        $query = "SELECT * FROM currencies";
        $sql = $this->db->prepare($query);
        try {
            $sql->execute();
        } catch (PDOException $e) {
            echo $e;
        }

        $result = [];
        while($row = $sql->fetch(PDO::FETCH_ASSOC)){
            $result[] = $row;
        }

        echo json_encode($result);
    }
    /**
     * lista todas criptomonedas de la tabla favoritas
     */
    function list($params) {
        var_dump($params);
        /*
        $sql = $this->pdo->prepare("SELECT * FROM favorites");
        $sql->execute();

        $results = $sql->fetchAll(PDO::FETCH_ASSOC);
         */
        return json_encode(["message" => "Todo correcto"]);
    }

    public function show($currency)
    {
        $coin = $currency["currency"];

        
        $sql = $this->db->prepare($query);
        try {
            $sql->execute();
        } catch (PDOException $e) {
            echo $e;
        }

        $result = $sql->fetchAll();

        echo json_encode($result);
    }
    /**
     * Añade una criptomoneda a la tabla de favoritos
     */
    public function store($currency)
    {
        echo json_encode($currency);
    }

    /**
     * Añade una criptomoneda a la tabla de currencies
     */
    public function add_new($currency)
    {
        $query = "INSERT INTO currencies (identifier,symbol,name,link,image,price,description,high24,low24,change24) VALUES ('$currency->identifier','$currency->symbol','$currency->name', '$currency->link',  '$currency->image', '$currency->price','$currency->description','$currency->high24','$currency->low24','$currency->change24')";
        //echo $query;
        $sql = $this->db->prepare($query);
        $sql->execute();
        echo json_encode($currency);
    }
    /**
     * Elimina una criptomoneda de la tabla de favoritas
     */
    public function destroy()
    {

    }
}
