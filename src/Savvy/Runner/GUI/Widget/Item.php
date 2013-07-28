<?php

namespace Savvy\Runner\GUI\Widget;

/**
 * Item Ext JS widget
 *
 * @package Savvy
 * @subpackage Runner\GUI\Widget
 */
class Item extends AbstractWidget
{
    /**
     * Widget configuration
     *
     * @var array
     */
    protected $configuration = array(
        'xtype'           => array(
            'type'            => self::TYPE_VARIABLE,
            'value'           => null
        ),
        'inputType'       => array(
            'type'            => self::TYPE_VARIABLE
        ),
        'name'            => array(
            'type'            => self::TYPE_VARIABLE
        ),
        'fieldLabel'      => array(
            'type'            => self::TYPE_VARIABLE,
            'localize'        => true
        ),
        'labelAlign'      => array(
            'type'            => self::TYPE_VARIABLE
        ),
        'padding'         => array(
            'type'            => self::TYPE_VARIABLE
        ),
        'margin'          => array(
            'type'            => self::TYPE_VARIABLE
        ),
        'submitValue'     => array(
            'type'            => self::TYPE_VARIABLE
        ),
        'id'              => array(
            'type'            => self::TYPE_VARIABLE
        ),
        'height'          => array(
            'type'            => self::TYPE_VARIABLE
        ),
        'border'          => array(
            'type'            => self::TYPE_VARIABLE
        ),
        'allowBlank'      => array(
            'type'            => self::TYPE_VARIABLE
        ),
        'items'           => array(
            'type'            => self::TYPE_CHILDS,
            'name'            => 'item'
        ),
        'buttons'         => array(
            'type'            => self::TYPE_CHILDS,
            'name'            => 'button'
        ),
        'layout'          => array(
            'type'            => self::TYPE_CHILD,
            'name'            => 'layout',
            'value'           => null
        ),
        'autoEl'          => array(
            'type'            => self::TYPE_CHILD,
            'name'            => 'element'
        ),
        'columnWidth'         => array(
            'type'            => self::TYPE_VARIABLE
        ),
        'title'           => array(
            'type'            => self::TYPE_VARIABLE,
            'localize'        => true
        ),
        'text'            => array(
            'type'            => self::TYPE_VARIABLE,
            'localize'        => true
        ),
        'html'            => array(
            'type'            => self::TYPE_VARIABLE,
            'localize'        => true
        ),
        'focus'           => array(
            'type'            => self::TYPE_INTERNAL
        ),
        'encrypt'         => array(
            'type'            => self::TYPE_INTERNAL
        ),
        'encryptedFields' => array(
            'type'            => self::TYPE_INTERNAL,
            'value'           => array()
        )
    );

    /**
     * Set focus for current input field
     *
     * @param string $enabled
     * @return void
     */
    protected function setFocus($enabled)
    {
        if ((bool)$enabled && (strtolower((string)$enabled) !== 'false')) {
            $rootWidget = $this;

            while ($rootWidget->parent !== null) {
                $rootWidget = $rootWidget->parent;
            }

            $rootWidget->setConfiguration('focus', $this->getId());
        }
    }

    /**
     * Set MD5 encryption for current input field
     *
     * @param string $enabled
     * @return void
     */
    protected function setEncrypt($enabled)
    {
        if ((bool)$enabled && (strtolower((string)$enabled) !== 'false')) {
            $this->setConfiguration('submitValue', 'false');

            if ($currentForm = $this->currentForm(true)) {
                $currentForm->setConfiguration('encryptedFields', $this->getId());
            }
        }
    }

    /**
     * Set type property
     *
     * @param string $type
     * @return void
     */
    protected function setType($type)
    {
        if ($type === 'button') {
            $this->configuration['handler'] = array(
                'type' => self::TYPE_CODE
            );
        }
    }

    /**
     * Get Xtype property
     *
     * @return string|null
     */
    protected function getXtype()
    {
        $result = null;

        if (isset($this->attributes['type'])) {
            switch ($this->attributes['type']) {
                case 'text':
                case 'password':
                    $result = 'textfield';
                    break;
                default:
                    $result = $this->attributes['type'];
            }
        }

        return $result;
    }

    /**
     * Get inputType property
     *
     * @return string
     */
    protected function getInputType()
    {
        $result = null;

        if (isset($this->attributes['type'])) {
            if ($this->attributes['type'] === 'password') {
                $result = 'password';
            }
        }

        return $result;
    }

    /**
     * Get fieldLabel property
     *
     * @return string
     */
    protected function getFieldLabel()
    {
        $result = null;

        if (isset($this->attributes['label'])) {
            $result = $this->attributes['label'];
        }

        return $result;
    }

    /**
     * Get layout property
     *
     * @return string|null
     */
    protected function getLayout()
    {
        $result = null;

        if (isset($this->attributes['layout'])) {
            if ($this->attributes['layout'] === 'inherit') {
                $result = $this->parent->configuration['layout']['value'];
            }
        }

        return $result;
    }

    /**
     * Get columnWidth property from width attribute
     *
     * @return string|null
     */
    protected function getColumnWidth()
    {
        $result = null;

        if (isset($this->attributes['width'])) {
            $result = $this->attributes['width'];

            if (strpos($result, '%') !== false) {
                $result = substr($result, 0, strpos($result, '%')) / 100;
            }
        }

        return $result;
    }

    /**
     * Get border property default
     *
     * @return string|null
     */
    protected function getBorder()
    {
        $result = null;

        if (isset($this->attributes['border'])) {
            $result = $this->attributes['border'];
        } elseif (isset($this->attributes['type'])) {
            switch ($this->attributes['type']) {
                case 'form':
                    $result = 0;
                    break;
                case 'fieldset':
                    $result = 1;
                    break;
            }
        }

        return $result;
    }

    /**
     * Get widget id
     *
     * @return string|null
     */
    protected function getId()
    {
        $result = null;

        if (isset($this->attributes['name'])) {
            if (isset($this->attributes['id']) === false) {
                $result = $this->attributes['name'];
            }
        }

        return $result;
    }
}
