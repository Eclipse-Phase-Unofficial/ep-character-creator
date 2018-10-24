import values from 'lodash/fp/values'

import React, { Fragment } from 'react'
import { connect } from 'react-redux'

import { List, NameAndBookLine } from '../../common/list'
import { withColumn } from '../../hoc/grid'

const SoftwareList = ({software}) => (
  <Fragment>
    <List
      title="Choose a software"
      values={software}
      renderLine={NameAndBookLine}
    />
  </Fragment>
)

const mapStateToProps = ({refData}) => ({
  software: values(refData.softGear.data)
})

export default connect(mapStateToProps)(withColumn({ xs: 2 })(SoftwareList))
