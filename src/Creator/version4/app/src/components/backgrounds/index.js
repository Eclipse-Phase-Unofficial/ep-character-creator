import map from 'lodash/fp/map'

import React from 'react'
import { connect } from 'react-redux'
import { FormLabel, TooltipDefinition } from 'carbon-components-react'

import { withColumn } from '../../hoc/grid'
import { Card } from '../../common/cards'

import ChooseBackground from './background-list'

const Background = ({background = {}}) => (
  <Card title={background.name}>
    <h3>
      <small>(Find more at: {background.findMore})</small>
    </h3>

    <FormLabel>Traits</FormLabel>
    <div>
      {map(t => <TooltipDefinition key={t.atomUid}
                                   tooltipText={t.description}>{t.name}</TooltipDefinition>)(background.traits)}
    </div>

    <FormLabel>Bonus & Malus</FormLabel>
    <div>
      {map(bm => bm.name)(background.bonusMalus).join(', ')}
    </div>

    <FormLabel>Limitations</FormLabel>
    <div>
      {map(bm => bm)(background.limitations).join(', ')}
    </div>

    <FormLabel>Description</FormLabel>
    <p dangerouslySetInnerHTML={{__html: background.description}}/>
  </Card>
)

const mapStateToProps = ({refData}) => ({
  background: refData.backgrounds.data[refData.backgrounds.selected]
})

const ChooseBackgroundEnhanced = withColumn({xs: 2})(ChooseBackground)
const BackgroundEnhanced = withColumn({xs: 4})(connect(mapStateToProps)(Background))

const BackgroundLayout = () => (<React.Fragment><ChooseBackgroundEnhanced/><BackgroundEnhanced/></React.Fragment>)

export default BackgroundLayout
