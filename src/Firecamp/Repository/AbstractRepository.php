<?php

namespace Firecamp\Repository;

use Doctrine\DBAL\Connection;

/**
 * Represents a base Repository.
 *
 * @package Firecamp\Repository
 */
abstract class AbstractRepository
{
    /**
     * @var Connection
     */
    public $db;

    /**
     * @param Connection $db
     */
    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * Inserts a table row with specified data.
     *
     * @param array $data An associative array containing column-value pairs.
     *
     * @return integer The number of affected rows.
     */
    public function insert(array $data)
    {
        return $this->db->insert($this->getTableName(), $data);
    }

    /**
     * Return the table name
     *
     * @return string
     */
    abstract public function getTableName();

    /**
     * Executes an SQL UPDATE statement on a table.
     *
     * @param array $data       An associative array containing column-value pairs.
     * @param array $identifier The update criteria
     *
     * @return integer The number of affected rows.
     */
    public function update(array $data, array $identifier)
    {
        return $this->db->update($this->getTableName(), $data, $identifier);
    }

    /**
     * Executes an SQL DELETE statement on a table.
     *
     * @param array $identifier The deletion criteria. An associateve array containing column-value pairs.
     *
     * @return integer The number of affected rows.
     */
    public function delete(array $identifier)
    {
        return $this->db->delete($this->getTableName(), $identifier);
    }

    /**
     * Returns a record by supplied id
     *
     * @param mixed $id
     *
     * @return array
     */
    public function find($id)
    {
        return $this->db->fetchAssoc(
            sprintf('SELECT * FROM %s WHERE id = ? LIMIT 1', $this->getTableName()),
            array($id)
        );
    }

    /**
     * Find a row by fields value
     *
     * @param array $fields
     * @param int   $limit  Limit results, 1 by default
     *
     * @return array
     */
    public function findByFields($fields, $limit = 1)
    {
        $where = " WHERE ";
        foreach ($fields as $field) {
            if (!empty($field['name']) && isset($field['value'])) {
                $operator = empty($field['operator']) ? '=' : $field['name'];
                $where .= $field['name'].$operator.$this->db->quote($field['value']).' AND ';
            }
        }

        if (substr($where, strlen($where) - 5, 5) == ' AND ') {
            $where = substr($where, 0, strlen($where) - 4);
        }

        if (is_int($limit) && $limit >= 1) {
            $limit = ' LIMIT '.$limit;
        } else {
            $limit = '';
        }

        return $this->db->fetchAssoc(
            sprintf('SELECT * FROM %s'.(($where !== " WHERE ") ? $where : '').$limit, $this->getTableName())
        );
    }

    /**
     * Find a row by a field value
     *
     * @param string $fieldName Name of the field to look for
     * @param mixed  $value     Value to check
     * @param int    $limit     Limit results, 1 by default
     * @param string $operator  Operator to use for comparison, "=" by default
     *
     * @return array
     */
    public function findBy($fieldName, $value, $limit = 1, $operator = "=")
    {
        if (is_int($limit) && $limit >= 1) {
            $limit = ' LIMIT '.$limit;
        } else {
            $limit = '';
        }

        return $this->db->fetchAssoc(
            sprintf('SELECT * FROM %s WHERE %s %s :value'.$limit, $this->getTableName(), $fieldName, $operator),
            array('value' => $value)
        );
    }

    /**
     * Returns all records from this repository's table
     *
     * @param integer $limit
     *
     * @return array
     */
    public function findAll($limit = null)
    {
        if (null === $limit) {
            return $this->db->fetchAll(sprintf('SELECT * FROM %s', $this->getTableName()));
        }

        return $this->db->fetchAll(sprintf('SELECT * FROM %s LIMIT %d', $this->getTableName(), $limit));
    }

    /**
     * Return all, ordered by a field
     *
     * @param string   $orderField Name of the field to order by
     * @param bool     $ASC        True for ASC (Default) False for DESC
     * @param null|int $limit      List limit, all by default
     *
     * @return array
     */
    public function findAllOrderedBy($orderField, $ASC = true, $limit = null)
    {
        $ASC ? $ascSQL = 'ASC' : $ascSQL = 'DESC';

        if (null === $limit) {
            return $this->db->fetchAll(
                sprintf('SELECT * FROM %s ORDER BY %s %s', $this->getTableName(), $orderField, $ascSQL)
            );
        }

        return $this->db->fetchAll(
            sprintf('SELECT * FROM %s LIMIT %d ORDER BY %s %s', $this->getTableName(), $limit, $orderField, $ascSQL)
        );
    }

    /**
     * Returns the last inserted id
     *
     * @return integer
     */
    public function lastInsertId()
    {
        return $this->db->lastInsertId();
    }

    /**
     * Convert the class to a string
     *
     * @return string
     */
    public function __toString()
    {
        return get_class($this);
    }
}
