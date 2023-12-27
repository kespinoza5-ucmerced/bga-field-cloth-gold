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

 action_spaces = { 
    1: "dragon",
    2: "secrecy",
    3: "gold",
    4: "blue",
    5: "white",
    6: "red",
    7: "purple"
}

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
            console.log('here is gamedatas ',this.gamedatas)
            
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
                let sprite_position = color_id - 1;
                this.playerHand.addItemType(color_id, color_id, g_gamethemeurl + 'img/tiles.png', sprite_position);
            }

            // hook up player hand ??
            dojo.connect( this.playerHand, 'onChangeSelection', this, 'onPlayerHandSelectionChanged' );

            // Cards in player's hand
            for ( let i in this.gamedatas.hand ) {
                let tile = this.gamedatas.hand[i];
                this.playerHand.addToStockWithId(getTileUniqueType(tile.type), tile.id);
            }

            // Cards played on table
            for ( i in this.gamedatas.cardsontable ) {
                let tile = this.gamedatas.tilesontable[i];
                let player_id = card.location_arg;
                this.playTileOnTable(player_id, tile.id, tile.stock_id);
            }

            for ( var [space, arg] of Object.entries(this.gamedatas.board) )
            {
                if ( arg.player != null ) {
                    // function( action_name, player_id, token_id )
                    this.addTokenOnBoard(space, arg.player, arg.token);
                }
            }

            // dojo.query( 'tokens' ).connect( 'onclick', this, 'onMoveToken' );

            dojo.query( '.circle_action' ).connect( 'onclick', this, 'onPlaceToken' );
            dojo.query( '.token' ).connect( 'onclick', this, 'onSelectToken' );
 
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
                case 'placeToken':
                    if ( this.player_id == args.active_player ) 
                    {
                        this.updatePossibleMoves( args.args.possibleMoves );
                    }

                    break;
                    
                case 'selectToken':
                    if ( this.player_id == args.active_player ) 
                    {
                        this.updatePossibleSelects( args.args.possibleSelects );
                    }

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
                case 'placeToken':        
                    dojo.query( '.possible_move' ).removeClass( 'possible_move' );            
                    break;

                case 'selectToken':
                    // dojo.query( '.possible_select' ).removeClass( 'possible_select' );

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

        updatePossibleMoves: function( possibleMoves )
        {
            console.log('made it to updateMove')
            // Remove current possible moves
            dojo.query( '.possible_move' ).removeClass( 'possible_move' );

            for( var space of Object.values(possibleMoves) )
            {
                dojo.addClass( 'circle_action_'+space, 'possible_move' );
            }
                        
            this.addTooltipToClass( 'possibleMove', '', _('Place token here') );
        },
    
        updatePossibleSelects: function( possibleSelects )
        {
            console.log('made it to updateSelect')
            var color = this.gamedatas.players[this.player_id].color;

            for( var token of Object.values(possibleSelects) )
            {
                var token_selector = 'token_'+token.player+'_'+token.token;
                
                dojo.addClass( token_selector, 'possible_select' );
            }

            this.addTooltipToClass( 'possible_select', '', _('Select token') );
        },

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

        addTokenOnBoard: function( action_name, player_id, token_id )
        {
            console.log('in addTokenOnBoard')

            dojo.place( this.format_block( 'jstpl_token', {
                color: this.gamedatas.players[ player_id ].color,
                player_id: player_id,
                token_id: token_id
            } ) , 'tokens' );
            
            var token_selector = 'token_'+player_id+'_'+token_id;

            this.placeOnObject( token_selector, 'overall_player_board_'+player_id );
            this.slideToObject( token_selector, 'circle_action_'+action_name ).play();
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

        

        onPlaceToken: function( evt ) {
            dojo.stopEvent( evt );

            // Get the clicked circle x
            // Note: circle id format is "circle_action_X"
            var coords = evt.currentTarget.id.split('_');
            var x = coords[2];
            console.log("here is the action", x)

            // // check that polled action is possible
            // if( ! dojo.hasClass( 'circle_action_'+x, 'possibleMove' ) )
            // {
            //     console.log('move is not possible')
            //     return ;
            // }

            var action = 'placeToken';
            console.log("on "+action+" to "+x);

            if (this.checkAction(action, true)) {
                // Can move a token
                this.ajaxcall("/" + this.game_name + "/" + this.game_name + "/" + action + ".html", {
                    x:x
                }, this, function(result) {
                }, function(is_error) {
                });
            }
        },

        onSelectToken: function( evt ) {
            dojo.stopEvent( evt );

            // // Get the clicked circle x
            // // Note: circle id format is "circle_action_X"
            var token = evt.currentTarget.id.split('_');
            var player_id = token[1];
            var token_id = token[2];

            // if( ! dojo.hasClass( 'circle_action_'+x, 'possibleMove' ) )
            // {
            //     // This is not a possible move => the click does nothing
            //     return ;
            // }

            var action = 'selectToken';
            console.log('on '+action+'move token '+token_id);

            // if (this.checkAction(action, true)) {
            //     // Can move a token
            //     this.ajaxcall("/" + this.game_name + "/" + this.game_name + "/" + action + ".html", {
            //         x:x
            //     }, this, function(result) {
            //     }, function(is_error) {
            //     });
            // }
        },

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

            dojo.subscribe( 'moveToken', this, "notif_moveToken" );
            this.notifqueue.setSynchronous( 'moveToken', 500 );
            
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
        
        notif_moveToken: function( notif )
        {
            // Remove current possible moves (makes the board more clear)
            dojo.query( '.possibleMove' ).removeClass( 'possibleMove' );

            this.addTokenOnBoard( notif.args.action_name, notif.args.player_id, notif.args.token_id );
        },


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
