<?php
declare(strict_types=1);
namespace CamHobbs\Kudos\Core;

use CamHobbs\Kudos\Interfaces\Database;

class Store extends Database
{
    use Logger;

    private const DB_PREFIX = "mongo";
    private $databaseName;

    function __construct(App $app)
    {
      parent::__construct($app, Store::DB_PREFIX);
      $this->databaseName = (isset($app->getConfig()[$this->getConfigKey()]["name"])) ? $app->getConfig()[$this->getConfigKey()]["name"] : "kudos";
    }

    function __get($name)
    {
      if(\property_exists($this, $name)) {
        return $this->$name;
      } else {
        if($this->db !== null) {
          $dbName = $this->databaseName;
          return $this->db->$dbName->$name;
        } else {
          throw new Exception("Database is not connected yet so cannot access collection $name");
        }
      }
    }

    function connect()
    {
      return parent::connect()->then(function($localhost) {
        try {
          $this->log("Attempting connection to mongo db..");
          if($localhost) {
            $this->db = new \MongoDB\Client;
          } else {
            $this->db = new \MongoDB\Client("mongodb://" . $this->getHost() . ":" . $this->getPort());
          }
          $this->log("Successfully connected to mongo db.");
        } catch(\Exception $e) {
          $this->log("Issue connecting to mongo");
        }
      });
    }

    function disconnect()
    {
      return parent::disconnect()->then(function() {
        $this->db = null;
      });
    }
}
