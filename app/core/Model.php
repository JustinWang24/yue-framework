<?php
/**
 * This is the super model class
 * User: justinwang
 * Date: 8/8/18
 * Time: 3:12 PM
 */

namespace App\core;

use App\core\contracts\support\Jsonable;
use Carbon\Carbon;
use Medoo\Medoo;

class Model implements Jsonable
{
    /**
     * @var Medoo
     */
    private static $_db = null;

    /**
     * Define the table name
     * @var string
     */
    protected $tableName = '';

    /**
     * Define the primary key filed name
     * @var string
     */
    protected $idFieldName = 'id';

    /**
     * Cache of current record
     * @var array
     */
    protected $rowData = [];

    /**
     * Fields list that should not be output for toJson
     * @var array
     */
    protected $protectedFields = ['password'];

    /**
     * Is in the debug mode
     * @var bool
     */
    protected $_debug = false;

    /**
     * Where conditions go here
     * @var array
     */
    protected $_whereArray = [];

    /**
     * Buffer of all statements
     * @var array
     */
    protected $_statementsPool= [];

    public function __construct($id = null)
    {
        // Set the debug flag
        $this->_debug = env('DEV_MODE',true);

        // Setup db connection
        if(is_null(self::$_db)){
            self::$_db = new Medoo([
                'database_type' => env('DB_DRIVER','mysql'),
                'database_name' => env('DB_NAME','gekko'),
                'server'        => env('DB_HOST','localhost'),
                'username'      => env('DB_USER','root'),
                'password'      => env('DB_PASSWORD','root'),
                // optional
                'charset'       => env('DB_CHARSET','utf8'),
                'port'          => env('DB_PORT',3306),
                'logging'       => $this->_debug,
            ]);
        }

        // find the record if id is given
        if($id){
            $this->find($id);
        }

        return $this;
    }

    /**
     * Get Database instance, now is using Medoo as ORM
     * @return Medoo
     */
    public static function DB(){
        if(self::$_db){
            return self::$_db;
        }
        self::$_db = new Medoo([
            'database_type' => env('DB_DRIVER','mysql'),
            'database_name' => env('DB_NAME','gekko'),
            'server'        => env('DB_HOST','localhost'),
            'username'      => env('DB_USER','root'),
            'password'      => env('DB_PASSWORD','root'),
            // optional
            'charset'       => env('DB_CHARSET','utf8'),
            'port'          => env('DB_PORT',3306),
            'logging'       => env('DEV_MODE',true),
        ]);
        return self::$_db;
    }


    /**
     * retrieve all records
     * @return array|bool
     */
    public function all(){
        $db = self::DB();
        return $db->select(self::getTableName(),['*']);
    }

    /**
     * Get record primary ID value
     * @return mixed
     */
    public function getId(){
        return $this->rowData[$this->idFieldName];
    }

    /**
     * Get database table name
     * @return mixed
     */
    public function getTableName(){
        return $this->tableName;
    }

    /**
     * Set database table name
     * @param $name
     */
    public function setTableName($name){
        $this->tableName = $name;
    }

    /**
     * Save current model, base on if ID is valid, it insert or update all fields
     * @return boolean|Model
     */
    public function save(){
        if(isset($this->rowData[$this->idFieldName]) && !empty($this->rowData[$this->idFieldName])){
            return $this->update();
        }else{
            return $this->insert();
        }
    }

    /**
     * Get row by given primary key value
     * If the record is exist, it will assign to rowData
     * @param $id
     * @param array|string $fields
     * @return $this|null
     */
    public function find($id,$fields='*'){
        $result = self::DB()->get(
            $this->tableName,
            $fields,
            [$this->idFieldName=>$id]
        );
        if($result){
            $this->rowData = $result;
        }
        return $this;
    }

    /**
     * Find the first one
     * @param $whereConditions
     * @param string $fields
     * @return $this
     */
    public function first($whereConditions,$fields='*'){
        $whereConditions['LIMIT'] = 1;
        $whereConditions['ORDER'] = [
            $this->idFieldName=>'ASC'
        ];
        $result = self::DB()->select(
            $this->tableName,
            $fields,
            $whereConditions
        );
        if(count($result)>0){
            $this->rowData = $result[0];
        }
        return count($result)>0 ? $this : null;
    }

    /**
     * find the last one
     * @param $whereConditions
     * @param string $fields
     * @return $this
     */
    public function last($whereConditions,$fields='*'){
        $whereConditions['LIMIT'] = 1;
        $whereConditions['ORDER'] = [
            $this->idFieldName=>'DESC'
        ];
        $result = self::DB()->select(
            $this->tableName,
            $fields,
            $whereConditions
        );
        if(count($result)>0){
            $this->rowData = $result[0];
        }
        return $this;
    }

    /**
     * Do update, if no params given, then update all fields with current value
     * @param array $params
     * @param boolean $isMerge  // if need to merge params with existed rowData. By default, no merge, simply replace
     * @return boolean
     */
    public function update($params=[], $whereConditions=[], $isMerge = false){
        if(empty($params)){
            $params = $this->rowData;
        }
        // merge params and rowData, so they all will be updated
        if($isMerge){
            $params = array_merge($params, $this->rowData);
        }

        return self::DB()->update(
            $this->tableName,
            $params,
            $whereConditions? $whereConditions : [$this->idFieldName=>$this->rowData[$this->idFieldName]]
        );
    }

    /**
     * Insert a new record in DB, if no params given, then persistent all field
     * @param array $params
     * @return Model
     */
    public function insert($params=[]){
        if(empty($params)){
            $params = $this->rowData;
        }
        $escaped = [];
        foreach ($params as $key=>$param) {
            if(is_object($param)){
                if (get_class($param) === Carbon::class){
                    $param = $param->toDateTimeString();
                }
            }
            $escaped[$key] = $param;
        }
        self::DB()->insert($this->tableName, $escaped);
        $this->id = self::DB()->id();
        return $this;
    }

    /**
     * Delete current record or by given where conditions.
     * Return how many rows were deleted or false for nothing removed.
     * @param null $whereConditions
     * @return bool | integer
     */
    public function delete($whereConditions=null){
        $rowCount = self::DB()->delete(
                $this->tableName,
                $whereConditions ? $whereConditions : [$this->idFieldName=>$this->rowData[$this->idFieldName]]
            )->rowCount() > 0;
        return $rowCount > 0 ? $rowCount : false;
    }

    /**
     * Do a simple database query
     * @param $whereConditions
     * @param string $fields
     * @return array|bool
     */
    public function simpleQuery($whereConditions,$fields='*'){
        return self::DB()->select(
            $this->tableName,
            $fields,
            $whereConditions
        );
    }

    /**
     * Do a simple database query for the first element only
     * @param $whereConditions
     * @param string $fields
     * @return array|bool
     */
    public function simpleQueryFirst($whereConditions,$fields='*'){
        $whereConditions['LIMIT'] = [0,1];
        $result = self::DB()->select(
            $this->tableName,
            $fields,
            $whereConditions
        );
        if($result && count($result)>0 && $result[0]){
            return $result[0];
        }
        return false;
    }

    /**
     * Set to debug mode
     * @return Model
     */
    public function setToDebugMode(){
        $this->_debug = true;
        return $this;
    }

    /**
     * For relations between tables: one-to-many
     * @param $className
     * @param $foreignKey
     * @param $options
     */
    protected function hasMany($className, $foreignKey, $options){
        // todo: impl this in future
    }

    /**
     * Magic function for setter
     * @param $name
     * @param $value
     */
    public function __set($name, $value){
        $this->rowData[$name] = $value;
    }

    /**
     * Magic function for getter
     * @param $name
     * @return mixed|null
     */
    public function __get($name){
        if(array_key_exists($name, $this->rowData))
            return $this->rowData[$name];
        return NULL;
    }

    /**
     * Magic isset
     * @param $name
     * @return bool
     */
    public function __isset($name){
        return isset($this->rowData[$name]);
    }

    /**
     * Magic unset
     * @param $name
     */
    public function __unset($name){
        unset($this->rowData[$name]);
    }

    /**
     * To String
     * @return string
     */
    public function __toString(){
        $str = '';
        if($this->rowData){
            foreach ($this->rowData as $name => $val) {
                $str .= $name.'='.$val.', ';
            }
        }
        return $str;
    }

    /**
     * Destruct the object and release database connection
     */
    public function __destruct(){
        self::$_db = null;
    }

    /**
     * Convert the object to its JSON representation.
     * 在转换成 json 之前, 将需要隐藏的字段从数据中抹去
     *
     * @param  int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        $_buffer = $this->rowData;
        foreach ($this->protectedFields as $protectedField) {
            if(isset($_buffer[$protectedField])){
                unset($_buffer[$protectedField]);
            }
        }
        return json_encode($_buffer);
    }
}