import map from 'lodash/fp/map'

import React from 'react'
import { connect } from 'react-redux'
import { Tile, TooltipDefinition } from 'carbon-components-react'

import { withColumn } from '../../hoc/grid'

import ChooseBackground from './choose-background'

const Background = ({ background = {} }) => (<Tile>
  <h3>{background.name}
    <small>(Find more at: {background.findMore})</small>
  </h3>

  <h6>Traits</h6>
  <div>
    {map(background.traits)(t => <TooltipDefinition key={t.atomUid} tooltipText={t.description}>{t.name}</TooltipDefinition>)}
  </div>

  <h6>Bonus & Malus</h6>
  <div>
    {map(background.bonusMalus)(bm => bm.name)}
  </div>

  <h6>Limitations</h6>
  <div>
    {map(background.limitations)(bm => bm)}
  </div>

  <h6>Description</h6>
  <p dangerouslySetInnerHTML={{ __html: background.description }} />
</Tile>)

const mapStateToProps = ({refData}) => ({
  background: refData.backgrounds.data[refData.backgrounds.selected]
})

const BackgroundEnhanced = withColumn({ xs: 4 })(connect(mapStateToProps)(Background))
const ChooseBackgroundEnchanced = withColumn({ xs: 2 })(ChooseBackground)

const BackgroundLayout = () => (<React.Fragment><ChooseBackgroundEnchanced /><BackgroundEnhanced /></React.Fragment>)

export default BackgroundLayout
