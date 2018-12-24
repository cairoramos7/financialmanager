<?php
declare(strict_types=1);
namespace CROFin\Repository;

class DefaultRepository implements RepositoryInterface
{
    /**
     * @var string
     */
    private $modelClass;

    /**
     * @var
     */
    private $model;

    /**
     * DefaultRepository constructor.
     *
     * @param string $modelClass
     */
    public function __construct(string $modelClass)
    {
        $this->modelClass = $modelClass;
        $this->model = new $modelClass;
    }

    /**
     * @return mixed
     */
    public function all(): array
    {
        return $this->model->all()->toArray();
    }

    /**
     * @param  int  $id
     * @param  bool $failIfNotExists
     * @return mixed
     */
    public function find(int $id, bool $failIfNotExists = true)
    {
        return $failIfNotExists ? $this->model->findOrFail($id) : $this->model->find($id);
    }

    /**
     * @param  string $field
     * @param  $value
     * @return array
     */
    public function findByField(string $field, $value)
    {
        return $this->model->where($field, '=', $value)->get();
    }

    /**
     * @param  array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $this->model->fill($data);
        $this->model->save();
        return $this->model;
    }

    /**
     * @param  int   $id
     * @param  array $data
     * @return mixed
     */
    public function update($id, array $data)
    {
        $model = $this->findInternal($id);

        $model->fill($data);
        $model->save();
        return $model;
    }

    /**
     * @param  int $id
     * @return mixed
     */
    public function delete($id)
    {
        $model = $this->findInternal($id);
        $model->delete();
        return $model;
    }

    protected function findInternal($id)
    {
        return is_array($id) ? $this->findOneBy($id) : $this->findOrFail($id);
    }

    /**
     * @param  array $search
     * @return mixed
     */
    public function findOneBy(array $search)
    {
        $queryBuilder = $this->model;
        foreach ($search as $field => $value) {
            $queryBuilder = $queryBuilder->where($field, '=', $value);
        }

        return $queryBuilder->firstOrFail();
    }
}