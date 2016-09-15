<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 20014 osCommerce

  Released under the GNU General Public License

  Example usage:

  $messageStack = new messageStack();
  $messageStack->add('Error: Error 1', 'error');
  $messageStack->add('Error: Error 2', 'warning');
  if ($messageStack->size > 0) echo $messageStack->output();
*/

  class messageStack extends alertBlock {
    var $size = 0;

    function messageStack() {
      global $messageToStack;

      $this->errors = array();

      if (tep_session_is_registered('messageToStack')) {
        for ($i = 0, $n = sizeof($messageToStack); $i < $n; $i++) {
          $this->add($messageToStack[$i]['text'], $messageToStack[$i]['type']);
        }
        tep_session_unregister('messageToStack');
      }
    }

    function add($message, $type = 'error') {
      if ($type == 'error') {
        $this->errors[] = array('params' => 'class="alert-message alert-message-danger"', 'text' => tep_glyphicon('flash glyphicon-lg', 'danger') . $message);
      } elseif ($type == 'warning') {
        $this->errors[] = array('params' => 'class="alert-message alert-message-warning"', 'text' => tep_glyphicon('warning-sign glyphicon-lg', 'warning') . $message);
      } elseif ($type == 'success') {
        $this->errors[] = array('params' => 'class="alert-message alert-message-success"', 'text' => tep_glyphicon('ok glyphicon-lg', 'success') . $message);
      } else {
        $this->errors[] = array('params' => 'class="alert-message alert-message-danger"', 'text' => $message);
      }

      $this->size++;
    }

    function add_session($message, $type = 'error') {
      global $messageToStack;

      if (!tep_session_is_registered('messageToStack')) {
        tep_session_register('messageToStack');
        $messageToStack = array();
      }

      $messageToStack[] = array('text' => $message, 'type' => $type);
    }

    function reset() {
      $this->errors = array();
      $this->size = 0;
    }

    function output() {
      return $this->alertBlock($this->errors);
    }
  }
?>
