<?php

namespace Application\Service;

class FilterService
{
    const TYPE_TEXT = 'text';
    const TYPE_SELECT = 'select';

    const PREFIX = 'f_';
    const PLACEHOLDER_VAL = ':value:';

    protected $data = array();
    protected $rawData = array();
    protected $filterParams = array();
    protected $url;

    private $row;
    private $cell;

    public function getData()
    {
        return $this->data;
    }

    public function getRawData()
    {
        return $this->rawData;
    }

    public function getValue($key)
    {
        $value = null;
        if (isset($this->filterParams[$key])) {
            $value = $this->filterParams[$key];
        } else {
            foreach ($this->rawData as $filterData) {
                if ($filterData['name'] == $key) {
                    $value = $filterData['defaultValue'];
                    break;
                }
            }
        }
        return $value;
    }

    public function setFilterParam($name, $value)
    {
        if (isset($this->rawData[$name])) {
            $filterData = $this->rawData[$name];
            if ($filterData['type'] === self::TYPE_SELECT) {
                $options = new \stdClass();
                $options->options = array();
                array_walk_recursive($filterData['options'], function($v, $k, $stdClass) {
                    $stdClass->options[] = (string)$k;
                }, $options);
                if (!in_array($value, $options->options, true)) {
                    return false;
                }
            }
        }
        $this->filterParams[$name] = $value;
        return true;
    }

    public function addFilterRow(array $data, $colspan = null)
    {
        if (is_null($this->row)) {
            $this->row = 0;
        } else {
            $this->row++;
        }
        return $this->addFilter($data, $this->row, $this->getNextCell(), $colspan);
    }

    public function addFilterCell(array $data, $colspan = null)
    {
        return $this->addFilter($data, $this->row, $this->getNextCell(), $colspan);
    }

    /**
     * @param string $label
     * @param string $name
     * @param string $defaultValue
     * @return array
     */
    public function createTextItem($label = '', $name = '', $defaultValue = '', $dbCriteria = null, $attributes = array())
    {
        $dbParam = ':param_e_' . $name;
        if (!$dbCriteria) {
            $dbCriteria = "LOWER(e." . $name . ") LIKE LOWER(CONCAT(" . $dbParam . ", '%'))";
        }
        return array(
            'type' => self::TYPE_TEXT,
            'label' => $label,
            'name' => $this->getName($name),
            'defaultValue' => $defaultValue,
            'db_criteria' => array(
                'param' => $dbParam,
                'value' => '',
                'criteria' => $dbCriteria
            ),
            'attributes' => $attributes,
        );
    }

    /**
     * @param string $label
     * @param string $name
     * @param array $options
     * @param string $defaultValue
     * @return array
     */
    public function createSelectItem($label = '', $name = '', array $options = array(), $defaultValue = '', $dbCriteria = null, $attributes = array())
    {
        $dbParam = ':param_e_' . $name;
        if (!$dbCriteria) {
            $dbCriteria = "e." . $name . " = " . $dbParam . "";
        }
        return array(
            'type' => self::TYPE_SELECT,
            'label' => $label,
            'name' => $this->getName($name),
            'options' => $options,
            'defaultValue' => $defaultValue,
            'db_criteria' => array(
                'param' => $dbParam,
                'value' => '',
                'criteria' => $dbCriteria
            ),
            'attributes' => $attributes,
        );
    }

    /**
     * Add filter data
     *
     * @param array $data
     * @param int $row
     * @param int $cell
     *
     * @return FilterService
     */
    public function addFilter(array $data, $row = 0, $cell = 0, $colspan = null)
    {
        if (!is_null($colspan)) {
            $data['colspan'] = (int) $colspan;
        }
        $this->data[$row][$cell] = $data;
        $this->rawData[$data['name']] = $data;
        return $this;
    }

    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    public function  pageUrl()
    {
        return $this->url;
    }

    public function getYesNo()
    {
        return array(
            0 => 'Нет',
            1 => 'Да'
        );
    }

    protected function getNextCell()
    {
        $this->cell = 0;
        if (isset($this->data[$this->row])) {
            $this->cell = count($this->data[$this->row]);
        }
        return $this->cell;
    }

    protected function getName($name = '')
    {
        return self::PREFIX . $name;
    }
}