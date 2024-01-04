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

module.exports = { getTileUniqueType }