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

module.exports = { getTileUniqueType }