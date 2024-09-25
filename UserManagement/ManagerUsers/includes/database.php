<?php

if(!defined('_LEGIT'))
{
    die('Access Denied');
}

function query($sql, $data=[], $check = 'false'){
    global $conn;
    $result = false;
    try{
        $statement = $conn -> prepare($sql);
        
        if(!empty($data)){
           $result = $statement -> execute($data); 
        }
        else {
            $result = $statement -> execute();
        }

    }catch(Exception $exp){
        echo $exp -> getMessage().'<br>';
        echo 'File: '.$exp -> getFile().'<br>';
        echo 'Line: '.$exp -> getLine();
        die();
    }

    if($check)
    {
        return $statement;
    }
    return $result;
}

function insert($table, $data) {
    $key = array_keys($data);
    $fields = implode(',', $key);
    $values = ':'.implode(',:', $key);

    $sql = 'INSERT INTO '.$table.'('.$fields.')'. 'VALUES(' .$values.')';
    $results = query($sql, $data);
    return $results;
}

function update($table, $data, $condition = '') {
    $update = '';
    foreach($data as $key => $value){
        $update .= $key .'= :' .$key .',';
    }
    $update = trim($update,',');

    if(!empty($condition)){
        $sql = 'UPDATE '. $table .' SET '. $update . ' WHERE id =' . $condition;
    }
    else{
        $sql = 'UPDATE '. $table .' SET '. $update;
    }
    $results = query($sql, $data);
    return $results;
}

function delete($table, $condition=''){
    if(empty($condition)){
        $sql = 'DELETE FROM '. $table;
    }
    else{
        $sql = 'DELETE FROM '. $table. ' WHERE '. $condition;
    }
    $results = query($sql);
    return $results;
}

//get all data
function getRaw($sql){
    $results = query($sql,'',true);
    if(is_object($results))
    {
        $dataFetch = $results -> fetchAll(PDO::FETCH_ASSOC);
    } 
    return $dataFetch;
}

//get single data
function get_1Raw($sql){
    $results = query($sql,'',true);
    if(is_object($results))
    {
        $dataFetch = $results -> fetch(PDO::FETCH_ASSOC);
    } 
    return $dataFetch;
}

//count how many object inside table
function getRows($sql){
    $results = query($sql,'',true);
    if(!empty($results))
    {
        return $results -> rowCount();
    } 
}
