<?php

namespace Application\Controller\Api;

use Zend\View\Model\JsonModel;
use Application\ApplicationTraits\DoctrineEntityManagerAwareTrait;

class AbstractRestfulController extends \Zend\Mvc\Controller\AbstractRestfulController
{
    use DoctrineEntityManagerAwareTrait;
    const ERROR_PARAM_REQUIRED = 'Param is required';

    protected $jsonDecodeType = \Zend\Json\Json::TYPE_ARRAY;

    public function onDispatch(\Zend\Mvc\MvcEvent $e)
    {
        return parent::onDispatch($e);
    }

    public function create($data)
    {
        return new JsonModel(parent::create($data));
    }

    public function delete($id)
    {
        return new JsonModel(parent::delete($id));
    }

    public function get($id)
    {
        return new JsonModel(parent::get($id));
    }

    public function getList()
    {
        return new JsonModel(parent::getList());
    }

    public function head($id = null)
    {
        return new JsonModel(parent::head($id));
    }

    public function options()
    {
        return new JsonModel(parent::options());
    }

    public function patch($id, $data)
    {
        return new JsonModel(parent::patch($id, $data));
    }

    public function replaceList($data)
    {
        return new JsonModel(parent::replaceList($data));
    }

    public function patchList($data)
    {
        return new JsonModel(parent::patchList($data));
    }

    public function update($id, $data)
    {
        return new JsonModel(parent::update($id, $data));
    }

    public function notFoundAction()
    {
        return new JsonModel(parent::notFoundAction());
    }

    public function getPayload($key = null, $default = null)
    {
        $contentType = $this->getRequest()->getHeaders()->get('content-type');
        $isJsonPayload = (is_object($contentType) && $contentType->getFieldValue() == 'application/json');
        if (!$isJsonPayload) {
            /** @see \Zend\Mvc\Controller\Plugin\Params */
            return $this->params()->fromPost($key, $default);
        } else {
            static $jsonArray;
            if (!is_array($jsonArray)) {
                try {
                    $jsonArray = \Zend\Json\Json::decode($this->getRequest()->getContent(), \Zend\Json\Json::TYPE_ARRAY);
                    if (!is_array($jsonArray)) {
                        throw new \Zend\Json\Exception\RuntimeException('Decoding failed');
                    }
                } catch (\Exception $e) {
                    http_response_code(400);
                    exit('{"success":false,"errors":{"json":"JSON decoding error"}}');
                }
            }
            if ($key) {
                return isset($jsonArray[$key]) ? $jsonArray[$key] : $default;
            } else {
                return $jsonArray;
            }
        }
    }

    protected function sendErrorResponse(array $errors = array(), $statusCode = 200)
    {
        $this->response->setStatusCode($statusCode);

        return new JsonModel(
            array(
                'success' => false,
                'errors' => $errors
            )
        );
    }

    protected function sendSuccessResponse(array $data = array(), $statusCode = 200)
    {
        $this->response->setStatusCode($statusCode);

        return new JsonModel(
            array(
                'success' => true,
                'data' => $data
            )
        );
    }

    protected function getFormErrors(\Application\Form\ApplicationFormAbstract $form)
    {
        $result = array();
        $data = $form->getMessages();
        foreach ($data as $item => $errors) {
            $result[$item] = current($errors);
        }
        return $this->sendErrorResponse($result);
    }
}
