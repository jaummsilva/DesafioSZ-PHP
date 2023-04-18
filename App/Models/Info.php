<?php

namespace App\Models;

use MF\Model\Model;
use App\Connection;

class Info extends Model
{
  private $id;
  private $secret_key;
  private $public_key;
  private $authorization;

  private $device_token;
  private $slack_url;
  private $url;
  public function __get($name)
  {
    return $this->$name;
  }
  public function __set($name, $value)
  {
    $this->$name = $value;
  }

  public function getInfo()
  {
    $query = "select * from info";
    $smtm = $this->db->prepare($query);
    $smtm->execute();
    return $smtm->fetchAll(\PDO::FETCH_ASSOC);
  }
}
