import map from 'lodash/fp/map'

import React, { Fragment } from 'react'
import { connect } from 'react-redux'

import { Card } from '../../common/cards'
import { withColumn } from '../../hoc/grid'

import AiList from './ai-list'
import { FormLabel } from 'carbon-components-react'

const AiCard = ({ai = {}}) => (
  <Card title={ai.name}>

    <FormLabel>Cost</FormLabel>
    <p>{ai.cost} Credits</p>

    <FormLabel>Aptitudes</FormLabel>
    <p>{map(a => a.name)(ai.aptitudes).join(', ')}</p>

    <FormLabel>Skills</FormLabel>
    <p>{map(a => a.name)(ai.skills).join(', ')}</p>


    <FormLabel>Description</FormLabel>
    <p dangerouslySetInnerHTML={{__html: ai.description}}/>

  </Card>
)

const mapStateToProps = ({refData}) => ({
  ai: refData.ai.data[refData.ai.selected]
})

const EnhancedAiList = withColumn({xs: 2})(AiList)
const EnhancedAiCard = withColumn({xs: 4})(connect(mapStateToProps)(AiCard))

const AiLayout = () => <Fragment><EnhancedAiList /><EnhancedAiCard /></Fragment>
export default AiLayout
