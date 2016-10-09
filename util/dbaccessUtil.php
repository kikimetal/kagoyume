<?php
// ☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★

require_once "defineUtil.php";
require_once "scriptUtil.php";

// ------------------------------------------------------------------------
class DBaccess {
    private function pdo_connect() {
        $pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }

    private function pdo_disconnect($pdo_object) {
        $pdo_object = null;
    }

    // SELECT * // 第１引数にテーブル名、第２引数に調べたい カラム名 => 値 になるように組まれた配列を入れる
    // 第２引数が未指定の場合、純粋なSELECT * になる
    // 成功すれば配列を、エラーならエラーを返す
    public function select_all($table, $array=null) {
        try {
            $pdo = $this->pdo_connect();
            $sql = "SELECT * FROM $table";

            if($array){
                $sql .= " WHERE";
                $and_flg = false;
                foreach ($array as $key => $value) {
                    if($and_flg){
                        $sql .= " and";
                    }
                    $sql .= " ".$key." = :".$key;
                    $and_flg = true;
                }
            }

            $stmt = $pdo->prepare($sql);

            if($array){
                foreach ($array as $key => $value) {
                    $stmt->bindValue(":".$key, $value, PDO::PARAM_STR);
                }
            }

            $stmt->execute();
            return $stmt->fetchall(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            $this->pdo_disconnect($pdo);
            return $e->getMessage();
        }
    }



    public function select_test($table) {
        try {
            $pdo = pdo_connect();

            $sql = "SELECT * FROM $table";

            $stmt = $pdo->prepare($sql);
            // $stmt->bindValue(":name", $name, PDO::PARAM_STR);
            // $stmt->bindValue(":pass", $password, PDO::PARAM_STR);

            $stmt->execute();
            return $stmt->fetchall(PDO::FETCH_ASSOC);

            // pdo_disconnect();

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


} // class DB









//
