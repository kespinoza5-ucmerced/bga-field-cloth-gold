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
  * fieldofclothofgold.game.php
  *
  * This is the main file for your game logic.
  *
  * In this PHP file, you are going to defines the rules of the game.
  *
  */

require_once( APP_GAMEMODULE_PATH.'module/table/table.game.php' );


class fieldofclothofgold extends Table
{
	function __construct( )
	{
        // Your global variables labels:
        //  Here, you can assign labels to global variables you are using for this game.
        //  You can use any number of global variables with IDs between 10 and 99.
        //  If your game has options (variants), you also have to associate here a label to
        //  the corresponding ID in gameoptions.inc.php.
        // Note: afterwards, you can get/set the global variables with getGameStateValue/setGameStateInitialValue/setGameStateValue
        parent::__construct();
       
        self::initGameStateLabels( array( 
            "dragonOccupiedSpace" => 10, 
            "evacuatedSpace" => 11,
            "placementSpace" => 12,
        ) );

        $this->sack = self::getNew( "module.common.deck" );
        $this->sack->init( "card" );
	}
	
    protected function getGameName( )
    {
		// Used for translations and stuff. Please do not modify.
        return "fieldofclothofgold";
    }	

    /*
        setupNewGame:
        
        This method is called only once, when a new game is launched.
        In this method, you must setup the game according to the game rules, so that
        the game is ready to be played.
    */
    protected function setupNewGame( $players, $options = array() )
    {    
        // Set the colors of the players with HTML color code
        // The default below is red/green/blue/orange/brown
        // The number of colors defined here must correspond to the maximum number of players allowed for the gams
        $gameinfos = self::getGameinfos();
        $default_colors = $gameinfos['player_colors'];
 
        // Create players
        // Note: if you added some extra field on "player" table in the database (dbmodel.sql), you can initialize it there.
        $sql = "INSERT INTO player (player_id, player_color, player_canal, player_name, player_avatar) VALUES ";
        $values = array();
        foreach( $players as $player_id => $player )
        {
            $color = array_shift( $default_colors );
            $values[] = "('".$player_id."','$color','".$player['player_canal']."','".addslashes( $player['player_name'] )."','".addslashes( $player['player_avatar'] )."')";
        }
        $sql .= implode( ',', $values );
        self::DbQuery( $sql );

        // Create tokens
        $sql = "INSERT INTO token (token_id, token_player) VALUES ";
        $values = array();
        foreach( $players as $player_id => $player )
        {
            for ( $token_id=1 ; $token_id<=2 ; $token_id++ ) {
                $values[] = "('".$token_id."','".$player_id."')";
            }
        }
        $sql .= implode( ',', $values );
        self::DbQuery( $sql );

        $sql = "INSERT INTO board (board_id, board_action) VALUES ";
        $values = array();
        foreach( $this->actions as $action )
        {
            $values[] = "(".$action['id'].",'".$action['name']."')";
        }
        $sql .= implode( ',', $values );
        self::DbQuery( $sql );

        self::reattributeColorsBasedOnPreferences( $players, $gameinfos['player_colors'] );
        self::reloadPlayersBasicInfos();
        
        /************ Start the game initialization *****/

        // Init global values with their initial values
        //self::setGameStateInitialValue( 'my_first_global_variable', 0 );

        // Create tiles
        $tiles = array ();
        foreach($this->tiles as $tile_id => $tile)
            $tiles[] = array('type' => $tile_id, 'type_arg' => $tile['color_id'], 'nbr' => 1);
        
        $this->sack->createCards( $tiles, 'deck' );

        // Shuffle deck
        $this->sack->shuffle('deck');
        // Deal cards to each players
        $players = self::loadPlayersBasicInfos();
        foreach ( $players as $player_id => $player ) {
            $tiles = $this->sack->pickCards(2, 'deck', $player_id);
        }

        for ( $i=2 ; $i<=7 ; $i++ )
        {
            $this->sack->pickCardForLocation( 'deck', 'board', $i );
        }
        
        // Init game statistics
        // (note: statistics used in this file must be defined in your stats.inc.php file)
        //self::initStat( 'table', 'table_teststat1', 0 );    // Init a table statistics
        //self::initStat( 'player', 'player_teststat1', 0 );  // Init a player statistics (for all players)

        // TODO: setup the initial game situation here

        // Activate first player (which is in general a good idea :) )
        $this->activeNextPlayer();

        /************ End of the game initialization *****/
    }

    /*
        getAllDatas: 
        
        Gather all informations about current game situation (visible by the current player).
        
        The method is called each time the game interface is displayed to a player, ie:
        _ when the game starts
        _ when a player refreshes the game page (F5)
    */
    protected function getAllDatas()
    {
        $result = array();
    
        $current_player_id = self::getCurrentPlayerId();    // !! We must only return informations visible by this player !!
    
        // Get information about players
        // Note: you can retrieve some extra field you added for "player" table in "dbmodel.sql" if you need it.
        $sql = "SELECT player_id id, player_score score FROM player ";
        $result['players'] = self::getCollectionFromDb( $sql );
        $result['board'] = self::getBoard();
        $result['tiles'] = $this->tiles;
        $result['tile_types'] = $this->colors;
        $result['sack'] = $this->sack;
        $result['possibleMoves'] = self::getPossibleMoves();
        $result['possibleSelects'] = self::getPossibleSelects();
        $result['tokens'] = self::getTokens();
        $result['tilesonboard'] = $this->sack->getCardsInLocation( 'board' );
        $result['actions'] = $this->actions;
        $result['dragon'] = self::getGameStateValue('dragonOccupiedSpace');

        // TODO: Gather all information about current game situation (visible by player $current_player_id).
        // Cards in player hand
        $result['hand'] = $this->sack->getCardsInLocation( 'hand', $current_player_id );

        // Cards played on the table
        $result['tableau'] = $this->sack->getCardsInLocation( 'tableau' );
  
        return $result;
    }

    /*
        getGameProgression:
        
        Compute and return the current game progression.
        The number returned must be an integer beween 0 (=the game just started) and
        100 (= the game is finished or almost finished).
    
        This method is called each time we are in a game state with the "updateGameProgression" property set to true 
        (see states.inc.php)
    */
    function getGameProgression()
    {
        // TODO: compute and return the game progression

        return 0;
    }


//////////////////////////////////////////////////////////////////////////////
//////////// Utility functions
////////////    

    /*
        In this space, you can put any utility methods useful for your game logic
    */

    function getTileFromSpace($action_id) {
        $tiles = $this->sack->getCardsInLocation('board', $action_id);
        return array_shift($tiles);
    }

    function getNextPlayerId( )
    {
        $players = self::loadPlayersBasicInfos();
        foreach ( $players as $player_id => $player )
        {
            if ( self::getActivePlayerId() != $player_id )
            {
                return $player_id;
            }
        }
    }

    function replenishTile($evacuated_space) {
        // came from offboard
        if ($evacuated_space == 0)
            return false;

        $DRAGON = 1;
        if ($evacuated_space == $DRAGON)
            return false;

        $tile = $this->sack->pickCardForLocation('deck', 'board', $evacuated_space);

        self::notifyAllPlayers("replenishTile", clienttranslate('New ${tile_color_name} tile draw to action space ${action_name}'), array(
            'action_id' => $evacuated_space,
            'action_name' => $this->actions[$tile['location_arg']]['name'],
            'tile_color' => $tile['type_arg'],
            'tile_id' => $tile['type'],
            'tile_color_name' => $this->colors[$tile['type_arg']]['name']
        ));
    }

    function giveTile($action_id) {
        if ($action_id == 0)
            return false;

        $tile = self::getTileFromSpace($action_id);

        $opponent_id = self::getNextPlayerId();
        $this->sack->moveCard($tile['id'], 'tableau', $opponent_id);

        // send notif to js
        self::notifyAllPlayers("giveTile", clienttranslate('${player_name} receives ${tile_color} tile'), array(
            'player_name' => self::getPlayerNameById($opponent_id),
            'tile_color' => $tile['type_arg'],
            'player_id' => $opponent_id,
            'tile_id' => $tile['type'],
            'action_id' => $action_id
        ));
    }

    function revealTiles($reveal_color) {
        $player_id = self::getActivePlayerId();

        $color_id = -1;
        if ($reveal_color == 'gold')
            $color_id = 2;
        if ($reveal_color == 'blue')
            $color_id = 0;
        if ($reveal_color == 'white')
            $color_id = 3;
        if ($reveal_color == 'red')
            $color_id = 1;
        // this is... not ideal
        if ($reveal_color == 'all')
            $color_id = 100;

        if ($color_id == -1)
            throw new BgaUserException(self::_("discardTiles received an invalid \$reveal_color ($reveal_color)"));

        $tiles = self::DB_getTilesFromHand($player_id, $reveal_color, $color_id);
    
        self::DB_playTilesToTableau($player_id, $tiles);

        self::notifyAllPlayers("revealTiles", clienttranslate('${player_name} revealed ${num_tiles} ${tile_color} tiles'), array(
            'player_name' => self::getActivePlayerName(),
            'player_id' => $player_id,
            'num_tiles' => count($tiles),
            'tile_color' => $reveal_color,
            'tiles' => $tiles
        ));
    }

    function discardTiles($player_id, $tiles, $reveal_color) {
        if ($reveal_color == 'gold')
            return false;

        if ($reveal_color == 'green')
            return false;

        $color_id = -1;
        if ($reveal_color == 'blue')
            $color_id = 0;
        if ($reveal_color == 'white')
            $color_id = 3;
        if ($reveal_color == 'red')
            $color_id = 1;
        if ($reveal_color == 'all')
            throw new BgaUserException(self::_("All discard not implemented!"));

        if ($color_id == -1)
            throw new BgaUserException(self::_("discardTiles received an invalid \$reveal_color ($reveal_color)"));
        
        self::DB_discardTiles($player_id, $color_id);

        self::notifyAllPlayers("discardTiles", clienttranslate('${player_name} discarded ${num_tiles} ${tile_color} tiles'), array(
            'player_name' => self::getPlayerNameById($player_id),
            'player_id' => $player_id,
            'num_tiles' => count($tiles),
            'tile_color' => $reveal_color,
            'tiles' => $tiles
        ));
    }

    function score($player_id, $points) {
        $score = self::DB_updateScore($player_id, $points);

        self::notifyAllPlayers("newScores", clienttranslate('${player_name} scored ${points} points'), array(
            'player_name' => self::getPlayerNameById($player_id),
            'player_id' => $player_id,
            'points' => $points,
            'score' => $score
        ));
    }

    function returnDragonHome($action_id) {
        self::DB_returnDragonHome($action_id);
        self::setGameStateValue('dragonOccupiedSpace', 0);

        self::notifyAllPlayers( "moveDragon", clienttranslate('${player_name} moves dragon to ${action_id}'), array(
            'player_name' => self::getActivePlayerName(),
            'action_id' => 0,
            'player_id' => 'dragon',
            'token_id' => 1
        ));

        return $action_id;
    }

    function tokenSelectionIsValid( $player, $token, $tokens ) {
        if ( ! array_key_exists( $player, $tokens) )
        {
            return false;
        }

        if ( ! array_key_exists( $token, $tokens[$player] ) ) 
        {
            return false;
        }

        return true;
    }   

    function dragonPlacementIsValid($action_id, $vacated_space, $board) {
        if ($board[$action_id]['player'] != null )
            return false;

        if ($board[$action_id] == $vacated_space)
            return false;

        return true;
    }

    function tokenPlacementIsValid( $action_id, $board ) {
        if (! array_key_exists($action_id, $board)) {
            return false;
        }

        // check if space is free of player or dragon
        if ($board[$action_id]['player'] != null) {
            return false;
        }

        // $sql = "SELECT * FROM board 
        //         WHERE board_action='".$action."' AND ".
        //             "board_player IS NULL";
        // $res = self::getCollectionFromDb( $sql );

        // if ( empty( $res ) ) {
        //     throw new BgaUserException(self::_("Move not valid: ") . "$action space is occupied");
        // }

        return true;
    }

    function getTokens() {
        $sql = "SELECT token_player player, token_id id, token_location loc , token_selected selected FROM token";
        return self::getDoubleKeyCollectionFromDB( $sql );
    }

    // currently kills the dragon space since it will never be matched with a card.
    // will also kill spaces that currently dont have a tile...
    function getBoard() {
        $sql = "SELECT board_id id, board_action name, board_token token, board_player player FROM board";
        $board = self::getCollectionFromDb($sql);

        $sql = "SELECT card_location_arg action_id, card_type tile_id, card_type_arg tile_color FROM card 
                WHERE card_location='board'";
        $tiles = self::getCollectionFromDb($sql);

        for ($i=1; $i<=sizeof($board); $i++) {
            if (! array_key_exists($board[$i]["id"], $tiles)) {
                $board[$i]["tile"] = null;
                $board[$i]["tile_color"] = null;
                continue;
            }

            $tile = $tiles[$board[$i]["id"]];
            $board[$i]["tile"] = $tile["tile_id"];
            $board[$i]["tile_color"] = $tile["tile_color"];
        }

        return $board;
    }

    function getPossibleSelects() {
        $player_id = self::getActivePlayerId();
        $result = array();

        $board = self::getBoard();

        foreach ( $board as $space )
        {
            if ( $space['player'] == $player_id )
            {
                $result[$space['id']] = $space;
            }
        }

        return $result;
    }

    function getPossibleMoves() {
        $result = array();

        $board = self::getBoard();

        foreach ( $board as $action ) {
            if ( $action['player'] == NULL ) {
                $result[$action['id']] = $action['id'];
            }
        }

        return $result;
    }

    function getPossibleDragonPlacements() {
        $result = array();

        $board = self::getBoard();

        $evacuated_space = self::getGameStateValue('evacuatedSpace');
        foreach ($board as $id => $action)
            if ($action['player'] == NULL && $id != $evacuated_space)
                $result[$action['id']] = $action['id'];
        
        return $result;
    }

//////////////////////////////////////////////////////////////////////////////
//////////// Player actions
//////////// 

    /*
        Each time a player is doing some game action, one of the methods below is called.
        (note: each method below must match an input method in fieldofclothofgold.action.php)
    */

    function selectToken($player_id, $token_id) {
        self::checkAction("selectToken");

        // check that player token was selected
        $tokens = self::getTokens();
        if ( ! self::tokenSelectionIsValid( $player_id, $token_id, $tokens ) )
        {
            throw new BgaUserException(self::_("Move not valid: ") . "$player_id selects their token $token_id");
        }

        $current_player_id = self::getActivePlayerId();
        $action_space =  $tokens[$current_player_id][$token_id]['loc'];

        // update db to select token
        $sql = "UPDATE token SET token_selected=1 
                WHERE token_player='".$current_player_id."' AND
                    token_id=".$token_id;
        self::DbQuery( $sql );

        // Statistics
        
        // Notify
        self::notifyAllPlayers( "selectToken", clienttranslate( '${player_name} selects token on ${action_name} space' ), array(
            'player_name' => self::getActivePlayerName(),
            'action_name' => $action_space,
            'player_id' => $player_id,
            'token_id' => $token_id
        ) );

        $this->gamestate->nextState( 'selectedToken' );

        // throw new BgaUserException(self::_("Not implemented: ") . "token $player_id - $token_id placed");
    }

    function placeDragon($action_id) {
        self::checkAction("placeDragon");
        $player_id = self::getActivePlayerId();
        $vacated_space = self::getGameStateValue('evacuatedSpace');

        $board = self::getBoard();

        if (! self::dragonPlacementIsValid($action_id, $vacated_space, $board))
            throw new BgaUserException(self::_("Move not valid: ") . "$player_id places at $action_id");

        self::DB_placeTokenOnBoard('dragon', 1, $action_id);
        self::setGameStateValue('dragonOccupiedSpace', $action_id);

        self::notifyAllPlayers( "moveDragon", clienttranslate('${player_name} moves dragon to ${action_id}'), array(
            'player_name' => self::getActivePlayerName(),
            'action_id' => $action_id,
            'player_id' => $player_id,
            'token_id' => 1
        ));

        $this->gamestate->nextState("");
    }

    function placeToken($action_id) {
        self::checkAction("placeToken");
        $player_id = self::getActivePlayerId();

        $board = self::getBoard();

        if (! self::tokenPlacementIsValid($action_id, $board)) {
            throw new BgaUserException(self::_("Move not valid: ") . "$player_id places at $action_id");
        }

        $sql = "SELECT token_player player, token_id id, token_location loc FROM token
                WHERE token_player='".$player_id."' AND token_selected=1";
        $selected_token = self::getObjectFromDB($sql);
        $selected_token_id = $selected_token['id'];

        // remove old token
        $sql = "UPDATE board SET board_player=NULL, board_token=NULL 
                WHERE board_player='".$player_id."' AND board_token=".$selected_token_id;
        self::DbQuery( $sql );

        $sql = "UPDATE token SET token_location='".$action_id."'
                WHERE token_id=".$selected_token_id." AND
                    token_player='".$player_id."'";
        self::DbQuery( $sql );

        // move selected token
        $sql = "UPDATE board SET board_player='".$player_id."', board_token=".$selected_token_id." WHERE board_id='".$action_id."'";
        self::DbQuery( $sql );

        // unselect token
        $sql = "UPDATE token SET token_selected=0 WHERE token_id=".$selected_token_id." AND token_player='".$player_id."'";
        self::DbQuery( $sql );

        self::setGameStateValue('placementSpace', $action_id);

        if ($selected_token["loc"] != 'supply') {
            self::setGameStateValue('evacuatedSpace', $selected_token["loc"]);
        }

        $evacuated_space = self::getGameStateValue('evacuatedSpace');
        // if ($evacuated_space == 1) {
        //     self::setGameStateValue('dragonOccupiedSpace', $selected_token_id);
        // }

        // Statistics

        // Notify
        self::notifyAllPlayers( "moveToken", clienttranslate( '${player_name} moves token to ${action_id}' ), array(
            'player_name' => self::getActivePlayerName(),
            'action_id' => $action_id,
            'player_id' => $player_id,
            'token_id' => $selected_token_id
        ) );
        
        // // tokens left in player supply
        // $sql = "SELECT COUNT(token_id) num_tokens FROM token 
        //         WHERE token_location!='supply' AND
        //             token_player='".$player_id."'";
        // $remainingNumTokens = self::getUniqueValueFromDB( $sql );

        // // Notify token was used from supply
        // self::notifyAllPlayers( "tokensInSupply", '', array(
        //     'player_id' => $player_id,
        //     'tokensInSupply' => $remainingNumTokens
        // ) );

        $state = 'performAction';
        if ($action_id == 1)
            $state = 'placeDragon';
        $this->gamestate->nextState($state);
    }

    /*
    
    Example:

    function playCard( $card_id )
    {
        // Check that this is the player's turn and that it is a "possible action" at this game state (see states.inc.php)
        self::checkAction( 'playCard' ); 
        
        $player_id = self::getActivePlayerId();
        
        // Add your game logic to play a card there 
        ...
        
        // Notify all players about the card played
        self::notifyAllPlayers( "cardPlayed", clienttranslate( '${player_name} plays ${card_name}' ), array(
            'player_id' => $player_id,
            'player_name' => self::getActivePlayerName(),
            'card_name' => $card_name,
            'card_id' => $card_id
        ) );
          
    }
    
    */

    
//////////////////////////////////////////////////////////////////////////////
//////////// Game state arguments
////////////

    /*
        Here, you can create methods defined as "game state arguments" (see "args" property in states.inc.php).
        These methods function is to return some additional information that is specific to the current
        game state.
    */

    function argPlaceDragon() {
        return array (
            'possibleMoves' => self::getPossibleDragonPlacements()
        );
    }

    function argPlaceToken() {
        return array (
            'possibleMoves' => self::getPossibleMoves( self::getActivePlayerId() )
        );
    }

    function argSelectToken() {
        return array (
            'possibleSelects' => self::getPossibleSelects( self::getActivePlayerId() )
        );
    }


    /*
    
    Example for game state "MyGameState":
    
    function argMyGameState()
    {
        // Get some values from the current game situation in database...
    
        // return values:
        return array(
            'variable1' => $value1,
            'variable2' => $value2,
            ...
        );
    }    
    */

//////////////////////////////////////////////////////////////////////////////
//////////// Game state actions
////////////

    /*
        Here, you can create methods defined as "game state actions" (see "action" property in states.inc.php).
        The action method of state X is called everytime the current game state is set to X.
    */

    function stSelectTokenFromSupply() {
        $player_id = self::getActivePlayerID();
        // query or player tokens in supply
        $sql = "SELECT token_player player, MIN(token_id) id, token_location loc FROM token
                WHERE token_location='supply' AND 
                    token_player='".$player_id."'";
        $selected_token = self::getCollectionFromDb( $sql );

        $sql = "UPDATE token SET token_selected=1 WHERE token_player='".$player_id."' AND token_id=".$selected_token[$player_id]['id'];
        self::DbQuery( $sql );

        $this->gamestate->nextState("");
    }
    
    function stNextPlayer() {
        $player_id = self::getActivePlayerId();
        $opponent_id = self::getNextPlayerId(); 
        // TO DO: If player has reached 30 points
        if (self::DB_getScore($player_id) >= 30 || self::DB_getScore($opponent_id) >= 30) {
            $this->gamestate->nextState('endGame');
            return ;
        }

        // TO DO: If bag is out of tiles
        if (empty($this->sack->getCardsInLocation('deck'))) {
            $this->gamestate->nextState('endGame');
            return ;
        }

        // If player has tokens left in supply
        $player_id = self::activeNextPlayer();
        self::giveExtraTime($player_id);
        $sql = "SELECT COUNT(token_id) FROM token WHERE token_location='supply' AND token_player='".$player_id."'";
        $tokens_in_supply = self::getUniqueValueFromDB( $sql );

        if ( $tokens_in_supply > 0 ) {
            $this->gamestate->nextState( 'placeFromSupply' );
            return ;
        }

        // Standard case (next turn)
        $this->gamestate->nextState( 'placeFromBoard' );
        return ;
    }

    function stPerformAction() {
        $player_id = self::getActivePlayerId();
        $opponent_id = self::getNextPlayerId();
        $placement_space = self::getGameStateValue('placementSpace');
        $evacuated_space = self::getGameStateValue('evacuatedSpace');
        $dragonoccupied_space = self::getGameStateValue('dragonOccupiedSpace');

        $gift_space = 0;
        if ($placement_space >= 2 && $placement_space <= 7)
            $gift_space = $placement_space;
        if ($placement_space == 1 && $dragonoccupied_space != 0)
            $gift_space = $dragonoccupied_space;
        // throw new BgaUserException(self::_("gift at space ").$gift_space);
        self::giveTile($gift_space);

        if ($placement_space == 2)
            self::secrecyAction($player_id);
        if ($placement_space == 3)
            self::goldAction();
        if ($placement_space == 4)
            self::blueAction($player_id);
        if ($placement_space == 5)
            self::whiteAction($player_id);
        if ($placement_space == 6)
            self::redAction($player_id, $opponent_id);
        if ($placement_space == 7)
            self::purpleAction($player_id);

        // return dragon home
        if ($evacuated_space == 1)
            self::returnDragonHome($dragonoccupied_space);

        $replenish_space = 0;
        if ($evacuated_space != 0 && $evacuated_space != 1)
            $replenish_space = $evacuated_space;
        if ($evacuated_space == 1)
            $replenish_space = $dragonoccupied_space;
        self::replenishTile($replenish_space);

        $this->gamestate->nextState("");
    }

    /*
    
    Example for game state "MyGameState":

    function stMyGameState()
    {
        // Do some stuff ...
        
        // (very often) go to another gamestate
        $this->gamestate->nextState( 'some_gamestate_transition' );
    }    
    */

//////////////////////////////////////////////////////////////////////////////
//////////// Board actions
////////////

    function secrecyAction($player_id) {
        $score = self::DB_getScore($player_id);
        $tile_draws = [ 0 => 1, 1 => 2, 2 => 2, 3 => 3, 4 => 0 ];

        $num_tiles_to_draw = $tile_draws[floor($score/8)];

        // for ($i=0; $i < $num_tiles_to_draw; $i++)
        $drawn_tiles = $this->sack->pickCards($num_tiles_to_draw, 'deck', $player_id);

        self::notifyAllPlayers("secrecyDrawPublic", clienttranslate('${player_name} draws ${num_tiles} tiles' ), array(
            'player_name' => self::getActivePlayerName(),
            'num_tiles' => count($drawn_tiles)
        ));

        $this->notifyPlayer($player_id, "secrecyDrawPrivate", clienttranslate('${player_name} draws: '), array(
            'player_name' => self::getPlayerNameById($player_id),
            'tiles' => $drawn_tiles
        ));
    }

    function goldAction() {
        $player_id = self::getActivePlayerId();
        $opponent_id = self::getNextPlayerId();

        self::revealTiles('gold');

        $tableaus = self::DB_getPlayerTableaus();

        $points = 0;
        if (count($tableaus[$player_id][2]) > count($tableaus[$opponent_id][2]))
            $points = 2;

        self::score($player_id, $points);
    }

    function blueAction($player_id) {
        self::revealTiles('blue');

        $tableaus = self::DB_getPlayerTableaus();

        $points = 0;
        if (count($tableaus[$player_id][0]) == 1)
            $points = 1;
        if (count($tableaus[$player_id][0]) == 2)
            $points = 3;
        if (count($tableaus[$player_id][0]) >= 3)
            $points = 6;

        self::score($player_id, $points);
        self::discardTiles($player_id, $tableaus[$player_id][0], 'blue');
    }

    function whiteAction($player_id) {
        self::revealTiles('white');
        $tableaus = self::DB_getPlayerTableaus();

        $points = count($tableaus[$player_id][3]);
        self::score($player_id, $points);
        self::discardTiles($player_id, $tableaus[$player_id][3], 'white');
    }

    function redAction($player_id, $opponent_id) {
        self::revealTiles('red');
        $tableaus = self::DB_getPlayerTableaus();

        $player_points = count($tableaus[$player_id][1]);
        $opponent_points = count($tableaus[$opponent_id][1]);
        self::score($player_id, $player_points);
        self::score($opponent_id, $opponent_points);
        self::discardTiles($player_id, $tableaus[$player_id][1], 'red');
        self::discardTiles($opponent_id, $tableaus[$opponent_id][1], 'red');

        self::secrecyAction($player_id);
        self::secrecyAction($opponent_id);
    }

    function purpleAction($player_id) {
        self::revealTiles('all');

        $tableaus = self::DB_getPlayerTableaus();
        $this->debug("look here @ tableaus: " . json_encode($tableaus, JSON_PRETTY_PRINT) . " // ");

        $min_tiles = INF;
        foreach ($tableaus[$player_id] as $color_id => $tiles) {
            $min_tiles = min(count($tiles), $min_tiles);
        }

        $points = 2 * $min_tiles;
        self::score($player_id, $points);
    }

//////////////////////////////////////////////////////////////////////////////
//////////// DB functions
////////////

    function DB_getScore($player_id) {
        $sql = "SELECT player_score from player where player_id=$player_id";
        return self::getUniqueValueFromDB($sql);
    }

    function DB_getPlayerTableaus() {
        $sql = "SELECT card_location_arg, card_type_arg, card_id, card_type FROM card WHERE card_location='tableau'";
        $tiles = self::getObjectListFromDB($sql);
        $test = self::getDoubleKeyCollectionFromDB($sql);

        // if (!$tiles)
        //     return NULL;

        $player_id = self::getActivePlayerId();
        $opponent_id = self::getNextPlayerId();
        $tableaus = array(
            $player_id => array(),
            $opponent_id => array()
        );

        foreach ($this->colors as $id => $color) {
            $tableaus[$player_id][$id] = array();
            $tableaus[$opponent_id][$id] = array();
        }
        
        foreach ($tiles as $i => $tile) {
            $player_id = $tile['card_location_arg'];
            // if (!array_key_exists($player_id, $tableaus))
                // $tableaus[$player_id] = array();

            $tile_color = $tile['card_type_arg'];
            // if (!array_key_exists($tile_color, $tableaus[$player_id]))
                // $tableaus[$player_id][$tile_color] = array();

            $tableaus[$player_id][$tile_color] [] = $tile;
        }

        return $tableaus;
    }

    function DB_getTilesFromHand($player_id, $reveal_color, $color_id) {
        if ($reveal_color == 'all') {
            $sql = "SELECT * FROM card 
                    WHERE card_location='hand' AND card_location_arg=$player_id";
            return self::getObjectListFromDB($sql);
        }

        $sql = "SELECT * FROM card 
                WHERE card_location='hand' AND card_location_arg=$player_id AND
                    card_type_arg=$color_id";
        return self::getObjectListFromDB($sql);
    }

    function DB_playTilesToTableau($player_id, $tiles) {
        foreach($tiles as $idx => $tile) {
            $sql = "UPDATE card SET card_location='tableau' WHERE card_location='hand' AND card_location_arg=$player_id and card_type=".$tile['card_type'];
            self::DbQuery($sql);
        }
    }

    function DB_updateScore($player_id, $points) {
        $score = self::DB_getScore($player_id) + $points;
        $sql = "UPDATE player SET player_score=$score WHERE player_id=$player_id";
        self::DbQuery($sql);
        return $score;
    }

    function DB_discardTiles($player_id, $color_id) {
        $sql = "UPDATE card SET card_location='discard', card_location_arg=-1 
                WHERE card_location='tableau' AND card_location_arg=$player_id AND
                    card_type_arg=$color_id";
        self::DbQuery($sql);
    }

    function DB_returnDragonHome($action_id) {
        $sql = "UPDATE board SET board_player=NULL, board_token=NULL WHERE board_player='dragon'";
        self::DbQuery($sql);
    }

    function DB_placeTokenOnBoard($player_id, $token_id, $action_id) {
        $sql = "UPDATE board SET board_player='$player_id', board_token=$token_id 
                WHERE board_id=$action_id";
        self::DbQuery($sql);
    }

//////////////////////////////////////////////////////////////////////////////
//////////// Zombie
////////////

    /*
        zombieTurn:
        
        This method is called each time it is the turn of a player who has quit the game (= "zombie" player).
        You can do whatever you want in order to make sure the turn of this player ends appropriately
        (ex: pass).
        
        Important: your zombie code will be called when the player leaves the game. This action is triggered
        from the main site and propagated to the gameserver from a server, not from a browser.
        As a consequence, there is no current player associated to this action. In your zombieTurn function,
        you must _never_ use getCurrentPlayerId() or getCurrentPlayerName(), otherwise it will fail with a "Not logged" error message. 
    */

    function zombieTurn( $state, $active_player )
    {
    	$statename = $state['name'];
    	
        if ($state['type'] === "activeplayer") {
            switch ($statename) {
                default:
                    $this->gamestate->nextState( "zombiePass" );
                	break;
            }

            return;
        }

        if ($state['type'] === "multipleactiveplayer") {
            // Make sure player is in a non blocking status for role turn
            $this->gamestate->setPlayerNonMultiactive( $active_player, '' );
            
            return;
        }

        throw new feException( "Zombie mode not supported at this game state: ".$statename );
    }
    
///////////////////////////////////////////////////////////////////////////////////:
////////// DB upgrade
//////////

    /*
        upgradeTableDb:
        
        You don't have to care about this until your game has been published on BGA.
        Once your game is on BGA, this method is called everytime the system detects a game running with your old
        Database scheme.
        In this case, if you change your Database scheme, you just have to apply the needed changes in order to
        update the game database and allow the game to continue to run with your new version.
    
    */
    
    function upgradeTableDb( $from_version )
    {
        // $from_version is the current version of this game database, in numerical form.
        // For example, if the game was running with a release of your game named "140430-1345",
        // $from_version is equal to 1404301345
        
        // Example:
//        if( $from_version <= 1404301345 )
//        {
//            // ! important ! Use DBPREFIX_<table_name> for all tables
//
//            $sql = "ALTER TABLE DBPREFIX_xxxxxxx ....";
//            self::applyDbUpgradeToAllDB( $sql );
//        }
//        if( $from_version <= 1405061421 )
//        {
//            // ! important ! Use DBPREFIX_<table_name> for all tables
//
//            $sql = "CREATE TABLE DBPREFIX_xxxxxxx ....";
//            self::applyDbUpgradeToAllDB( $sql );
//        }
//        // Please add your future database scheme changes here
//
//


    }    
}
