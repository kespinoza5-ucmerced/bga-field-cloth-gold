{OVERALL_GAME_HEADER}

<!-- 
--------
-- BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
-- fieldofclothofgold implementation : © <Kevin Espinoza> <kespinoza5@ucmerced.edu>
-- 
-- This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
-- See http://en.boardgamearena.com/#!doc/Studio for more information.
-------

    fieldofclothofgold_fieldofclothofgold.tpl
    
    This is the HTML template of your game.
    
    Everything you are writing in this file will be displayed in the HTML page of your game user interface,
    in the "main game zone" of the screen.
    
    You can use in this template:
    _ variables, with the format {MY_VARIABLE_ELEMENT}.
    _ HTML block, with the BEGIN/END format
    
    See your "view" PHP file to check how to set variables and control blocks
    
    Please REMOVE this comment before publishing your game on BGA
-->


This is your game interface. You can edit this HTML in your ".tpl" file.

<div id="playertables">

    <!-- BEGIN player -->
    <div class="playertable whiteblock playertable_{PLAYER_ID}">
        <div class="playertablename" style="color:#{PLAYER_COLOR}">
            {PLAYER_NAME}
        </div>
    </div>
    <!-- END player -->

</div>

<div id="myhand_wrap" class="whiteblock">
    <h3>{MY_HAND}</h3>
    <div id="myhand">
    </div>
</div>

<div id="board">
    <div id="oval_space_dragon" class="oval" style="left: 69px; top: 80px;"></div>

    <!-- BEGIN oval -->
        <div id="oval_{X}" class="oval" style="left: {LEFT}px; top: {TOP}px;"></div>
    <!-- END oval -->

    <!-- BEGIN square -->
    <div id="square_{X}" class="square" style="left: {LEFT}px; top: {TOP}px;"></div>
    <!-- END square -->
    
</div>

<script type="text/javascript">

// Javascript HTML templates
var jstpl_cardontable = '<div class="tileontable" id="tileontable_${player_id}" style="background-position:-${x}px">\
                        </div>';


/*
// Example:
var jstpl_some_game_item='<div class="my_game_item" id="my_game_item_${MY_ITEM_ID}"></div>';

*/

</script>  

{OVERALL_GAME_FOOTER}
