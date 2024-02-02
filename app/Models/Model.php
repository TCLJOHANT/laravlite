<?php 
namespace App\Models;
use mysqli;
class Model{
    protected $hostname = 'localhost';
    protected $database = 'ferre';
    protected $username = 'root';
    protected $password= '';
    protected $connection;
    protected $table;
    protected $query;
    protected $sql, $data=[],$params =null ,$orderBy = "";
    public function __construct() {
        $this->connection();
     }
    // public function __construct($table,$con){
       
    //     $this->connection = $con;
    //     $this->table = $table;
    // }

    public function connection(){
        $this->connection= new mysqli($this->hostname, $this->username, $this->password, $this->database);
        if ($this->connection->connect_error) {
            die("Conexión fallida: " . $this->connection->connect_error);
        }
    }

    public function query($sql,$data =[],$params = null){
        if($data){
            if($params == null){
                $params = str_repeat('s',count($data));
            }
            $stmt = $this->connection->prepare($sql);
            $stmt->bind_param($params,...$data);
            $stmt->execute();
            $this->query = $stmt->get_result();
        }else{
            $this->query = $this->connection->query($sql);
        }
        return $this;
     }

    public function where($column, $operator, $value= null){
        if($value == null){
            $value = $operator;
            $operator = '=';
        }
        if(empty($this->sql)){
            //SELECT * FROM table WHERE name = 'Juan'
            $this->sql = "SELECT SQL_CALC_FOUND_ROWS * FROM {$this->table} WHERE {$column} {$operator} ?";
            $this->data[] =$value;
            return $this;
        }else {
            $this->sql .= "AND {$column} {$operator} ?";
            $this->data[] = $value;
        }
    }   
    public function orderBy($column , $order = "ASC"){
        if(empty($this->orderBy)){
            $this->orderBy = " ORDER BY {$column} {$order}";
        }else{
            $this->orderBy .= ", {$column} {$order}";
        }
        return  $this; 
    }

    public function first(){
        //verificando si se ha ejecutado o no la sentencia previamente
        if (empty($this->query)) {
          if(empty($this->sql)){
              $this->sql = "SELECT * FROM {$this->table}";
          }
          $this->sql .= $this->orderBy; //concanetar con order by
          $this->query($this->sql,$this->data, $this->params);
       }
       return $this->query->fetch_assoc();
    }

    public function get(){
        //verificando si se ha ejecutado o no la sentencia previamente
        if (empty($this->query)) {
            if(empty($this->sql)){
                $this->sql = "SELECT * FROM {$this->table}";
            }
            $this->sql .= $this->orderBy; //concanetar con order by
           $this->query($this->sql,$this->data, $this->params);
        }
        return $this->query->fetch_all(MYSQLI_ASSOC);
    }
    public function paginate($cantidad = 15){
        //SELECT * FROM nameTable LIMIT 0,5;
     $page = isset($_GET['page'])? $_GET['page'] : 1 ;
     if ($this->sql) {
         $sql = $this->sql . ($this->orderBy ?? '') . " LIMIT " . ($page - 1) * $cantidad . ",{$cantidad}";
         $data = $this->query($sql,$this->data,$this->params)->get();
     }
     //si solo uso paginate ($model->paginate(4) se ejecurara el else)
     else{
         $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM {$this->table}  " . ($this->orderBy ?? '') . " LIMIT" . ($page -1)*$cantidad . ",{$cantidad}";
         $data = $this->query($sql)->get();
     }

     $totalRegistros = $this->query('SELECT FOUND_ROWS() as total')->first()['total'];

     $uri = $_SERVER['REQUEST_URI'];
     $uri = trim($uri,'/');
     if(strpos($uri, '?')){
         $uri = substr($uri, 0, strpos($uri, '?'));
     }
     $last_page = ceil($totalRegistros / $cantidad);
     return [
         'total' => $totalRegistros,
         'from' =>  ($page -1) * $cantidad + 1,
         'to' =>    ($page -1) * $cantidad + count($data),
         'next_page_url' => $page < $last_page ?  '/' . $uri . '?page=' . $page + 1 :null,
         'prev_page_url' => $page > 1 ? '/' . $uri . '?page=' . $page - 1 :null,
         'data' => $data,
     ];
 }
    



    //ALTAS EN DB
    public function all(){
        //SELECT * FROM nameTable
        $sql = "SELECT * FROM {$this->table}";
        return $this->query($sql)->get();
    }

    public function find($id){
        //SELECT * FROM nameTable WHERE id = 1
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        return $this->query($sql,[$id],'i')->first();
    }
    public function create($data) {
        // INSERT INTO table (name, email, phone) VALUES (?,?, ?)
        $columns = array_keys($data);
        $columns = implode(', ', $columns);
        $values = array_values($data);
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES (" . str_repeat('?, ',count($values) -1) . "?)";
        $this->query($sql,$values);
        // Obtención del ID del registro insertado
        $insert_id = $this->connection->insert_id;
        return $this->find($insert_id);
    }
   //MODIFICACIONES EN DB
   public function update ($id, $data){
        //UPDATE contacts SET name email ?, phone = ? WHERE id = 1
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "{$key} = ?";
        }
        $fields = implode(', ', $fields);
        $sql = "UPDATE {$this->table} SET {$fields} WHERE id = ?";
        $values = array_values ($data);
        $values[] = $id;
        $this->query($sql, $values);
        return $this->find($id);

    }
    //BAJAS EN DB
    public function delete ($id){
        //DELETE FROM table WHERE id = ?                          
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $this->query($sql,[$id],'i');
    }
    //
 
}