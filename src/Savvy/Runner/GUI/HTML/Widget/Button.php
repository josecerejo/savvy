<?php

namespace Savvy\Runner\GUI\HTML\Widget;

/**
 * Button Ext JS widget
 *
 * @package Savvy
 * @subpackage Runner\GUI\HTML\Widget
 */
class Button extends AbstractWidget
{
    /**
     * Widget configuration
     *
     * @var array
     */
    protected $configuration = array(
        'text'         => array(
            'type'         => self::TYPE_VARIABLE,
            'localize'     => true
        ),
        'message'      => array(
            'type'         => self::TYPE_INTERNAL,
            'localize'     => true
        ),
        'scale'        => array(
            'type'         => self::TYPE_VARIABLE,
            'value'        => 'small'
        ),
        'handler'      => array(
            'type'         => self::TYPE_CODE
        )
    );

    /**
     * Map text configuration
     *
     * @return string
     */
    protected function getText()
    {
        $result = null;

        if (isset($this->attributes['caption'])) {
            $result = $this->attributes['caption'];
        }

        return $result;
    }

    /**
     * Get handler for button action
     *
     * @return string
     */
    protected function getHandler()
    {
        $result = "function(){";

        if ($this->currentForm() === false) {
            $this->configuration['scope'] = array(
                'type'  => self::TYPE_CODE,
                'value' => 'this'
            );

            switch ($this->attributes['action']) {
                case 'close':
                    $result .= 'this.close();';
            }
        } else {
            $currentForm = $this->currentForm(true);

            $result .= "var f=Ext.getCmp('" . $this->currentForm() . "');";

            // show loading message
            if ($message = $this->getConfiguration('message')) {
                $result .= "var lm=new Ext.LoadMask(f,{msg:'" . $message . "'});lm.show();";
            }

            $result .= "f.getForm().submit({";

            if (isset($this->attributes['action'])) {
                // presenter action
                $result .= "url:'/" . implode('/', $this->route) . "?action=" . $this->attributes['action'] . "',";
            }

            $fields = array(0 => 'applicationSessionId:Ext.util.session()');

            // submit encrypted values
            if ($encryptedFields = $currentForm->getConfiguration('encryptedFields')) {
                foreach ($encryptedFields as $i => $field) {
                    $fields[] = $field . ":Ext.util.md5(" .
                        "Ext.util.md5(f.getForm().findField('" . $field . "').getValue())+Ext.util.session())";
                }

            }

            $result .= "params:{" . implode(",", $fields) . "},";
            $result .= "success:function(o,r){";

            // hide loading message on success
            if ((bool)$message) {
                $result .= "lm.hide();";
            }

            // invoke RPC processor for "success" responses
            $result .= "if(r.result.rpc){var rpc=new Ext.util.rpc(r.result.rpc);}";

            $result .= "},failure:function(o,r){";

            // hide loading message on failure
            if ((bool)$message) {
                $result .= "lm.hide();";
            }

            // invoke RPC processor for "failure" responses
            $result .= "if(r.result.rpc){var rpc=new Ext.util.rpc(r.result.rpc);}";

            $result .= '}});';
        }

        $result .= '}';

        return $result;
    }
}
