<?php

namespace App\Services\WorkSector\UsersModule\EmailVerificationService;

use Exception;
use Illuminate\Http\Request;
use App\CustomLibs\ValidatorLib\Validator;
use App\Models\WorkSector\UsersModule\User;
use App\CustomLibs\ValidatorLib\ArrayValidator;
use App\Http\Requests\WorkSector\UsersModule\VerificationTokenRequest;

abstract class ServiceBaseFunctionality
{

    protected Validator $validator;
    protected ?User $user = null;
    protected array $data = [];


    /**
     * @param User|null $user
     * @return $this
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    abstract protected function setRequestKeysToGeneralValidation(): array;
    abstract protected function setRequestKeysToValidationWhenUserSet(): array;

    /**
     * @param array|Request $request
     * @return $this
     * @throws Exception
     */
    protected function initValidator(array | Request $request): self
    {
        $this->validator = new ArrayValidator(VerificationTokenRequest::class, $request);
        $this->setRequestKeysToGeneralValidation();
        $this->data = $this->validator->getRequestData();
        return $this;
    }

    /**
     * @return bool|array
     */
    protected function validateRequest(): bool | array
    {
        return $this->validator->validate();
    }

    /**
     * @param array|Request $request
     * @return bool
     * @throws Exception
     */
    protected function setProcessableUser(array | Request $request): bool
    {
        $this->initValidator($request);
        //I User is set there is no need to validate all data (like email)
        if ($this->user) {
            if (empty($this->setRequestKeysToValidationWhenUserSet())) {
                return true;
            }
        }

        //validation on desired columns only
        $validationResult = $this->validateRequest();
        if (is_array($validationResult)) {
            throw new Exception(join(" , ", $validationResult));
        }

        //I User is set there is no need to retrieve a new instance
        if ($this->user == null) {
            $this->user = User::where("email", $this->data["email"])->first();
        }

        //user maybe not set
        // and also after retrieve a new instance maybe it is not found in DB
        return $this->user != null;
    }
}
