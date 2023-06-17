<?php

namespace App\Services\GeneralServices;

use App\CustomLibs\ValidatorLib\ArrayValidator;
use App\CustomLibs\ValidatorLib\Validator;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

//Need To Review And Test
class ModelDuplicatingService
{

    //Model & Relationships Properties
    private Model $model;
    private array $RelationshipsToFill = [];

    //Validation Properties
    private Validator $validator;
    private string $RequestFormClass;
    private array $data = [];

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param string $RequestFormClass
     * @return $this
     * @throws Exception
     */
    public function setRequestFormClass(string $RequestFormClass): self
    {
        $this->validator->changeRequestClass($RequestFormClass);
        return $this;
    }

    /**
     * @param array $relationships
     * @return $this
     */
    public function setRelationshipsToFill(array $relationships): self
    {
        $this->RelationshipsToFill = $relationships;
        return $this;
    }

    /**
     * @return $this
     */
    private function setRequestData(): self
    {
        $this->data =  $this->validator->getRequestData();
        return $this;
    }

    /**
     * @param Request|array $request
     * @return $this
     * @throws Exception
     */
    private function initValidator(Request | array $request): self
    {
        $this->validator = new ArrayValidator($this->RequestFormClass, $request);
        return $this;
    }

    /**
     * @return $this
     * @throws Exception
     */
    private function validateData(Request | array $request): self
    {
        $this->initValidator($request)->setRequestData();
        $validationResult = $this->validator->validate();
        if (is_array($validationResult)) {
            throw new Exception(join(" , ", $validationResult));
        }
        return $this;
    }

    /**
     * @param Model $model
     * @return array
     */
    private function setModelFillableData(Model $model): array
    {
        return  array_map(function ($column) {
            if (isset($this->data[$column])) {
                return $this->data[$column];
            }
        }, $model->getFillable());
    }

    /**
     * @return $this
     * @throws Exception
     */
    private function replicateModel(): self
    {
        $this->model->replicate()->fill(
            $this->setModelFillableData($this->model)
        );
        if ($this->model->save()) {
            return $this;
        }
        throw new Exception("Failed To Replicate The Model !");
    }


    /**
     * @return $this
     * @throws Exception
     */
    private function replicateRelationships(): self
    {
        foreach ($this->RelationshipsToFill as $relationship) {
            $relationshipOb = $this->model->{$relationship};
            if ($relationshipOb == null) {
                throw new Exception("The Given Model Relationship ' " . $relationship . " ' Is Null !");
            }

            $relationshipOb->replicate()->fill(
                $this->setModelFillableData($relationshipOb)
            );
            if (!$relationshipOb->save()) {
                throw new Exception("Failed To Replicate The Model Relationship ' " . $relationship . " ' !");
            }
        }
        return $this;
    }

    public function duplicate(Request | array $request): JsonResponse
    {
        try {
            $this->validateData($request);
            //If No Exception Is Thrown .... Data are valid
            DB::beginTransaction();
            $this->replicateModel()->replicateRelationships();
            DB::commit();
            return Response::success([], ["Duplicated Successfully !"]);
        } catch (Exception $e) {
            DB::rollBack();
            return Response::error([$e->getMessage()]);
        }
    }
}
