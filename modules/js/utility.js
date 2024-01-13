function getTileUniqueType(color) {
    if (color == 'blue') {
        return 1;
    } else if (color == 'red') {
        return 2;
    } else if (color == 'gold') {
        return 3;
    } else if (color == 'white') {
        return 4;
    } else if (color == 'green') {
        return 5;
    }

    // means error
    return -1;
}

function getTileTypeFromId( tile_id ) {
    // blue
    if (tile_id >=0 && tile_id <= 11) {
        return 1
    }

    // red
    if (tile_id >= 12 && tile_id <= 23) {
        return 2
    }

    // gold
    if (tile_id >= 24 && tile_id <= 35) {
        return 3
    }

    // white
    if (tile_id >= 36 && tile_id <= 47) {
        return 4
    }

    // green
    if (tile_id >= 48 && tile_id <= 53) {
        return 5
    }
}

function createAction(action_id, bga) {
    const DRAGON = 1
    const action = bga.gamedatas.actions[action_id]

    if (action_id == DRAGON)
        return new DragonAction(action, bga)
    
    if (action_id in bga.gamedatas.actions)
        return new SquareAction(action, bga)

    return null
}

class Action {
    // action_id, action_square, this
    constructor (action) {
        this.id = action.id
        this.name = action.name
        this.hasAttachedSquare = action.hasAttachedSquare
        this.token = null
    }

    placeTokenFrom(location, bga) {
        function getTokenAt(location, bga) {

        }

        // if location is player_id stock
            // construct playerboard_ selector
            // get token at location

        // if location is an action_id
            // construct action selector
            // get token at location

        // perform slide
    }
}

class DragonAction extends Action {
    constructor(action) {
        super(action)
    }

    replaceDragon() {

    }

    placeTile(tile_id, bga) {
        return false
    }
}

class SquareAction extends Action {
    constructor(action, bga) {
        super(action)
        this.square = new ebg.stock()
        bga.initTileStock(this.square, 'square_action_'+action.id)
    }

    placeTile(bga) {
        // what if tile not in gamedatas cuz notify?
        console.log('inside place tile')
        let tile_id = bga.gamedatas.board[this.id].tile
        console.log('tile_id', tile_id)
        let tile = bga.gamedatas.tiles[tile_id]
        console.log('tile', tile)
        let color_id = bga.gamedatas.tiles[tile_id].color_id
        console.log('color_id', color_id)
        this.square.addToStockWithId(color_id, tile_id, 'topbar')
    }
}

module.exports = { getTileUniqueType }