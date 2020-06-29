<?php
/**
 * Pimcore
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPLv3)
 * - Pimcore Enterprise License (PEL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 * @category   Pimcore
 * @package    Property
 *
 * @copyright  Copyright (c) Pimcore GmbH (http://www.pimcore.org)
 * @license    http://www.pimcore.org/license     GPLv3 and PEL
 */

namespace Pimcore\Model\Asset\MetaData\ClassDefinition\Data;

use Pimcore\Model\Element\AbstractElement;
use Pimcore\Model\Element\ElementInterface;
use Pimcore\Model\Element\Service;

class Asset extends Data
{
    /**
     * @param mixed $value
     * @param array $params
     *
     * @return null|int
     */
    public function marshal($value, $params = [])
    {
        $element = Service::getElementByPath('asset', $value);
        if ($element) {
            return $element->getId();
        } else {
            return null;
        }
    }

    /**
     * @param mixed $value
     * @param array $params
     *
     * @return string
     */
    public function unmarshal($value, $params = [])
    {
        $element = null;
        if (is_numeric($value)) {
            $element = Service::getElementById('asset', $value);
        }
        if ($element) {
            $value = $element->getRealFullPath();
        } else {
            $value = '';
        }

        return $value;
    }

    /**
     * @param mixed $data
     * @param array $params
     * @return mixed
     */
    public function transformGetterData($data, $params = [])
    {
        if (is_numeric($data)) {
            return \Pimcore\Model\Asset\Service::getElementById("asset", $data);
        }
        return $data;
    }

    /**
     * @param mixed $data
     * @param array $params
     * @return mixed
     */
    public function transformSetterData($data, $params = [])
    {
        if ($data instanceof \Pimcore\Model\Asset) {
            return $data->getId();
        }
        return $data;
    }


    /**
     * @param mixed $data
     * @param array $params
     * @return mixed
     */
    public function getDataFromEditMode($data, $params = [])
    {
        $element = Service::getElementByPath("asset", $data);
        if ($element) {
            return $element->getId();
        }
        return "";
    }

    /**
     * @param mixed $data
     * @param array $params
     * @return mixed
     */
    public function getDataForResource($data, $params = [])
    {
        if ($data instanceof ElementInterface) {
            return $data->getId();
        }

        return $data;
    }

    /** @inheritDoc */
    public function getDataForEditMode($data, $params = [])
    {
        if (is_numeric($data)) {
            $data = Service::getElementById("asset", $data);
        }
        if ($data instanceof ElementInterface) {
            return $data->getRealFullPath();
        } else {
            return "";
        }
    }

    /**
     * @param mixed $data
     * @param array $params
     * @return mixed
     */
    public function getDataForListfolderGrid($data, $params = [])
    {
        if ($data instanceof AbstractElement) {
            return $data->getFullPath();
        }
        return $data;
    }

    /**
     * @param $data
     * @param array $params
     * @return array
     */
    public function resolveDependencies($data, $params = [])
    {
        if (isset($params['data']) && $params['data'] instanceof ElementInterface) {
            /** @var ElementInterface $elementData */
            $elementData = $params['data'];
            $elementType = $params['type'];

            $key = $elementType . '_' . $elementData->getId();
            return [
                $key => [
                    'id' => $elementData->getId(),
                    'type' => $elementType
                ]];
        }
        return [];
    }
}
