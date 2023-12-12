/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * fieldofclothofgold implementation : © <Kevin Espinoza> <kespinoza5@ucmerced.edu>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * fieldofclothofgold.js
 *
 * fieldofclothofgold user interface script
 * 
 * In this file, you are describing the logic of your user interface, in Javascript language.
 *
 */

define([
    "dojo","dojo/_base/declare",
    "ebg/core/gamegui",
    "ebg/counter",
    "ebg/stock",
    g_gamethemeurl+"modules/js/utility.js"
],
function (dojo, declare) {
    return declare("bgagame.fieldofclothofgold", ebg.core.gamegui, {
        constructor: function(){
            console.log('fieldofclothofgold constructor');
              
            // Here, you can init the global variables of your user interface
            // Example:
            // this.myGlobalValue = 0;

            console.log('hearts constructor');
            this.cardwidth = 56;
            this.cardheight = 56;
        },
        
        /*
            setup:
            
            This method must set up the game user interface according to current game situation specified
            in parameters.
            
            The method is called each time the game interface is displayed to a player, ie:
            _ when the game starts
            _ when a player refreshes the game page (F5)
            
            "gamedatas" argument contains all datas retrieved by your "getAllDatas" PHP method.
        */
        
        setup: function( gamedatas )
        {
            console.log( "Starting game setup" );
            console.log('here it is ',this)
            
            this.tableau = {};

            // Setting up player boards
            for( var player_id in gamedatas.players )
            {
                var player = gamedatas.players[player_id];

                // TODO: Setting up players boards if needed
                this.tableau[player_id] = new ebg.stock();
                this.tableau[player_id].create( this, $('playertabletile_'+player_id), this.cardwidth, this.cardheight );
                // this.tableau[player_id].autowidth = true;

                this.tableau[player_id].image_items_per_row = 5;
                
                for ( const color_id in this.gamedatas.tile_types ) {
                    let sprite_position = color_id - 1;
                    this.tableau[player_id].addItemType(color_id, color_id, g_gamethemeurl + 'img/tiles.png', sprite_position);
                    this.tableau[player_id].autowidth = true;
                }
            }
            
            // TODO: Set up your game interface here, according to "gamedatas"
            
            // Player hand
            this.playerHand = new ebg.stock(); // new stock object for hand
            this.playerHand.create( this, $('myhand'), this.cardwidth, this.cardheight );

            this.playerHand.image_items_per_row = 5;

            // Create cards types:
            for ( const color_id in this.gamedatas.tile_types ) {
                console.log('this is the color_id ', color_id)
                let sprite_position = color_id - 1;
                this.playerHand.addItemType(color_id, color_id, g_gamethemeurl + 'img/tiles.png', sprite_position);
            }

            // hook up player hand ??
            dojo.connect( this.playerHand, 'onChangeSelection', this, 'onPlayerHandSelectionChanged' );

            console.log('here is gamedatas ', this.gamedatas);
            console.log('here is hand ',this.gamedatas.hand);
            console.log('here is cardsontable ',this.gamedatas.cardsontable);

            // Cards in player's hand
            for ( let i in this.gamedatas.hand ) {
                let tile = this.gamedatas.hand[i];
                console.log('tile ', tile)
                console.log('add to stock with id: ', tile.type, getTileUniqueType(tile.type), tile.id)
                this.playerHand.addToStockWithId(getTileUniqueType(tile.type), tile.id);
            }

            // Cards played on table
            for ( i in this.gamedatas.cardsontable ) {
                let tile = this.gamedatas.tilesontable[i];
                let player_id = card.location_arg;
                this.playTileOnTable(player_id, tile.id, tile.stock_id);
            }
 
            // Setup game notifications to handle (see "setupNotifications" method below)
            this.setupNotifications();

            // window.addEventListener('resize', function(event) {
            //     this.playerHand.updateDisplay();

            //     // for (tableau in this.tableau[this.player_id]) {
            //     //     tableau.updateDisplay();
            //     // }

            // }, true);
            

            console.log( "Ending game setup" );
        },
       

        ///////////////////////////////////////////////////
        //// Game & client states
        
        // onEnteringState: this method is called each time we are entering into a new game state.
        //                  You can use this method to perform some user interface changes at this moment.
        //
        onEnteringState: function( stateName, args )
        {
            console.log( 'Entering state: '+stateName );
            
            switch( stateName )
            {
            
            /* Example:
            
            case 'myGameState':
            
                // Show some HTML block at this game state
                dojo.style( 'my_html_block_id', 'display', 'block' );
                
                break;
           */
           
           
            case 'dummmy':
                break;
            }
        },

        // onLeavingState: this method is called each time we are leaving a game state.
        //                 You can use this method to perform some user interface changes at this moment.
        //
        onLeavingState: function( stateName )
        {
            console.log( 'Leaving state: '+stateName );
            
            switch( stateName )
            {
            
            /* Example:
            
            case 'myGameState':
            
                // Hide the HTML block we are displaying only during this game state
                dojo.style( 'my_html_block_id', 'display', 'none' );
                
                break;
           */
           
           
            case 'dummmy':
                break;
            }               
        }, 

        // onUpdateActionButtons: in this method you can manage "action buttons" that are displayed in the
        //                        action status bar (ie: the HTML links in the status bar).
        //        
        onUpdateActionButtons: function( stateName, args )
        {
            console.log( 'onUpdateActionButtons: '+stateName );
                      
            if( this.isCurrentPlayerActive() )
            {            
                switch( stateName )
                {
/*               
                 Example:
 
                 case 'myGameState':
                    
                    // Add 3 action buttons in the action status bar:
                    
                    this.addActionButton( 'button_1_id', _('Button 1 label'), 'onMyMethodToCall1' ); 
                    this.addActionButton( 'button_2_id', _('Button 2 label'), 'onMyMethodToCall2' ); 
                    this.addActionButton( 'button_3_id', _('Button 3 label'), 'onMyMethodToCall3' ); 
                    break;
*/
                }
            }
        },        

        ///////////////////////////////////////////////////
        //// Utility methods
        
        /*
        
            Here, you can defines some utility methods that you can use everywhere in your javascript
            script.
        
        */

        playTileOnTable : function(player_id, color, tile_id) {
            if (player_id != this.player_id) {
                // Some opponent played a card
                // Move card from player panel
                this.tableau[this.player_id].addToStockWithId(color, tile_id);
            } else {
                // You played a card. If it exists in your hand, move card from there and remove
                // corresponding item

                if ($('myhand_item_' + tile_id)) {
                    this.playerHand.removeFromStockById(tile_id);
                    this.tableau[this.player_id].addToStockWithId(color, tile_id);
                }
            }
        },

        ///////////////////////////////////////////////////
        //// Player's action
        
        /*
        
            Here, you are defining methods to handle player's action (ex: results of mouse click on 
            game objects).
            
            Most of the time, these methods:
            _ check the action is possible at this game state.
            _ make a call to the game server
        
        */

        onPlayerHandSelectionChanged: function() {
            var items = this.playerHand.getSelectedItems();

            if (items.length > 0) {
                var action = 'dragonSelected';
                if (this.checkAction(action, true)) {
                    // Can play a card

                    var tile_id = items[0].id;
                    console.log("on "+action+" " +tile_id);

                    // type is color
                    var tile_type = items[0].type;
                    this.ajaxcall("/" + this.game_name + "/" + this.game_name + "/" + action + ".html", {
                        id : tile_id,
                        lock : true
                    }, this, function(result) {
                    }, function(is_error) {
                    });
                    
                    // this.playTileOnTable(this.player_id, tile_type, tile_id);

                    this.playerHand.unselectAll();
                } else if (this.checkAction('giveCards')) {
                    // Can give cards => let the player select some cards
                } else {
                    this.playerHand.unselectAll();
                }
            }

            this.playerHand.updateDisplay();
        },
    
        
        /* Example:
        
        onMyMethodToCall1: function( evt )
        {
            console.log( 'onMyMethodToCall1' );
            
            // Preventing default browser reaction
            dojo.stopEvent( evt );

            // Check that this action is possible (see "possibleactions" in states.inc.php)
            if( ! this.checkAction( 'myAction' ) )
            {   return; }

            this.ajaxcall( "/fieldofclothofgold/fieldofclothofgold/myAction.html", { 
                                                                    lock: true, 
                                                                    myArgument1: arg1, 
                                                                    myArgument2: arg2,
                                                                    ...
                                                                 }, 
                         this, function( result ) {
                            
                            // What to do after the server call if it succeeded
                            // (most of the time: nothing)
                            
                         }, function( is_error) {

                            // What to do after the server call in anyway (success or failure)
                            // (most of the time: nothing)

                         } );        
        },        
        
        */

        
        ///////////////////////////////////////////////////
        //// Reaction to cometD notifications

        /*
            setupNotifications:
            
            In this method, you associate each of your game notifications with your local method to handle it.
            
            Note: game notification names correspond to "notifyAllPlayers" and "notifyPlayer" calls in
                  your fieldofclothofgold.game.php file.
        
        */
        setupNotifications: function()
        {
            console.log( 'notifications subscriptions setup' );
            
            // TODO: here, associate your game notifications with local methods
            
            // Example 1: standard notification handling
            // dojo.subscribe( 'cardPlayed', this, "notif_cardPlayed" );
            
            // Example 2: standard notification handling + tell the user interface to wait
            //            during 3 seconds after calling the method in order to let the players
            //            see what is happening in the game.
            // dojo.subscribe( 'cardPlayed', this, "notif_cardPlayed" );
            // this.notifqueue.setSynchronous( 'cardPlayed', 3000 );
            // 
        },  
        
        // TODO: from this point and below, you can write your game notifications handling methods
        
        /*
        Example:
        
        notif_cardPlayed: function( notif )
        {
            console.log( 'notif_cardPlayed' );
            console.log( notif );
            
            // Note: notif.args contains the arguments specified during you "notifyAllPlayers" / "notifyPlayer" PHP call
            
            // TODO: play the card in the user interface.
        },    
        
        */
   });             
});
