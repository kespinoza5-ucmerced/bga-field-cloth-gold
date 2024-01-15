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
    constructor (action) {
        this.id = action.id
        this.name = action.name
        this.hasAttachedSquare = action.hasAttachedSquare
    }

    getToken(bga) {
        const action = bga.gamedatas.board[this.id]

        if (action.token == null)
            return false

        return { player: action.player, id: action.token }
    }

    moveTokenToSpace(bga, token = null) {
        if (token == null)
            token = this.getToken(bga)

        if (token == false)
            return false

        const dest_location_selector = 'circle_action_'+this.id
        const token_selector = 'token_'+token.player+'_'+token.id

        bga.slideToObject(token_selector, dest_location_selector).play()
    }
}

class DragonAction extends Action {
    constructor(action) {
        super(action)
    }

    replaceDragon() {

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
        let tile_id = bga.gamedatas.board[this.id].tile
        let color_id = bga.gamedatas.tiles[tile_id].color_id
        this.square.addToStockWithId(color_id, tile_id, 'topbar')
    }

    removeTile(bga) {
        let tile_id = bga.gamedatas.board[this.id].tile
        this.square.removeFromStockById(tile_id)
    }
 }

// module.exports = { }