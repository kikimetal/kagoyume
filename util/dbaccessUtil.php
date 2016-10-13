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
    // 第３引数に返して欲しいカラムを指定できる、未指定の場合、純粋なSELECT * になる
    // 成功すれば配列を、エラーならエラーを返す
    public function select($table, $array=null ,$column=null) {
        try {
            $pdo = $this->pdo_connect();
            if($column){
                $sql = "SELECT $column FROM $table";
            }else{
                $sql = "SELECT * FROM $table";
            }

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



    // 第１引数にテーブル名、第２引数に挿入したい カラム名 => 値 になるように組まれた配列を入れる
    // 第３引数は登録する現在時刻のカラム名（newDate or buyDate）
    // 成功すれば null を、エラーならエラーを返す
    public function insert($table, $array, $create_time) {
        try {
            $pdo = $this->pdo_connect();
            $sql = "INSERT INTO $table";

            // 現在時をdatetime型で取得
            $datetime = new DateTime();
            $date = $datetime->format('Y-m-d H:i:s');

            if($array){
                $sql .= " (";
                $and_flg = false;
                foreach ($array as $key => $value) {
                    if($and_flg){
                        $sql .= ", ";
                    }
                    $sql .= $key." ";
                    $and_flg = true;
                }
                $sql .= ", $create_time)";

                $sql .= " VALUES";
                $sql .= " (";
                $and_flg = false;
                foreach ($array as $key => $value) {
                    if($and_flg){
                        $sql .= ", ";
                    }
                    $sql .= ":".$key;
                    $and_flg = true;
                }
                $sql .= ", :newDate)";
            }

            // var_dump($sql);

            $stmt = $pdo->prepare($sql);

            if($array){
                foreach ($array as $key => $value) {
                    $stmt->bindValue(":".$key, $value, PDO::PARAM_STR);
                }
                $stmt->bindValue(":newDate", $date, PDO::PARAM_STR);
            }

            $stmt->execute();
            return null;

        } catch (PDOException $e) {
            $this->pdo_disconnect($pdo);
            return $e->getMessage();
        }
    }

    // 第１引数にテーブル名、第２引数にアップデートするカラム名 第３引数にアップデーとする値
    // 第４引数に そのテーブルでの主キーカラムのネーム、第５にその値
    // 成功で null エラーでエラーを返す
    public function update($table, $column, $new_value, $p_key_name, $p_key_value){
        try {
            $pdo = $this->pdo_connect();
            $sql = "UPDATE $table SET $column = :$column WHERE $p_key_name = :$p_key_name";

            // var_dump($sql);
            $stmt = $pdo->prepare($sql);

            $stmt->bindValue(":$column", $new_value, PDO::PARAM_INT);
            $stmt->bindValue(":$p_key_name", $p_key_value, PDO::PARAM_INT);

            $stmt->execute();
            return null;

        } catch (PDOException $e) {
            $this->pdo_disconnect($pdo);
            return $e->getMessage();
        }

    }


} // class DB









//
