<?php
// ☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★

require_once "defineUtil.php";
require_once "scriptUtil.php";

// ------------------------------------------------------------------------
class Db_access {
    protected function pdo_connect() {
        $pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }
    protected function pdo_disconnect($pdo_object) {
        $pdo_object = null;
    }
    public function select_all() {
        try {
            $pdo = $this->pdo_connect();
            // 検索処理
            // ...
            return $result;
        } catch (PDOException $e) {
            $this->pdo_disconnect($pdo);
            return $e->getMessage();
        }
    }
    public function delete() {
        try {
            $pdo = $this->pdo_connect();
            // 削除処理
            // ...
            return $result;
        } catch (PDOException $e) {
            $this->pdo_disconnect($pdo);
            return $e->getMessage();
        }
    }
    public function insert() {
        try {
            $pdo = $this->pdo_connect();
            // 挿入処理
            // ...
            return $result;
        } catch (PDOException $e) {
            $this->pdo_disconnect($pdo);
            return $e->getMessage();
        }
    }
}












//
