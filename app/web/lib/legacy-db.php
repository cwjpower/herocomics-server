<?php declare(strict_types=1);
final class LegacyDB {
  private PDO $pdo;
  public function __construct(array $c){
    $dsn="mysql:host={$c['host']};dbname={$c['name']};charset={$c['charset']}";
    $this->pdo=new PDO($dsn,$c['user'],$c['pass'],[
      PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC
    ]);
    $this->pdo->exec("SET NAMES {$c['charset']}");
  }
  public function getVar(string $sql,array $p=[]): mixed { $st=$this->pdo->prepare($sql);$st->execute($p);$r=$st->fetch(PDO::FETCH_NUM);return $r[0]??null; }
  public function getRow(string $sql,array $p=[]): ?array { $st=$this->pdo->prepare($sql);$st->execute($p);return $st->fetch()?:null; }
  public function getResults(string $sql,array $p=[]): array { $st=$this->pdo->prepare($sql);$st->execute($p);return $st->fetchAll(); }
}
function db_get_var($q,$p=[]){ global $db; return $db->getVar($q,$p); }
function db_get_row($q,$p=[]){ global $db; return $db->getRow($q,$p); }
function db_get_results($q,$p=[]){ global $db; return $db->getResults($q,$p); }