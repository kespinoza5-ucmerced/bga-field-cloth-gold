// import { getTileUniqueType } from "../js_utility/utility";

const getTileUniqueType = require('./../modules/js/utility').getTileUniqueType

describe('Tests that tile colors are correctly associated to numeric value', () => {
    it('blue matches', () => {
        expect(getTileUniqueType('blue')).toBe(1);
    });

    it('red matches', () => {
        expect(getTileUniqueType('red')).toBe(2);
    });
    
    it('gold matches', () => {
        expect(getTileUniqueType('gold')).toBe(3);
    });

    it('white matches', () => {
        expect(getTileUniqueType('white')).toBe(4);
    });

    it('green matches', () => {
        expect(getTileUniqueType('green')).toBe(5);
    });
})