<?php
/**
 * Created by PhpStorm.
 * User: zhu
 * Email: ylsc633@gmail.com
 * Date: 2017/6/5
 * Time: 下午5:25
 */

namespace App\Repositories\Eloquent;


use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function model()
    {
        return User::class;
    }

    /**
     * 通过email 获取user
     * @param $email
     * @return mixed
     */
    public function findUserByEmail($email)
    {
        return $this->model->whereEmail($email)->first();
    }

    /**
     * 通过githubId 获取 user
     * @param $githubId
     * @return mixed
     */
    public function findUserByGithubId($githubId)
    {
        return $this->model->whereGithubId($githubId)->first();
    }

    /**
     *
     * 获取已分配的角色
     *
     * @param $userId
     * @return mixed
     */
    public function getAssignRole($userId)
    {
        return $this->model->find($userId)->role()->get();
    }
    /**
     *
     * 获取已分配的角色ID集合
     *
     * @param $userId
     * @return array
     */
    public function getAssignRoleIds($userId)
    {
        $result = $this->getAssignRole($userId);
        $ids = [];
        if (!empty($result->toArray())) {
            foreach ($result as $value) {
                array_push($ids,$value->id);
            }
            array_unique($ids);
        }
        return $ids;
    }
    /**
     *
     * 分配角色
     *
     * @param $role
     * @param $id
     * @return bool
     */
    public function syncRelationship($role,$id)
    {
        try{
            \DB::beginTransaction();
            $this->model->find($id)->role()->sync([]);
            $this->model->find($id)->role()->sync($role);
            \DB::commit();
            return true;
        } catch (\Exception $e) {
            \DB::rollBack();
            $code = $e->getCode();
            \Log::info('"controller.error" to listener "' . __METHOD__ . '".', ['message' => $e->getMessage(), 'code' => $code]);
            return false;
        }
    }

    /**
     * 根据userId 获取user
     * @param $userId
     * @return mixed
     */
    public function first($userId)
    {
        return $this->model->whereId($userId)->first();
    }

    public function getRoleByUserId($authId)
    {
        return $this->model->find($authId)->role()->first();
    }

}