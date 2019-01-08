<?php
/**
 * @author Jaap Jansma <jaap.jansma@civicoop.org>
 * @license AGPL-3.0
 */

abstract class CRM_Generic_Tokens_Tokens {

  abstract public function tokens(&$tokens);

  abstract public function tokenValues(&$values, $cids, $tokens = array());

  /**
   * @return String
   */
  abstract protected function getTokenName();

  /**
   * Check whether a token is present in the set of tokens.
   *
   * @param $tokens
   * @param $token
   * @return bool
   */
  protected function isTokenInTokens($tokens, $token) {
    if (in_array($token, $tokens)) {
      return true;
    } elseif (isset($tokens[$token])) {
      return true;
    } elseif (isset($tokens[$this->getTokenName()]) && in_array($token, $tokens[$this->getTokenName()])) {
      return true;
    } elseif (isset($tokens[$this->getTokenName()][$token])) {
      return true;
    }
    return FALSE;
  }
  /**
   * Set the value for a token and checks whether cids is an array or not.
   *
   * @param $values
   * @param $cids
   * @param $token
   * @param $tokenValues
   */
  protected function setTokenValue(&$values, $cids, $token, $tokenValues) {
    if (is_array($cids)) {
      foreach ($cids as $cid) {
        $values[$cid][$this->getTokenName() . '.' . $token] = $tokenValues[$cid];
      }
    }
    else {
      $values[$this->getTokenName() . '.' . $token] = $tokenValues[$cids];
    }
  }

}