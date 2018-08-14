<?php
declare(strict_types=1);
namespace CROFin\Repository;

interface RepositoryInterface
{
    /**
     * @return mixed
     */
    public function all(): array;

    /**
     * @param int $id
     * @param bool $failIfNotExists
     * @return mixed
     */
    public function find(int $id, bool $failIfNotExists = true);

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update($id, array $data);

    /**
     * @param int $id
     * @return mixed
     */
    public function delete($id);

    /**
     * @param string $field
     * @param $value
     * @return array
     */
    public function findByField(string $field, $value);

    /**
     * @param array $search
     * @return mixed
     */
    public function findOneBy(array $search);
}