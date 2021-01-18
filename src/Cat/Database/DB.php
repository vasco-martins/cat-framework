<?php


namespace Cat\Database;


class DB
{

    private array $fields = [];
    private array $where = [];
    private array $from = [];

    public function select() {
        $this->fields = func_get_args();

        return $this;
    }

    public function where() {
        foreach(func_get_args() as $arg) {
            $this->where[] = $arg;
        }
        return $this;
    }

    public function from(string $table) {
        $this->from[] = "$table";
        return $this;
    }

    public function get() {
        return 'SELECT ' . implode(', ', $this->fields)
            . ' FROM ' . implode(', ', $this->from)
            . ' WHERE ' . implode(' AND ', $this->where);
    }

}