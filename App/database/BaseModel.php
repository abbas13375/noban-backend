<?php
namespace App\database;

use App\database\drivers\DriverContract;
use App\database\drivers\DriverInstance;

abstract class BaseModel{

    /**
     * access to database driver.
     * @var DriverContract
     */
    protected $driver;

    /**
     * save key value peroperties of model
     * @var array
     */
    protected $attributes;

    /**
     * added automaticly value for fields created_at, updated_at
     * @var bool
     */
    protected $timestamps = true;

    protected $createdAtFieldName = 'created_at';
    protected $updatedAtFieldName = 'updated_at';


    abstract protected function tableName(): string;

    abstract protected function attributes(): array;

    public function __construct()
    {
        $this->driver = DriverInstance::get();
    }

    /**
     * @return DriverContract
     */
    public function getConnection(){
        return $this->driver;
    }

    public function save(): bool{

        $this->initCreatedAt();
        $this->initUpdatedAt();

        return $this->driver->insert(static::tableName(), $this->attributes);
    }


    public static function find(? string $conditions = null, ?bool $asArray = false): array{
        /** @var BaseModel $model */
        $modelName = get_called_class();
        $model = new $modelName();

        $sql = 'SELECT * FROM '. $model->tableName();
        if($conditions){
            $sql .= ' WHERE ' . $conditions;
        }
        $result =  $model->getConnection()->query($sql);
        if($asArray){
            return $result;
        }

        $models = [];
        foreach($result as $arrayModel){
            $objectModel = new $modelName();
            foreach($arrayModel as $key => $value){
                $objectModel->{$key} = $value;
            }
            $models[] = $objectModel;
        }

        return $models;
    }


    protected function initCreatedAt(){
        if(! $this->timestamps) return;
        $this->{$this->createdAtFieldName} = time();
    }

    protected function initUpdatedAt(){
        if(! $this->timestamps) return;
        $this->{$this->updatedAtFieldName} = time();
    }

    public function __set($name, $value){
        if(! in_array($name, static::attributes())){
            throw new \Exception('Field Database not Exists.');
        }

        $this->attributes[$name] = $value;
    }

    public function __get($name){
        return $this->attributes[$name] ?? null;
    }
}
