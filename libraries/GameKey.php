<?php

namespace bnphpbot\Libraries;

use \LogicException;

class GameKey {

  const REGEX_BLIZZARD_THIRTEEN_NUMERIC = '/[0123456789]{13}/';
  const REGEX_BLIZZARD_SIXTEEN_ALPHA    = '/[246789BCDEFGHJKMNPRTVWXZ]{16}/';
  const REGEX_BLIZZARD_TWENTY_SIX_ALPHA = '/[246789BCDEFGHJKMNPRTVWXYZ]{26}/';

  protected $key;

  protected $key_product;
  protected $key_public;
  protected $key_private;

  public function __construct( $key ) {
    $this->key = $key;
    $this->seed();
  }

  public function getPrivate() {
    return $this->key_private;
  }

  public function getProduct() {
    return $this->key_product;
  }

  public function getPublic() {
    return $this->key_public;
  }

  protected function seed() {
    $key = strtoupper( $this->key );

    // TODO

    if ( preg_match( self::REGEX_BLIZZARD_THIRTEEN_NUMERIC, $key ) === 1 )
    {
      // 13-digit
    }
    else if ( preg_match( self::REGEX_BLIZZARD_SIXTEEN_ALPHA, $key ) === 1 )
    {
      // 16-character
    }
    else if ( preg_match( self::REGEX_BLIZZARD_TWENTY_SIX_ALPHA, $key ) === 1 )
    {
      // 26-character
    }
    else
    {
      throw new LogicException('Unidentifiable game key');
    }

  }

}
