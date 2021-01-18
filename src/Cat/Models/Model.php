<?php



namespace Cat\Models;


use Cat\Database\Database;
use Cat\Database\DB;

class Model
{
    protected static string $primaryKey = 'id';

    protected static string $table;

    protected static array $fillable = [];

    public function __construct()
    {
    }

    private static function getTable()
    {
        return static::$table;
    }

    private static function getPrimaryKey()
    {
        return static::$primaryKey;
    }

    public static function find($id, $field = 'id')
    {
        $db = Database::instance();

        return $db->prepare('SELECT * FROM ' . static::$table . ' WHERE ' . $field . ' = ?', [$id], get_called_class(), $field == static::$primaryKey);
    }

    public static function all($orderBy = null, $sort = null)
    {
        $db = Database::instance();

        $orderQ = "";

        if ($orderBy && $sort) {
            $orderQ = "ORDER BY $orderBy $sort";
        }

        return $db->query('SELECT * FROM ' . static::$table . ' ' . $orderQ, get_called_class());
    }


    public static function where($clauses = [], string $implode = 'AND' ,string|null $orderBy = null, $sort = null){
        $db = Database::instance();

        $where = implode(' ' . $implode, $clauses);
        $where = trim($where, $implode);

        $orderQ = "";

        if($orderBy && $sort) {
            $orderQ = "ORDER BY $orderBy $sort";
        }


        return  $db->query('SELECT * FROM ' . static::$table . ' WHERE ' . $where . ' '. $orderQ, get_called_class());
    }

    /**
     * Creates a model
     * @param array $fields
     * @return mixed
     */
    public static function create(array $fields)
    {
        $db = Database::instance();

        $sqlParts = [];
        $attributes = [];
        $class = get_called_class();

        foreach ($fields as $key => $value) {
            if (in_array($key, static::$fillable)) {
                $sqlParts[] = "$key = ?";
                $attributes[] = $value;
            }
        }

        $sqlPart = implode(', ', $sqlParts);

        $db->prepare('INSERT INTO ' . static::$table . ' SET ' . $sqlPart, $attributes, $class, true);

        return $class::find($db->getLastInsertedId());
    }

    /**
     * Updates given model
     * @param array $fields
     * @return mixed
     */
    public function update(array $fields): bool
    {
        $db = Database::instance();

        $sqlParts = [];
        $attributes = [];
        $class = get_called_class();

        foreach ($fields as $key => $value) {
            if (in_array($key, static::$fillable)) {
                $sqlParts[] = "$key = ?";
                $attributes[] = $value;
                $this->$key = $value;
            }
        }

        $sqlPart = implode(', ', $sqlParts);
        $pk = static::$primaryKey;
        $attributes[] = $this->$pk;


        $db->prepare('UPDATE ' . static::$table . ' SET ' . $sqlPart . ' WHERE ' . $pk . '=?', $attributes, $class, true);

        return true;
    }

    public function delete(): bool
    {
        $pk = static::$primaryKey;
        return static::destroy($this->$pk);
    }

    public static function destroy($id)
    {
        $db = Database::instance();
        $pk = static::$primaryKey;

        $db->prepare("DELETE FROM " . static::$table . " WHERE " . $pk . "=?", [$id]);

        return true;
    }

    protected function belongsTo(string $className, $field)
    {
        return $className::find($this->$field);
    }

    protected function hasMany(string $className, string $field) {
        $db = Database::instance();
        $pk = static::$primaryKey;
        $class = new $className();

        return  $db->prepare(
            'SELECT * FROM ' . $class->getTable()
            . ' WHERE ' . $field . ' = ?',[$this->$pk], get_called_class());
    }

}