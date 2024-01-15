<?php
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * fieldofclothofgold implementation : © <Kevin Espinoza> <kespinoza5@ucmerced.edu>
 * 
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * material.inc.php
 *
 * fieldofclothofgold game material description
 *
 * Here, you can describe the material of your game with PHP variables.
 *   
 * This file is loaded in your game logic class constructor, ie these variables
 * are available everywhere in your game logic code.
 *
 */


/*

Example:

$this->card_types = array(
    1 => array( "card_name" => ...,
                ...
              )
);

*/

// $this->card_types = array(
//   'green'
// )

$this->actions = array(
    1 => array("id" => 1, "name" => "dragon", "hasAttachedSquare" => false),
    2 => array("id" => 2, "name" => "secrecy", "hasAttachedSquare" => true),
    3 => array("id" => 3, "name" => "gold", "hasAttachedSquare" => true),
    4 => array("id" => 4, "name" => "blue", "hasAttachedSquare" => true),
    5 => array("id" => 5, "name" => "white", "hasAttachedSquare" => true),
    6 => array("id" => 6, "name" => "red", "hasAttachedSquare" => true),
    7 => array("id" => 7, "name" => "purple", "hasAttachedSquare" => true),
);

$this->colors = array(
  0 => array( 'name' => clienttranslate('blue'),
              'nametr' => self::_('blue') ),
  1 => array( 'name' => clienttranslate('red'),
              'nametr' => self::_('red') ),
  2 => array( 'name' => clienttranslate('gold'),
              'nametr' => self::_('gold') ),
  3 => array( 'name' => clienttranslate('white'),
              'nametr' => self::_('white') ),
  4 => array( 'name' => clienttranslate('green'),
              'nametr' => self::_('green') )
);

$this->tiles = array();

foreach (range(1,12) as $i) 
{
    $this->tiles[$i] = array( 
      // $this->colors[1] = blue tiles
      'color' => $this->colors[0],
      'color_id' => 0,
      'id' => $i,
      'sprite_position' => 0
    );
}

foreach (range(13, 24) as $i) 
{
    $this->tiles[$i] = array(       
      // $this->colors[2] = red tiles
      'color' => $this->colors[1],
      'color_id' => 1,
      'id' => $i,
      'sprite_position' => 1
   );
}

foreach (range(25,36) as $i) 
{
    $this->tiles[$i] = array( 
      // $this->colors[3] = gold tiles
      'color' => $this->colors[2],
      'color_id' => 2,
      'id' => $i,
      'sprite_position' => 2
     );
}

foreach (range(37,48) as $i) 
{
    $this->tiles[$i] = array( 
      // $this->colors[4] = white tiles
      'color' => $this->colors[3],
      'color_id' => 3,
      'id' => $i,
      'sprite_position' => 3
    );
}

foreach (range(49,54) as $i) 
{
    $this->tiles[$i] = array(
      // $this->colors[5] = green tiles
      'color' => $this->colors[4],
      'color_id' => 4,
      'id' => $i,
      'sprite_position' => 4
    );
}