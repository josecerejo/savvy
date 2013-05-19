<?php

namespace Savvy\Module\Test\Presenter;

use Savvy\Runner\GUI as GUI;

/**
 * Test module presenter for modal dialog
 *
 * @package Savvy
 * @subpackage Module\Test
 */
class ModalPresenter extends GUI\Presenter
{
    /**
     * Test for PHP-generated views (opens modal dialog)
     *
     * @return string
     */
    protected function modalView()
    {
        return
            '<window width="320" height="200">' .
            '    <button action="close" caption="Schließen" />' .
            '</window>';
    }
}
