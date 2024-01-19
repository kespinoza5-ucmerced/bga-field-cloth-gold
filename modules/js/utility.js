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

    moveTokenToSpace(bga, token) {
        if (token.id == null)
            return false

        const dest_location_selector = 'circle_action_'+token.loc
        const token_selector = 'token_'+token.player+'_'+token.id

        bga.slideToObject(token_selector, dest_location_selector).play()
    }
}

class DragonAction extends Action {
    constructor(action, bga) {
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

    placeTile(bga, tile) {
        if (tile.id == null)
            return false

        this.square.addToStockWithId(tile.color, tile.id, 'topbar')
    }

    removeTile(bga, tile) {
        if (tile.id == null)
            return false

        this.square.removeFromStockById(tile.id)
    }
}

class Tableau {
    constructor(bga, player_id) {
        this.player_id = player_id
        this.tableau = new ebg.stock()
        bga.initTileStock(this.tableau, 'playertabletile_'+player_id)
    }

    takeTileFromOffboard(bga, tile) {
        this.tableau.addToStockWithId(tile.color, tile.id, 'topbar')
    }

    takeTileFromAction(bga, tile, action_id) {
        const action_square_selector = 'square_action_'+action_id
        this.tableau.addToStockWithId(tile.color, tile.id, action_square_selector)
    }

}

// module.exports = { }