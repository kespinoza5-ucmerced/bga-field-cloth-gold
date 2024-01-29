# bga-field-cloth-gold

Digital adaptation of the 2-player board game [The Field of Cloth of Gold](https://boardgamegeek.com/boardgame/309752/field-cloth-gold), on BoardGameArena.com.

# License
Falls under [BGA License](https://github.com/kespinoza5-ucmerced/bga-field-cloth-gold/blob/main/LICENCE_BGA).

# Project Progress
##  To-do
- [ ] Implement actions
  - [ ] Dragon action
  - [x] Secrecy action
  - [x] Gold action
  - [x] Blue action
  - [x] White action
  - [x] Red action
  - [x] Purple action
- [ ] Implement scoring
- [ ] Implement undo
- [ ] Revamp UI
- [ ] Improve animation timing
- [ ] Improve error handling

## Completed
- [x] Token placement/movement
- [x] Tile redraw
- [x] Tile gifting

# Development
## Initialize development environment
Requires `node` and `npm`. 

To initialize development environment, run:
```
npm i
```

## Run project
This project cannot be run locally, and must instead be run from BGA servers. Create a developer account and a new project (using this guide https://boardgamearena.com/doc/First_steps_with_BGA_Studio).

## Setup file sync
Project files are hosted remotely and can be accessed and modified through SFTP (can be set up using this guide https://boardgamearena.com/doc/Tools_and_tips_of_BGA_Studio#File_sync).

**Note**: Highly recommended to use the `| .git/; node_modules/` file mask to improve sync times and reduce server use.

# Test
Testing is accomplished through `jest`. Test suite validates helper functions in the `modules/js` directory.

To execute test suite, run:
```
npm run test
```
