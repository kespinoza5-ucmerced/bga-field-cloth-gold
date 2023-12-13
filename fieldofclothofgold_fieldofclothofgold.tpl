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
        <div class="playertabletile" id="playertabletile_{PLAYER_ID}">
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
    <div id="circle_action_0" class="circle_dragon" style="left: 70px; top: 92px;"></div>

    <div id="tokens">
        <div id="circle_action_1" class="circle_action" style="left: 70px; top: 145px;"></div>
        <div id="circle_action_2" class="circle_action" style="left: 149px; top: 145px;"></div>
        <div id="circle_action_3" class="circle_action" style="left: 242px; top: 145px;"></div>
        <div id="circle_action_4" class="circle_action" style="left: 336px; top: 145px;"></div>
        <div id="circle_action_5" class="circle_action" style="left: 431px; top: 145px;"></div>
        <div id="circle_action_6" class="circle_action" style="left: 525px; top: 145px;"></div>
        <div id="circle_action_7" class="circle_action" style="left: 619px; top: 145px;"></div>
    </div>

    <!-- BEGIN square -->
    <div id="square_{X}" class="square" style="left: {LEFT}px; top: {TOP}px;"></div>
    <!-- END square -->

    <div id="score_track">
        <div id="score_track_0" class="circle_score" style="left: 328px; top: 292px;"></div>
        <div id="score_track_1" class="circle_score" style="left: 376px; top: 292px;"></div>
        <div id="score_track_2" class="circle_score" style="left: 427px; top: 292px;"></div>
        <div id="score_track_3" class="circle_score" style="left: 476px; top: 292px;"></div>
        <div id="score_track_4" class="circle_score" style="left: 525px; top: 292px;"></div>
        <div id="score_track_5" class="circle_score" style="left: 575px; top: 292px;"></div>
        <div id="score_track_6" class="circle_score" style="left: 626px; top: 292px;"></div>
        <div id="score_track_7" class="circle_score" style="left: 676px; top: 292px;"></div>

        <div id="score_track_8" class="circle_score" style="left: 328px; top: 340px;"></div>
        <div id="score_track_9" class="circle_score" style="left: 376px; top: 340px;"></div>
        <div id="score_track_10" class="circle_score" style="left: 427px; top: 340px;"></div>
        <div id="score_track_11" class="circle_score" style="left: 476px; top: 340px;"></div>
        <div id="score_track_12" class="circle_score" style="left: 525px; top: 340px;"></div>
        <div id="score_track_13" class="circle_score" style="left: 575px; top: 340px;"></div>
        <div id="score_track_14" class="circle_score" style="left: 626px; top: 340px;"></div>
        <div id="score_track_15" class="circle_score" style="left: 676px; top: 340px;"></div>

        <div id="score_track_16" class="circle_score" style="left: 328px; top: 388px;"></div>
        <div id="score_track_17" class="circle_score" style="left: 377px; top: 388px;"></div>
        <div id="score_track_18" class="circle_score" style="left: 427px; top: 388px;"></div>
        <div id="score_track_19" class="circle_score" style="left: 476px; top: 388px;"></div>
        <div id="score_track_20" class="circle_score" style="left: 525px; top: 388px;"></div>
        <div id="score_track_21" class="circle_score" style="left: 575px; top: 388px;"></div>
        <div id="score_track_22" class="circle_score" style="left: 626px; top: 388px;"></div>
        <div id="score_track_23" class="circle_score" style="left: 676px; top: 388px;"></div>

        <div id="score_track_24" class="circle_score" style="left: 327px; top: 436px;"></div>
        <div id="score_track_25" class="circle_score" style="left: 376px; top: 436px;"></div>
        <div id="score_track_26" class="circle_score" style="left: 426px; top: 436px;"></div>
        <div id="score_track_27" class="circle_score" style="left: 476px; top: 436px;"></div>
        <div id="score_track_28" class="circle_score" style="left: 525px; top: 436px;"></div>
        <div id="score_track_29" class="circle_score" style="left: 575px; top: 436px;"></div>
        <div id="score_track_30" class="circle_score" style="left: 624px; top: 436px;"></div>
        <div id="score_track_31" class="circle_score" style="left: 674px; top: 436px;"></div>
        
        <div id="score_track_32" class="circle_score" style="left: 324px; top: 484px;"></div>
        <div id="score_track_33" class="circle_score" style="left: 375px; top: 484px;"></div>
        <div id="score_track_34" class="circle_score" style="left: 424px; top: 484px;"></div>
        <div id="score_track_35" class="circle_score" style="left: 474px; top: 484px;"></div>
        <div id="score_track_36" class="circle_score" style="left: 524px; top: 484px;"></div>
        <div id="score_track_37" class="circle_score" style="left: 573px; top: 484px;"></div>
        <div id="score_track_38" class="circle_score" style="left: 623px; top: 484px;"></div>
        <div id="score_track_39" class="circle_score" style="left: 672px; top: 484px;"></div>
    </div>
</div>

<script type="text/javascript">

var jstpl_token='<div class="token tokencolor_${color}" id="token_${x}\"></div>';

/*
// Example:
var jstpl_some_game_item='<div class="my_game_item" id="my_game_item_${MY_ITEM_ID}"></div>';

*/

</script>  

{OVERALL_GAME_FOOTER}
