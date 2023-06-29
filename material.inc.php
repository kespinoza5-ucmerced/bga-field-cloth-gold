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

$this->tiles = array();

foreach (range(0,11) as $i) 
{
    $this->tiles[$i] = array( 
      'color' => 'blue',
      'sprite_position' => 0
    );
}

foreach (range(12, 23) as $i) 
{
    $this->tiles[$i] = array( 
      'color' => 'red',
      'sprite_position' => 1
   );
}

foreach (range(24,35) as $i) 
{
    $this->tiles[$i] = array( 
      'color' => 'gold',
      'sprite_position' => 2
     );
}

foreach (range(36,47) as $i) 
{
    $this->tiles[$i] = array( 
      'color' => 'white',
      'sprite_position' => 3
    );
}

foreach (range(48,53) as $i) 
{
    $this->tiles[$i] = array( 
      'color' => 'green',
      'sprite_position' => 4
    );
}