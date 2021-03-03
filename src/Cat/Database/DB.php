<?php


namespace Cat\Database;


class DB
{

    private array $fields = [];
    private array $where = [];
    private array $from = [];
    private int $perPage;
    private int $page;

    public function select()
    {
        $this->fields = func_get_args();

        return $this;
    }

    public function where()
    {
        foreach (func_get_args() as $arg) {
            $this->where[] = $arg;
        }
        return $this;
    }

    public function from(string $table)
    {
        $this->from[] = "$table";
        return $this;
    }

    public function paginate(int $perPage = 8)
    {
        $this->page = request()->get('page', 1);
        $this->perPage = $perPage;
        return $this;
    }

    public function get()
    {
        $query = 'SELECT ' . implode(', ', $this->fields)
            . ' FROM ' . implode(', ', $this->from)
            . ' WHERE ' . implode(' AND ', $this->where);

        if ($this->perPage) {
            $offset = ($this->page - 1) + $this->perPage;
            $query .= 'LIMIT ' . $this->perPage . ' OFFSET ' . $offset;
        }

        return $query;
    }

}