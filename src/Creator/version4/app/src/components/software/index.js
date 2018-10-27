import map from 'lodash/fp/map'

import React, { Fragment } from 'react'
import { FormLabel } from 'carbon-components-react'
import { connect } from 'react-redux'

import { withColumn } from '../../hoc/grid'
import { Card } from '../../common/cards'
import SoftwareList from './software-list'

const SoftwareCard = ({ai: software = {}}) => (
  <Card title={software.name}>

    <FormLabel>Cost</FormLabel>
    <p>{software.cost} Credits</p>

    {/*<FormLabel>Aptitudes</FormLabel>*/}
    {/*<p>{map(a => a.name)(software.aptitudes).join(', ')}</p>*/}

    {/*<FormLabel>Skills</FormLabel>*/}
    {/*<p>{map(a => a.name)(software.skills).join(', ')}</p>*/}


    <FormLabel>Description</FormLabel>
    <p dangerouslySetInnerHTML={{__html: software.description}}/>

  </Card>
)


const mapStateToProps = ({refData}) => ({
  ai: refData.softGear.data[refData.softGear.selected]
})

const EnhancedSoftwareList = withColumn({ xs: 2 })(SoftwareList)
const EnhancedSoftwareCard = withColumn({ xs: 4 })(connect(mapStateToProps)(SoftwareCard))

const SoftwareLayout = () => <Fragment><EnhancedSoftwareList /><EnhancedSoftwareCard /></Fragment>

export default SoftwareLayout
