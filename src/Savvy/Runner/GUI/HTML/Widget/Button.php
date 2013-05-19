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
        'text'      => array(
            'type'  => self::TYPE_VARIABLE
        ),
        'scale'     => array(
            'type'  => self::TYPE_VARIABLE,
            'value' => 'small'
        ),
        'handler'   => array(
            'type'  => self::TYPE_CODE
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
            $result .= "var f=Ext.getCmp('" . $this->currentForm() . "');";

            // show loading message
            if (isset($this->attributes['message'])) {
                $result .= "var lm=new Ext.LoadMask(f,{msg:'" . $this->attributes['message'] . "'});lm.show();";
            }

            $result .= "f.getForm().submit({";

            // presenter action
            if (isset($this->attributes['action'])) {
                $result .= "url:'/" . implode('/', $this->route) . "?action=" . $this->attributes['action'] . "',";
                $result .= "params:{applicationSessionId:'" . \Savvy\Base\Session::getApplicationSessionId() . "'},";
            }

            $result .= "success:function(o,r){";

            // hide loading message on success
            if (isset($this->attributes['message'])) {
                $result .= "lm.hide();";
            }

            // invoke RPC processor
            $result .= "if(r.result.rpc){var rpc=new Ext.util.rpc(r.result.rpc);}";

            $result .= "},failure:function(o,r){";

            // hide loading message on failure
            if (isset($this->attributes['message'])) {
                $result .= "lm.hide();";
            }

            // invoke RPC processor
            $result .= "if(r.result.rpc){var rpc=new Ext.util.rpc(r.result.rpc);}";

            $result .= '}});';
        }

        $result .= '}';

        return $result;
    }
}
