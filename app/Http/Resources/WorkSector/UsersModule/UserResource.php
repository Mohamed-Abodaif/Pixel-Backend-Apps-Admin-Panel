<?php

namespace App\Http\Resources\WorkSector\UsersModule;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\WorkSector\UsersModule\UserProfileResource;
use App\Http\Resources\WorkSector\SystemConfigurationResources\RolesAndPermissions\RoleResource;
use App\Http\Resources\WorkSector\SystemConfigurationResources\DropdownLists\DepartmentResource;
use App\Models\WorkSector\UsersModule\User;

class UserResource extends JsonResource
{

    private function handleRolePermissions(array $dataArrayToChange = []): array
    {
        if ($this->role != null) {
            $dataArrayToChange["role"] = new RoleResource($this->role);
            //Adding permissions to data array
            $dataArrayToChange["permissions"] = $this->role->permissions()->pluck("name")->toArray();
            //    $dataArrayToChange["permissions"] = $this->getAllPermissions();


            //unsetting permissions from user role's array (there is no need to serialize it with user data)
            // unset($this->role);
            //  return $dataArrayToChange;
        }


        return $dataArrayToChange;
    }

    private function handleDepartmentInfo(array $dataArrayToChange = []): array
    {
        if ($this->department != null) {
            $dataArrayToChange["department"] = new DepartmentResource($this->department);

            //unsetting department from user's array (there is no need to serialize it with user data)
            unset($this->department);
            // return $dataArrayToChange;
        }
        return $dataArrayToChange;
    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = [];
        //Getting permissions and unsetting from user object before serializing it
        $data = $this->handleRolePermissions($data);
        $data = $this->handleDepartmentInfo($data);
        //Serializing User Object
        $data = array_merge(
            parent::toArray($request),
            $data,
            (new UserProfileResource($this->profile))->toArray($request)
        );


        //Adding User Access Token
        $data['token'] = auth()->login(User::find($this->id));

        return $data;
    }

    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }
}
